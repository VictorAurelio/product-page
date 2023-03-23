const validateForm = (event, showNotification, handleSave) => {
    event.preventDefault();

    const form = event.target;
    if (!form.checkValidity()) {
        for (let i = 0; i < form.length; i++) {
            if (!form[i].checkValidity()) {
                showNotification(form[i].validationMessage, 'error');
                return;
            }
        }
    }
    handleSave(event); // Call the handlesave function only if the form is valid
};

export default validateForm;