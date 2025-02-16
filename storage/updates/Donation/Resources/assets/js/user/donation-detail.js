
'use strict';


$(document).on('click', '#copyBtn', function(e) {
    $('.donation_url').select();
    document.execCommand('copy');
    Swal.fire({title: copiedText, text: null, icon: "success"}
    );
})

$(document).on('click', '.delete-donation', function(e) {
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
            $('#donation-delete-form').trigger('submit'); 
        } else {
            Swal.fire(
                safeDonationText,
            )
        }
    })
})