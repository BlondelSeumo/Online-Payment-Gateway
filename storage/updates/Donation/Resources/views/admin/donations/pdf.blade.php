@extends('admin.pdf.app')

@section('title', __('Campaign pdf'))

@section('content')
    <div class="mt-30">
        <table class="table">
            <tr class="table-header">
                <td>{{ __('Date') }}</td>
                <td>{{ __('User') }}</td>
                <td>{{ __('Campaign Title') }}</td>
                <td>{{ __('Goal Amount') }}</td>
                <td>{{ __('Raised Amount') }}</td>
                <td>{{ __('Currency') }}</td>
                <td>{{ __('Campaign Type') }}</td>
                <td>{{ __('Fee Bearer') }}</td>
            </tr>

            @foreach($donations as $donation)
                <tr class="tb-row">
                    <td>{{ dateFormat($donation->end_date) }}</td>
                    <td>{{ getColumnValue($donation->creator) }}</td>
                    <td>{{ $donation->title }}</td>
                    <td>{{ formatNumber($donation->goal_amount, $donation->currency_id) }}</td>
                    <td>{{ formatNumber($donation->raised_amount, $donation->currency_id) }}</td>
                    <td>{{ optional($donation->currency)->code }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $donation->donation_type)) }}</td>
                    <td>{{ ucfirst($donation->fee_bearer) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
