
'use strict';


$(document).on('click', '.copy-link', function(e) {
    var donationId = $(this).attr('id');
    var tempInput = document.createElement("input");
    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
    tempInput.value = $('#donation-link-' + donationId).val();
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    Swal.fire({title: copiedText, text: null, icon: "success"}
    );
})

$(document).on('click', '.delete-donation', function(e) {
    var donationId = $(this).attr('data-id');
    event.preventDefault(); 
    Swal.fire({
        title: confirmText,
        icon: 'warning',
        width: 600,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCancelButton: true,
        confirmButtonColor: '#7066e0',
        cancelButtonColor: '#d33',
        confirmButtonText: deleteButtonText,
        cancelButtonText: cancelButtonText,
    }).then((result) => {
        if (result.isConfirmed) {
            $('#delete-form-' + donationId).trigger('submit'); 
        } else {
            Swal.fire(
                safeDonationText,
            )
        }
    })
})