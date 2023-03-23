const validateForm = (event, showNotification, callback) => {
    event.preventDefault();

    const form = event.target;
    let isValid = true;

    for (let i = 0; i < form.length; i++) {
        const field = form[i];
        const isRequired = field.getAttribute('data-required') !== null;

        // Verify if the field is required and empty
        if (isRequired && field.value.trim() === '') {
            showNotification('Please, submit required data');
            isValid = false;
            break;
        }

        // Verify the field's validity
        if (!field.checkValidity()) {
            showNotification('Please, provide the data of indicated type');
            isValid = false;
            break;
        }
    }

    if (isValid) {
        callback(event); // Callback function(e.g handleSave) only if the form is valid
    }
};

export default validateForm;
