// for showing the swal on dynamic ways
function showSawl(textFor,icons,showCancelButton=false,confirmtext) {
    Swal.fire({
        text: textFor,
        icon: icons,
        showCancelButton: showCancelButton,
        confirmButtonText: confirmtext,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ms-2'
        },
        buttonsStyling: false
    });
}

// for updating first letter of charateries 
function toUpperFirstLetter(ActiveToken)
{
  return ActiveToken.charAt(0).toUpperCase()+ActiveToken.substr(1);
}