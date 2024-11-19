<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths'                    => ['*'], // Rotas que suportam CORS
    'allowed_methods'          => ['*'], // Métodos permitidos
    'allowed_origins'          => ['http://localhost:5173'], // Domínio do frontend
    'allowed_origins_patterns' => [],
    'allowed_headers'          => ['*'], // Cabeçalhos permitidos
    'exposed_headers'          => [],
    'max_age'                  => 0,
    'supports_credentials'     => true,

];
