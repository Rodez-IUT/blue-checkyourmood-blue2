const toastTrigger = document.getElementById('confirmationSupp');
const toastConfirmation = document.getElementById('confirmationToast');

if (toastTrigger) {
    toastTrigger.addEventListener('click', () => {
    const toast = new bootstrap.Toast(toastConfirmation);

    toast.show();
    })
}