'use strict';

$(document).ready(function() {
    $('#paymentMethodForm').on('submit', function() {

        const selectedValue = $('input[name="method"]:checked').val();
        localStorage.setItem('checkboxValue', selectedValue);

        $("#paymentMethodSubmitBtn").attr("disabled", true);
        $(".spinner").removeClass('d-none');
        $("#paymentMethodSubmitBtnText").text(paymentMethodSubmitBtnText);
        $(".arrow").addClass('d-none');

        setTimeout(function () {
            $(".spinner").addClass('d-none');
            $(".arrow").removeClass('d-none');
            $("#paymentMethodSubmitBtn").attr("disabled", false);
            $("#paymentMethodSubmitBtnText").text(pretext);

        }, 2000);
    });

    var selectedPaymentMethod = $('input[type="radio"][name="method"]:checked').val();
    if (selectedPaymentMethod !== undefined) {
        checkAmountLimitAndFeesLimit(selectedPaymentMethod);
    }

    var storedValue = localStorage.getItem('checkboxValue');

    if (storedValue !== null && $('#' + storedValue).length > 0) {
        localStorage.removeItem('checkboxValue');
        $('#' + storedValue).addClass('gateway-selected');
    } else {
        if ($('input[type="checkbox"]').length > 0) {
            $('input[type="checkbox"]').first().prop('checked', true);
        }
        if ($('.gateway').length > 0) {
            $('.gateway').first().addClass('gateway-selected');
        }
    }
});

$('input[type="radio"][name="method"]').change(function() {
    checkAmountLimitAndFeesLimit($(this).val());
});


function checkAmountLimitAndFeesLimit(method) {
    var token = $('[name="_token"]').val();
    var amount = $('input[type="hidden"][name="amount"]').val();
    var currency_id = $('input[type="hidden"][name="currency_id"]').val();
    var currency_symbol = $('input[type="hidden"][name="currency_symbol"]').val();
    var donation_id = $('input[type="hidden"][name="donation_id"]').val();

    if (amount != '') {
        $.post({
            url: feesLimitUrl,
            dataType: "json",
            data: {
                "_token": token,
                'amount': amount,
                'currencyId': currency_id,
                'currencySymbol': currency_symbol,
                'donationId': donation_id,
                'method': method,
            }
        }).done(function(response) {
            if (response.success.status == 200) {
                $("#donationFeesAmount").html(response.success.totalFees);
                $("#donationTotalAmount").html(response.success.totalAmount);
            }
        });
    }
}
