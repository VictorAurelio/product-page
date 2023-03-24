import { toast } from 'react-toastify';

const useNotifications = () => {
    const showNotification = (message, type = 'error', fieldId, duration = 3000) => {
        switch (type) {
            case 'success':
                toast.success(message, { autoClose: duration });
                break;
            case 'warning':
                toast.warning(message, { autoClose: duration });
                break;
            case 'info':
                toast.info(message, { autoClose: duration });
                break;
            case 'error':
            default:
                toast.error(message, { autoClose: duration });
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