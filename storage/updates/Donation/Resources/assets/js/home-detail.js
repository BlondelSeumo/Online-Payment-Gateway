"use strict";

$(document).ready(function(){
  
    if($("input[name=donation_type]").val() == 'any_amount')
    {
      $('#amount').val('');
    }
    $(".action").on('click',function(){
      $('.amount').removeClass('active-color');
      $(this).addClass('active-color')
  
    });
  });

  $(document).on('click', '#copyBtn', function(e) {
    e.preventDefault();
    $('#result').removeAttr('disabled').select().attr('disabled', 'true');
    document.execCommand('copy');
    $('#copiedMessage').show();
    $('#copiedMessage').delay(3000).fadeOut('slow');
    
})

function clipboard(elem, event) {
    var $input = elem.prev('input[type="text"]');
    $input.focus().select();
    document.execCommand(event);
    $input.blur();
    elem.addClass('clicked');
    $('.copy-link').addClass('d-none');

    // Remove the classes after 3 seconds
    setTimeout(function() {
        elem.removeClass('clicked');
        $('.copy-link').removeClass('d-none');
    }, 3000);
}
$('.copys-btn').on('click', function(){
    clipboard($(this), 'copy')
});

$(window).on('load', function() {
  $('#payer_info').hide();

  var classList = $('#other-amount').attr('class');

  if (classList == 'other-amount background-color') {
    $('#amount-div').show();
  } else if (donationType == 'suggested_amount'){
    $('#amount-div').hide();
  }

  let text = $('#payment_method').find("option:selected").text().toLowerCase();
  $('#gateway').val(text);
});

$('.suggested-amount').on('click', function () {
  if ($(this).attr('id') == 'first-suggestion') {
    $('#first-suggestion').addClass('active-color');
    $('#second-suggestion, #third-suggestion, .other-amount').removeClass('active-color');
    $('#amount').val($(this).attr('data-amount')).trigger('change');
  } else if ($(this).attr('id') == 'second-suggestion') {
    $('#first-suggestion, #third-suggestion, .other-amount').removeClass('active-color');
    $('#second-suggestion').addClass('active-color');
    $('#amount').val($(this).attr('data-amount')).trigger('change');
  } else if ($(this).attr('id') == 'third-suggestion') {
    $('#first-suggestion, #second-suggestion, .other-amount').removeClass('active-color');
    $('#third-suggestion').addClass('active-color');
    
    $('#amount').val($(this).attr('data-amount')).trigger('change');
  }
  $('#amount-div').removeClass('d-block');
});

$('.other-amount').on('click', function () {
  $('#amount').val('');
  $('.other-amount').addClass('active-color');
  $('#first-suggestion, #second-suggestion, #third-suggestion').removeClass('active-color');
  $('#amount-div').addClass('d-block');
});

function restrictNumberToPrefdecimalOnInput(e) {
  var type = $('#currency_type').data('type')
  restrictNumberToPrefdecimal(e, type);
}
