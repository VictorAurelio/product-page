import { Routes } from 'react-router-dom';
import { BrowserRouter as Router } from "react-router-dom";
import React from 'react';
import ReactDOM from 'react-dom';
import './index.scss';
import App from './App';
import Footer from './components/Footer';
import Container from './components/Container';
export { default } from './components/Container';

const root = ReactDOM.createRoot(document.getElementById("root"));

root.render(
    <React.StrictMode>
      <Router>
            <Container>
                <App />
                <Routes />
            </Container>
            <Footer />
      </Router>
    </React.StrictMode>
  );

