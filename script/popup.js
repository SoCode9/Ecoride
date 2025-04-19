function showPopup(id) {
    document.getElementById(id).style.display = 'block';
}

function closePopup(id) {
    document.getElementById(id).style.display = 'none';

    // for the user space page : calls the closePopupValidate function
    if (typeof closePopupValidate === 'function') {
        closePopupValidate();
    }
}
