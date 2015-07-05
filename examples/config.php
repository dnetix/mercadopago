<?php

/**
 *  Data values required to instanciate the MercadoPago class
 */
$config = [
    'appId' => 'YOUR_APP_ID',
    'secretKey' => 'YOUR_APP_SECRET_KEY',
    'authorization_url' => 'http://authorization-url-given',
    'notification_url' => 'http://notification-url-given',
    'currency' => 'COP',
    // Optional, redirection URLS to use when ending payments
    'back_urls' => [
        'success' => '',
        'pending' => '',
        'failure' => ''
    ],
    // If its a global marketplace fee you can stablish it here
    'fee' => null
];