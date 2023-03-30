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

/**
 * Configuration file which holds some of the information required by the system
 */
class Config
{
    /**
     * Defines constants used in the application.
     * 
     * @return void
     */
    public function constants()
    {
        define('ROOT', dirname(dirname(__FILE__)) . 'backend/App');
        define('ENVIRONMENT', 'production');
        define('JWT_SECRET_KEY', '!A@mda!@$%sMAao28man8o');
        define('DEFAULT_ACTION', 'index');
        define('JWT_EXPIRATION_TIME', 604800); // one week expiration time
    }
    /**
     * Sets the configuration variables for the environment.
     * 
     * @return void
     */
    public function environmentType()
    {
        if (ENVIRONMENT === 'development') {
            define('BASE_URL', '');
            define('DB_NAME', '');
            define('DB_HOST', '');
            define('DB_USER', '');
            define('DB_PASS', '');
        } else {
            define('BASE_URL', '');
            define('DB_NAME', '');
            define('DB_HOST', '');
            define('DB_USER', '');
            define('DB_PASS', '');
        }
    }
    /**
     * Configures the CORS headers to allow cross-origin resource sharing.
     * 
     * @return void
     */
    public function configureCors()
    {
        $this->setCorsHeaders();
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
    /**
     * Sets the Access-Control-Allow headers to allow CORS.
     * 
     * @return void
     */
    private function setCorsHeaders()
    {
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        header('Access-Control-Allow-Origin: *');
    }
}
