import { Toast } from '../components/Toast';

const validateForm = (event, form, callback) => {
    event.preventDefault();
    
    let isValid = true;

    for (let i = 0; i < form.length; i++) {
        const field = form[i];
        const isRequired = field.getAttribute('data-required') !== null;
        
        // Verify the field's validity only if it's required
        if (isRequired && !field.checkValidity()) {
            Toast({ message: 'Please, provide the data of indicated type', type: 'warning' });
            isValid = false;
            break;
        }
        // Verify if the field is required and empty
        if (isRequired && field.value.trim() === '') {
            Toast({ message: 'Please, submit the required data', type: 'warning' });
            isValid = false;
            break;
        }

    }

    if (isValid) {
        callback(event); // Callback function(e.g handleSave) only if the form is valid
    }
};

export default validateForm;
