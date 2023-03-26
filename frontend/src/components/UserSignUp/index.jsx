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
                        placeholder={'Type your name...'}
                        required
                    />
                    <InputField
                        label="Email"
                        id="signUpEmailField"
                        name="email"
                        type="email"
                        placeholder={'Type your email...'}
                        required
                    />
                    <InputField
                        label="Password"
                        id="signUpPasswordField"
                        name="password"
                        type="password"
                        placeholder={'Type your password...'}
                        required
                    />
                    <InputField
                        label="Password Confirmation"
                        id="signUpPasswordConfirmationField"
                        name="passwordConfirmation"
                        type="password"
                        placeholder={'Confirm your password...'}
                        required
                    />
                </form>
            </div>
        </div>
    );
};

export default UserSignUp;
