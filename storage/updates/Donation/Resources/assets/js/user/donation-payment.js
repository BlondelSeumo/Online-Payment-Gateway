'use strict';

$(function() {
    var sDate;
    var eDate;

    $('#daterange-btn').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),

        }, function (start, end) {
            sDate = moment(start, 'MMMM D, YYYY').format('DD-MM-YYYY');
            $('#startfrom').val(sDate);
            eDate = moment(end, 'MMMM D, YYYY').format('DD-MM-YYYY');
            $('#endto').val(eDate);
            $('#daterange-btn p').html(sDate + ' - ' + eDate);
        }
    )
    
    if (startDate == '') {
        $('#daterange-btn p').html(dateRangePickerText);
    } else {
        $('#daterange-btn p').html(startDate + ' - ' + endDate);
    }
});

$(document).ready(function () {

    var status = $('#status').val();
    var type = $('#payment_method').val();
    var currency = $('#currency').val();
    if (startDate != '' || status != 'all' || type != 'all' || currency != 'all') {
        $(".filter-panel").css('display', 'block');
    }
    $(".fil-btn").on('click', function () {
        $(this).find('img').toggle();
        $(".filter-panel").slideToggle(300);
    });
});