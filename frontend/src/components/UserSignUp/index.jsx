import React from 'react';
import InputField from '../InputField';
import { ToastContainer } from 'react-toastify';
import './styles.scss';

const UserSignUp = ({ formRef, handleValidation }) => {
    return (
        <div>
            <ToastContainer />
            <div className="container">
                <form
                    id="userSignUpForm"
                    ref={formRef}
                    onSubmit={handleValidation}
                >
                    <InputField
                        label="Name"
                        id="signUpNameField"
                        name="name"
                        type="text"
                        required
                    />
                    <InputField
                        label="Email"
                        id="signUpEmailField"
                        name="email"
                        type="email"
                        required
                    />
                    <InputField
                        label="Password"
                        id="signUpPasswordField"
                        name="password"
                        type="password"
                        required
                    />
                    <InputField
                        label="Password Confirmation"
                        id="signUpPasswordConfirmationField"
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
