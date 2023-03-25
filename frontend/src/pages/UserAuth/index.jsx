// src/pages/UserAuth/index.jsx
import React, { useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import UserSignIn from '../../components/UserSignIn';
import UserSignUp from '../../components/UserSignUp';
import Header from '../../components/Header';
import { Toast } from '../../components/Toast';
import validateForm from '../../utils/validateForm';
import './styles.scss';

const UserAuth = () => {
    const navigate = useNavigate();
    const signInFormRef = useRef(null);
    const signUpFormRef = useRef(null);

    const handleSignIn = async (event) => {
        const email = document.querySelector('#signInEmailField').value;
        const password = document.querySelector('#signInPasswordField').value;

        const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_HANDLE_USER_SIGN_IN}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email,
                password,
            }),
        });
        console.log(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_HANDLE_USER_SIGN_IN}`);
        if (response.status === 201) {
            const data = await response.json();
            console.log(data.message);

            localStorage.setItem("jwt", data.jwt);
            localStorage.setItem("userId", data.userId);

            // Redirect the user to home if the sign-in was successful
            navigate(`${process.env.REACT_APP_BASE_URL}`);
        } else {
            const errorResult = await response.json();
            console.error(response.statusText);

            // Display a generic error notification
            Toast({ message: `An error occurred while signing in`, type: 'error' });
        }
    };

    const handleSignUp = async (event) => {
        const name = document.querySelector('#signUpNameField').value;
        const email = document.querySelector('#signUpEmailField').value;
        const password = document.querySelector('#signUpPasswordField').value;
        const passwordConfirmation = document.querySelector('#signUpPasswordConfirmationField').value;

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

            // Redirect the user to home if the sign-up was successful
            navigate(`${process.env.REACT_APP_BASE_URL}`);
        } else {
            const errorResult = await response.json();
            console.error(response.statusText);

            // Display a generic error notification
            Toast({ message: `An error occurred while signing up`, type: 'error' });
        }
    };

    const handleValidation = (event, form, callback) => {
        validateForm(event, form, callback);
    };

    const handleSignInValidation = (event) => {
        handleValidation(event, signInFormRef.current, handleSignIn);
    };

    const handleSignUpValidation = (event) => {
        handleValidation(event, signUpFormRef.current, handleSignUp);
    };

    const handleCancel = () => {
        navigate(`${process.env.REACT_APP_BASE_URL}`);
    };

    return (
        <div className="user-auth-container">
            <div className="sign-in-container">
                <Header
                    title="User Sign In"
                    onSubmit={handleSignIn}
                    formRef={signInFormRef}
                    handleValidation={handleSignInValidation}
                    buttons={[
                        {
                            id: 'signInButton',
                            type: 'submit',
                            title: 'Sign In',
                        },
                        {
                            id: 'cancel-edit-btn',
                            type: 'button',
                            onClick: handleCancel,
                            title: 'Cancel',
                        },
                    ]}
                />
                <UserSignIn formRef={signInFormRef} handleValidation={handleSignInValidation} />
            </div>
            <div className="sign-up-container">
                <UserSignUp formRef={signUpFormRef} handleValidation={handleSignUpValidation} />
                <Header
                    title="User Sign Up"
                    onSubmit={handleSignUp}
                    formRef={signUpFormRef}
                    handleValidation={handleSignUpValidation}
                    buttons={[
                        {
                            id: 'signUpButton',
                            type: 'submit',
                            title: 'Sign Up',
                        },
                        {
                            id: 'cancel-edit-btn',
                            type: 'button',
                            onClick: handleCancel,
                            title: 'Cancel',
                        },
                    ]}
                />
            </div>
        </div>
    );
};

export default UserAuth;
