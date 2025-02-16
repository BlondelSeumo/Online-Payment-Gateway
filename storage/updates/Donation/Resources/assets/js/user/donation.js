'use strict';

$(window).on('load', function() {
    var donationType = $("#campaign-type option:selected").val();
    showHideDonationTypeValue(donationType);
});

function restrictNumberToPrefdecimalOnInput(e) {
    var type = $('select#currency').find(':selected').data('type')
    restrictNumberToPrefdecimal(e, type);
}

$('#donation-form').on('submit', function() {
    $(".spinner").removeClass('d-none');
    $("#donation-submit-btn").attr("disabled", true);
    $("#donation-submit-btn-text").text(submitButtonText);
});

$('#campaign-type').on('change', function() {
    var donationType = $("#campaign-type option:selected").val();
    showHideDonationTypeValue(donationType);
    $('#fixed-amount, #first-suggestion, #second-suggestion, #third-suggestion').each(function() {
        this.setCustomValidity('');
    });
});

$('#banner-image').on('change', function() {
    readPicture(this);
});

$('#title').on('keyup', function() {
    const campaignTitle = $("#title").val();
    const regex = /^[a-zA-Z0-9\s]*$/;
    
    if (!regex.test(campaignTitle)) {
        $("#title-error").text(titleErrorText);
        $("#donation-submit-btn").attr("disabled", true);
    } else {
        $("#title-error").text('');
        $("#donation-submit-btn").attr("disabled", false);
    }
});

function showHideDonationTypeValue(donationType) { 
    const $elem = $("#suggestion-div");     
    if (donationType == 'any_amount') {
        $('#campaign-type-div').removeClass('col-12');
        $('#campaign-type-div').addClass('col-6');
        $('#fixed-amount-div').hide();
        $elem[0].style.setProperty('display', 'none', 'important');;
        $('#fixed-amount').prop('required', false);
        $('#first-suggestion').prop('required', false);
        $('#second-suggestion').prop('required', false);
        $('#third-suggestion').prop('required', false);
        if (feeApplicable == 'yes') {
            $('#image-div').removeClass('col-12');
            $('#image-div').addClass('col-6');
        } else {
            $('#image-div').removeClass('col-6');
            $('#image-div').addClass('col-12');
        }
    } else if(donationType == 'fixed_amount') {
        $('#campaign-type-div').removeClass('col-12');
        $('#campaign-type-div').addClass('col-6');
        $('#fixed-amount-div').show();
        $elem[0].style.setProperty('display', 'none', 'important');;
        $('#fixed-amount').prop('required', true);
        $('#first-suggestion').prop('required', false);
        $('#second-suggestion').prop('required', false);
        $('#third-suggestion').prop('required', false);
        if (feeApplicable == 'yes') {
            $('#image-div').removeClass('col-6');
            $('#image-div').addClass('col-12');
        } else {
            $('#image-div').removeClass('col-12');
            $('#image-div').addClass('col-6');
        }
    } else {
        $('#campaign-type-div').removeClass('col-6');
        $('#campaign-type-div').addClass('col-12');
        $('#fixed-amount-div').hide();
        $('#suggestion-div').show();
        $('#fixed-amount').prop('required', false);
        $('#first-suggestion').prop('required', true);
        $('#second-suggestion').prop('required', true);
        $('#third-suggestion').prop('required', true);
        if (feeApplicable == 'yes') {
            $('#image-div').removeClass('col-6');
            $('#image-div').addClass('col-12');
        } else {
            $('#image-div').removeClass('col-12');
            $('#image-div').addClass('col-6');
        }
    }
}

$(function() {
    $('#end-date').datepicker({
        autoclose: true,
        changeYear: true,
        changeMonth: true,
        dateFormat: "dd-mm-yy",
        yearRange: "-0:+5",
        minDate: 0
    });

    $('#end-date').on('focus', function() {
        $('select').css('visibility', 'visible')
    });
});

function readPicture(input) {

    var file, img, width, height;

    if (input.files && input.files[0]) {

        file = input.files[0]
        img = new Image();
        var objectUrl = _URL.createObjectURL(file);
     
        img.onload = function() {
            
            width = this.width;
            height = this.height;

            if (width < 365 || height < 200) {
                document.getElementById('banner-image').value = "";
                $('#dimension-error').text(minImageDimensionError);
            } else {
                $('#dimension-error').text(' ');
            }
            _URL.revokeObjectURL(objectUrl);
            displayPicture(width, height, input);
        };

        img.src = objectUrl;
    }
}

function displayPicture(width, height, input) {
    if (width >= 365 || height >= 200) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#banner-image-preview')
                .attr('src', e.target.result)
                .width(250)
                .height(100);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#banner-image-preview').attr('src', '');
    }
}

var isNumberOrDecimalPointKey = function(value, e) {

    var charCode = (e.which) ? e.which : e.keyCode;

    if (charCode == 46) {
        if (value.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
    }
    return true;
}