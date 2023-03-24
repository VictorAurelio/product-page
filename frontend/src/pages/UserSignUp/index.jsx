import React, { useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import Header from '../../components/Header';
import { Toast } from '../../components/Toast';
import InputField from '../../components/InputField';
import { ToastContainer } from 'react-toastify';
import validateForm from '../../utils/validateForm';
import './styles.scss';

const UserSignUp = () => {
    const navigate = useNavigate();
    const formRef = useRef(null);

    const handleSignUp = async (event) => {
        const name = document.querySelector('#nameField').value;
        const email = document.querySelector('#emailField').value;
        const password = document.querySelector('#passwordField').value;
        const passwordConfirmation = document.querySelector('#passwordConfirmationField').value;

        const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_HANDLE_USER_SIGN_UP}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name,
                email,
                password,
                password_confirmation: passwordConfirmation,
            }),
        });

        if (response.status === 201) {
            const data = await response.json();
            console.log(data.message);

            localStorage.setItem("jwt", data.jwt);
            localStorage.setItem("userId", data.userId);

            // Redirect the user to home if the signup was successful
            navigate('/');
        } else {
            const errorResult = await response.json();
            console.error(response.statusText);

            // Display a generic error notification
            Toast({ message: 'An error occurred while signing up', type: 'error' });
        }
    };

    const handleValidation = (event, form) => {
        const newEvent = { ...event, target: form };
        validateForm(event, form, handleSignUp.bind(null, newEvent));
    };

    return (
        <div>
            <ToastContainer />
            <Header
                title="User Sign Up"
                formRef={formRef}
                handleValidation={handleValidation}
                buttons={[
                    {
                        id: 'signUpButton',
                        type: 'submit',
                        title: 'Sign Up',
                    },
                ]}
            />
            <div className="container">
                <form
                    id="userSignUpForm"
                    ref={formRef}
                    onSubmit={handleValidation}
                >
                    <InputField
                        label="Name"
                        id="nameField"
                        name="name"
                        type="text"
                        required
                    />
                    <InputField
                        label="Email"
                        id="emailField"
                        name="email"
                        type="email"
                        required
                    />
                    <InputField
                        label="Password"
                        id="passwordField"
                        name="password"
                        type="password"
                        required
                    />
                    <InputField
                        label="Password Confirmation"
                        id="passwordConfirmationField"
                        name="passwordConfirmation"
                        type="password"
                        required
                    />
                </form>
            </div>
        </div>
    );
};

export default UserSignUp;
