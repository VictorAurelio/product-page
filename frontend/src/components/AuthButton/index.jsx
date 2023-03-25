import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUser } from '@fortawesome/free-solid-svg-icons';
import { useNavigate } from 'react-router-dom';
import './styles.scss';

const AuthButton = () => {
  const navigate = useNavigate();

  const jwt = localStorage.getItem('jwt');
  const isLoggedIn = Boolean(jwt);

  const handleSignInSignUp = () => {
    navigate('/user/');
  };

  const handleLogout = () => {
    localStorage.removeItem('jwt');
    localStorage.removeItem('userId');
    window.location.reload();
  };

  return (
    <div className={`auth-button-container ${isLoggedIn ? 'auth-button-logged-in' : 'auth-button-logged-out'}`}>
      <button className="auth-button" onClick={isLoggedIn ? handleLogout : handleSignInSignUp}>
        <FontAwesomeIcon icon={faUser} />
      </button>
      <div className="auth-button-tooltip">{isLoggedIn ? 'Logout' : 'Sign In / Sign Up'}</div>
    </div>
  );
};

export default AuthButton;
