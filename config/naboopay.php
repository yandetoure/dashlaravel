<?php declare(strict_types=1); 

return [
    /*
    |--------------------------------------------------------------------------
    | NabooPay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration avec l'API NabooPay
    | Documentation: https://docs.naboopay.com/docs/intro/
    |
    */

    'api_token' => env('NABOOPAY_API_TOKEN'),
    'api_url' => env('NABOOPAY_API_URL', 'https://api.naboopay.com/api/v1'),
    
    /*
    |--------------------------------------------------------------------------
    | URLs de redirection
    |--------------------------------------------------------------------------
    */
    'success_url' => env('NABOOPAY_SUCCESS_URL', env('APP_URL') . '/payment/success'),
    'error_url' => env('NABOOPAY_ERROR_URL', env('APP_URL') . '/payment/error'),
    
    /*
    |--------------------------------------------------------------------------
    | Méthodes de paiement supportées
    |--------------------------------------------------------------------------
    */
    'supported_methods' => [
        'WAVE',
        'ORANGE_MONEY',
        'FREE_MONEY',
        'BANK'
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Limites de transaction
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'max_amount' => 2000000, // 2,000,000 XOF
        'min_amount' => 10, // 10 XOF
        'max_products' => 20,
        'min_products' => 1,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuration des cashouts
    |--------------------------------------------------------------------------
    */
    'cashout' => [
        'min_wave' => 10,
        'min_orange_money' => 10,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Timeout des requêtes
    |--------------------------------------------------------------------------
    */
    'timeout' => 30,
    
    /*
    |--------------------------------------------------------------------------
    | Rate limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => 30, // 30 requêtes par fenêtre de temps
];
