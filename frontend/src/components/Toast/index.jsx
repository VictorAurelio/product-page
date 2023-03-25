import { toast, Bounce } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export function Toast({ message, type, willClose }) {
    toast[type](message, {
        position: "top-right",
        autoClose: willClose ? willClose : 1500,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        theme: "colored",
        transition: Bounce,
    });
}

export const clearToasts = () => {
    toast.dismiss();
};