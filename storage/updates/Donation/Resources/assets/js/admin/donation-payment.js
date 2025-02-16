'use strict';

//Date range as a button
$('#daterange-btn').daterangepicker({
    ranges   : {
    'Today'       : [moment(), moment()],
    'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    'This Month'  : [moment().startOf('month'), moment().endOf('month')],
    'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
    },
    function (start, end)
    {
        sDate = moment(start, 'MMMM D, YYYY').format(sessionDateFinal);
        $('#startfrom').val(sDate);

        eDate = moment(end, 'MMMM D, YYYY').format(sessionDateFinal);
        $('#endto').val(eDate);

        $('#daterange-btn span').html('&nbsp;' + sDate + ' - ' + eDate + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    }
)

$(document).ready(function() {
    $("#daterange-btn").on('mouseover', function() {
        $(this).css('background-color', 'white');
        $(this).css('border-color', 'grey !important');
    });

    if (startDate == '') {
        $('#daterange-btn span').html('<i class="fa fa-calendar"></i> &nbsp;&nbsp; '+ dateRangePickerText +' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    } else {
        $('#daterange-btn span').html(startDate + ' - ' + endDate + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    }

    $("#user_input").on('keyup keypress', function(e) {
        if (e.type=="keyup" || e.type=="keypress") {
            var user_input = $('form').find("input[type='text']").val();
            if(user_input.length === 0) {
                $('#user_id').val('');
                $('#error-user').html('');
                $('form').find("button[type='submit']").prop('disabled',false);
            }
        }
    });

    $('#user_input').autocomplete({
        source:function(req,res) {
            if (req.term.length > 0) {
                $.ajax({
                    url: userSearchUrl,
                    dataType:'json',
                    type:'get',
                    data:{
                        search:req.term
                    },
                    success:function (response)
                    {
                        $('form').find("button[type='submit']").prop('disabled',true);

                        if(response.status == 'success') {
                            res($.map(response.data, function (item) {
                                return {
                                    id : item.payer_id,
                                    first_name : item.payer.first_name,
                                    last_name : item.payer.last_name, 
                                    value: item.payer.first_name + ' ' + item.payer.last_name
                                }
                            }));
                        } else if(response.status == 'fail') {
                            $('#error-user').addClass('f-12 text-danger').html(userErrorText);
                        }
                    }
                })
            } else {
                $('#user_id').val('');
            }
        },
        select: function (event, ui)
        {
            var e = ui.item;

            $('#error-user').html('');

            $('#user_id').val(e.id);

            $('form').find("button[type='submit']").prop('disabled',false);
        },
        minLength: 0,
        autoFocus: true
    });
});

// csv
$(document).ready(function()
{
    $('#csv').on('click', function(event) {
        event.preventDefault();

        var startfrom = $('#startfrom').val();
        var endto = $('#endto').val();
        var status = $('#status').val();
        var currency = $('#currency').val();
        var payment_methods = $('#payment_methods').val();
        var user_id = $('#user_id').val();

        window.location = ADMIN_URL+"/campaign-payments/csv?startfrom=" + startfrom + "&endto=" + endto + "&status=" + status + "&currency=" + currency + "&payment_method=" + payment_methods + "&user_id=" + user_id;
    });
});

// pdf
$(document).ready(function()
{
    $('#pdf').on('click', function(event) {
        event.preventDefault();

        var startfrom = $('#startfrom').val();
        var endto = $('#endto').val();
        var status = $('#status').val();
        var currency = $('#currency').val();
        var payment_methods = $('#payment_methods').val();
        var user_id = $('#user_id').val();

        window.location = ADMIN_URL+"/campaign-payments/pdf?startfrom=" + startfrom + "&endto=" + endto + "&status=" + status + "&currency=" + currency + "&payment_method=" + payment_methods + "&user_id=" + user_id;
    });
});