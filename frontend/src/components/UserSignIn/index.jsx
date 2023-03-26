import React from 'react';
import InputField from '../InputField';
import { ToastContainer } from 'react-toastify';
import './styles.scss';

const UserSignIn = ({ formRef, handleValidation }) => {
    return (
        <div>
            <ToastContainer />
            <div className="container">
                <form
                    id="userSignInForm"
                    ref={formRef}
                    onSubmit={handleValidation}
                >
                {/* <h2>Already signed up? Sign In...</h2> */}
                    <InputField
                        label="Email"
                        id="signInEmailField"
                        name="email"
                        type="email"
                        placeholder={'Type your email...'}
                        required
                    />
                    <InputField
                        label="Password"
                        id="signInPasswordField"
                        name="password"
                        type="password"
                        placeholder={'Type your password...'}
                        required
                    />
                </form>
            </div>            
        </div>        
    );
};

export default UserSignIn;
