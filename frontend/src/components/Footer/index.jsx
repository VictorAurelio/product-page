import React from 'react';
import './styles.scss';

const Footer = () => {
    return (
        <footer className="footer">
                <div className="row">
                    <a href="https://github.com/VictorAurelio/product-page"><i className="fa fa-github"></i></a>
                    <a href="#"><i className="fa fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/victor-aurelio-a8700b17a/"><i className="fa fa-linkedin"></i></a>
                </div>
                <div className="row">
                    <ul>
                        <li><a href="https://wa.me/5562983314425">Contact me</a></li>
                        <li><a href="#">About me</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div className="row">
                    Copyright © 2021 - All rights reserved || Junior Test Assignment
                </div>
        </footer>
    )
}

export default Footer;
