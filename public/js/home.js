function buttonEnabled(txtArea,submitButton) {
    //var submitButton = document.getElementById("confirm-post");

    if (txtArea.value.trim().length >= 3) {
        submitButton.disabled = false;
        submitButton.classList.add('enabled');
    } else {
        submitButton.disabled = true;
        submitButton.classList.remove('enabled');
    }
}

