"use strict";

$(document).on('submit', '#donation-preference-setting-form', function() {
    $("#preference-submit-btn").attr("disabled", true);
    $(".fa-spinner").removeClass('d-none');
    $("#preference-submit-btn-txt").text(submitButtonText);
});