<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
    'https://erp-website-chi.vercel.app',
    'https://erp-website-7a2gfyhd2-sany002s-projects.vercel.app',
    'http://localhost:3000',
],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
