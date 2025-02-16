<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TokenReceivedTransactionsDataTable;
use App\Exports\TokenReceivesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TokenReceivedTransactionController extends Controller
{
    protected $transaction;
    protected $cryptoCurrency;

    public function __construct()
    {
        $this->transaction = new Transaction();
    }

    public function index(TokenReceivedTransactionsDataTable $dataTable)
    {
        $data['menu']  = 'transaction';
        $data['sub_menu'] = 'token-received-transactions';

        $data['cryptoReceivedTransactionsCurrencies'] = $this->transaction->with('currency:id,code')->where('transaction_type_id', Token_Received)->groupBy('currency_id')->get(['currency_id']);
        
        $data['from'] = isset(request()->from) ? setDateForDb(request()->from) : null;
        $data['to'] = isset(request()->to ) ? setDateForDb(request()->to) : null;
        $data['currency'] = isset(request()->currency) ? request()->currency : 'all';
        $data['user'] = $user = isset(request()->user_id) ? request()->user_id : null;
        $data['getName'] = $this->transaction->getTransactionsUsersEndUsersName($user, Token_Received);
        return $dataTable->render('admin.token_transactions.received.index', $data);
    }

    public function tokenReceivedTransactionsSearchUser(Request $request)
    {
        $search = $request->search;
        $user = $this->transaction->getTransactionsUsersResponse($search, Token_Received);
        $res = [
            'status' => 'fail',
        ];
        if (count($user) > 0) {
            $res = [
                'status' => 'success',
                'data' => $user,
            ];
        }
        return json_encode($res);
    }

    public function tokenReceivedTransactionsCsv()
    {
        return Excel::download(new TokenReceivesExport(), 'token_received_transactions_list_' . time() . '.csv');
    }

    public function tokenReceivedTransactionsPdf()
    {
        $from = !empty(request()->startfrom) ? setDateForDb(request()->startfrom) : null;
        $to = !empty(request()->endto) ? setDateForDb(request()->endto) : null;
        $currency = isset(request()->currency) ? request()->currency : null;
        $user = isset(request()->user_id) ? request()->user_id : null;

        $data['getCryptoReceivedTransactions'] = $this->transaction->getCryptoReceivedTransactions($from, $to, $currency, $user, Token_Received)->orderBy('transactions.id', 'desc')->get();
        if (isset($from) && isset($to)) {
            $data['date_range'] = $from . ' To ' . $to;
        } else {
            $data['date_range'] = 'N/A';
        }

        // Input parameters (view, filename, printdata)
        generatePDF('admin.token_transactions.received.token_received_transactions_report_pdf', 'token_received_transactions_report_', $data);
    }

    public function view($id)
    {
        $data['menu']  = 'transaction';
        $data['sub_menu'] = 'token-received-transactions';

        $data['transaction'] = $transaction = $this->transaction->with([
            'user:id,first_name,last_name',
            'end_user:id,first_name,last_name',
            'currency:id,code,symbol',
            'payment_method:id,name',
            'cryptoAssetApiLog:id,object_id,payload,confirmations',
        ])
        ->where('transaction_type_id', Token_Received)
        ->exclude(['merchant_id', 'bank_id', 'file_id', 'refund_reference', 'transaction_reference_id', 'email', 'phone', 'note'])
        ->find($id);

        // Get crypto api log details for Token_Received
        if (!empty($transaction->cryptoAssetApiLog)) {
            $getCryptoDetails = getCryptoPayloadConfirmationsDetails($transaction->transaction_type_id, optional($transaction->cryptoAssetApiLog)->payload, optional($transaction->cryptoAssetApiLog)->confirmations);
            if (count($getCryptoDetails) > 0) {

                if (isset($getCryptoDetails['senderAddress'])) {
                    $data['senderAddress']   = $getCryptoDetails['senderAddress'];
                }
                if (isset($getCryptoDetails['receiverAddress'])) {
                    $data['receiverAddress'] = $getCryptoDetails['receiverAddress'];
                }
                if (isset($getCryptoDetails['network'])) {
                    $data['network'] = $getCryptoDetails['network'];
                }
                $data['txId'] = $getCryptoDetails['txId'];
                $data['confirmations']   = $getCryptoDetails['confirmations'];
            }
        }
        return view('admin.token_transactions.received.view', $data);
    }

}
