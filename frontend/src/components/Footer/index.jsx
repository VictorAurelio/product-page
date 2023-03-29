import React from 'react';
import './styles.scss';

const Footer = () => {
    return (
        <footer className="footer">
                <div className="row">
                    <a href="https://github.com/VictorAurelio" target="_blank"><i className="fa fa-github"></i></a>
                    <a href="https://www.instagram.com/_victoraurelio/" target="_blank"><i className="fa fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/victor-aurelio-a8700b17a/" target="_blank"><i className="fa fa-linkedin"></i></a>
                </div>
                <div className="row">
                    <ul>
                        <li><a href="https://wa.me/5562983314425" target="_blank">Contact me</a></li>
                        <li><a href="https://github.com/VictorAurelio/product-page" target="_blank">Find this repository</a></li>
                    </ul>
                </div>
                <div className="row">
                    Copyright © 2023 - All rights reserved || Scandiweb Junior Test Assignment
                </div>
        </footer>
    )
}

export default Footer;
