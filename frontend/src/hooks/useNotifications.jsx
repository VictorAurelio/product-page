import { toast } from 'react-toastify';

const useNotifications = () => {
    const showNotification = (message, type = 'error', fieldId) => {
        switch (type) {
            case 'success':
                toast.success(message);
                break;
            case 'warning':
                toast.warning(message);
                break;
            case 'info':
                toast.info(message);
                break;
            case 'error':
            default:
                toast.error(message);
                if (fieldId) {
                    const field = document.getElementById(fieldId);
                    const errorSpan = field.nextElementSibling;
                    errorSpan.innerText = message;
                }
                break;
        }
    };

    return { showNotification };
};

export default useNotifications;