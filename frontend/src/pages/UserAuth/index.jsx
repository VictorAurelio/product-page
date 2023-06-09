// src/pages/UserAuth/index.jsx
import React, { useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import UserSignIn from '../../components/UserSignIn';
import UserSignUp from '../../components/UserSignUp';
import Header from '../../components/Header';
import { Toast, clearToasts } from '../../components/Toast';
import validateForm from '../../utils/validateForm';
import Button from '../../components/Button';
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

        if (response.status === 201) {
            const data = await response.json();
            console.log(data.message);

            localStorage.setItem("jwt", data.jwt);
            localStorage.setItem("userId", data.userId);

            // Clear existing toasts before redirecting
            clearToasts();
            // Redirect the user to home if the sign-in was successful
            navigate(`${process.env.REACT_APP_BASE_URL}`);
        } else if(response.status === 401) {
            // Display the error notification
            Toast({ message: "Invalid email or password", type: 'error' });
        } else {
            const errorResult = await response.json();
            console.error(response.statusText);
            console.log(errorResult);

            let errorMessage = '';
            const keys = Object.keys(errorResult);

            for (let i = 0; i < keys.length; i++) {
                const key = keys[i];
                const errors = JSON.parse(errorResult[key][0]);
                if (errors && errors.message) {
                    errorMessage = errors.message;
                    break;
                }
            }
            console.log(errorMessage);
            // Display the error notification
            Toast({ message: errorMessage, type: 'error' });
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

            // Clear existing toasts before redirecting
            clearToasts();
            // Redirect the user to home if the sign-up was successful
            navigate(`${process.env.REACT_APP_BASE_URL}`);

        } else {
            const errorResult = await response.json();
            console.error(response.statusText);
            console.log(errorResult);

            let errorMessage = '';
            const keys = Object.keys(errorResult);

            for (let i = 0; i < keys.length; i++) {
                const key = keys[i];
                const errors = JSON.parse(errorResult[key][0]);
                if (errors && errors.message) {
                    errorMessage = errors.message;
                    break;
                }
            }
            console.log(errorMessage);
            // Display the error notification
            Toast({ message: errorMessage, type: 'error' });
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

    const goHome = () => {
        navigate(`${process.env.REACT_APP_BASE_URL}`);
    };
    
    return (
        <div className="user-auth-container">
            <div className="header-container">
                <Header
                    title="User Registration"
                    buttons={[
                        {
                            id: 'home-btn',
                            type: 'button',
                            onClick: goHome,
                            title: 'Home',
                        },
                    ]}
                />
            </div>
            <div className="sign-in-container">
                <UserSignIn formRef={signInFormRef} handleValidation={handleSignInValidation} />
                <Button
                    id='signInButton'
                    type='submit'
                    title='Sign In'
                    handleValidation={handleSignInValidation}
                    formRef={signInFormRef}
                    onSubmit={handleSignIn}
                />
            </div>
            <div className="sign-up-container">
                <UserSignUp formRef={signUpFormRef} handleValidation={handleSignUpValidation} />
                <Button
                    id='signUpButton'
                    type='submit'
                    title='Sign Up'
                    handleValidation={handleSignUpValidation}
                    formRef={signInFormRef}
                    onSubmit={handleSignUp}
                />
            </div>
        </div>
    );
};

export default UserAuth;
