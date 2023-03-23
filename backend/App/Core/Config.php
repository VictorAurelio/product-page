<?php

/**
 * This file is part of my Product Page project.
 *
 * @category  Config
 * @package   App\Core
 * @author    Victor Aurélio Rodrigues Ribeiro <victoraurelio_198@hotmail.com>
 * @copyright (c) 2023 Victor Aurelio
 * @link      https://github.com/VictorAurelio/product-page
 */

namespace App\Core;

class Config
{
    public function constants()
    {
        define('ROOT', dirname(dirname(__FILE__)) . 'backend/App');
        define('ENVIRONMENT', 'development');
        define('JWT_SECRET_KEY', '!A@mda!@$%sMAao28man8o');
        define('DEFAULT_ACTION', 'index');
        define('JWT_EXPIRATION_TIME', 604800); // one week expiration time
    }
    public function environmentType()
    {
        if (ENVIRONMENT === 'development') {
            define('BASE_URL', 'http://localhost/productpage/backend/public');
            define('DB_NAME', 'prodpage');
            define('DB_HOST', 'localhost');
            define('DB_USER', 'root');
            define('DB_PASS', '');
        } else {
            // define('BASE_URL', 'https://www.mywebsite.com');
            // define('DB_NAME', 'mvc');
            // define('DB_HOST', 'localhost');
            // define('DB_USER', 'root');
            // define('DB_PASS', '');
        }
    }
    public function configureCors()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->setCorsHeaders();
            exit;
        }
    }
    private function setCorsHeaders()
    {
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Origin: *');
    }
}