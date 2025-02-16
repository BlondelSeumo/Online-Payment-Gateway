'use strict';

$(document).ready(function() {
    // csv
    $('#csv').on('click', function(event) {

        event.preventDefault();
        var currency = $('#currency').val();
        var type = $('#type').val();
        var user_id = $('#user_id').val();

        window.location = ADMIN_URL+"/campaigns/csv?startfrom=" + "&currency=" + currency + "&type=" + type + "&user_id=" + user_id;
    });

    // pdf
    $('#pdf').on('click', function(event) {
        event.preventDefault();

        var currency = $('#currency').val();
        var type = $('#type').val();
        var user_id = $('#user_id').val();

        window.location = ADMIN_URL+"/campaigns/pdf?startfrom=" + "&currency=" + currency + "&type=" + type + "&user_id=" + user_id;
    });

    // user search
    $('#user-input').autocomplete({
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
                                    id : item.creator_id, 
                                    first_name : item.creator.first_name, 
                                    last_name : item.creator.last_name, 
                                    value: item.creator.first_name + ' ' + item.creator.last_name
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
        select: function (event, ui) {
            var e = ui.item;
            $('#error-user').html('');
            $('#user_id').val(e.id);
            $('form').find("button[type='submit']").prop('disabled',false);
        },
        minLength: 0,
        autoFocus: true
    });
});