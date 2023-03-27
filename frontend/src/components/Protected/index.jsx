import React from 'react';
import { Outlet, Navigate } from 'react-router-dom';

const Protected = (props) => {
  const isAuthenticated = localStorage.getItem('jwt') !== null;

  return isAuthenticated ? <Outlet {...props} /> : <Navigate to="/user" />;
};

export default Protected;
