<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Change contact email
    |--------------------------------------------------------------------------
    |
    */

    'email' => env('SAURON_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | jwa - Json WHOIS API Credentials
    |--------------------------------------------------------------------------
    |
    | https://jsonwhoisapi.com/register
    | https://jsonwhoisapi.com/dashboard
    |
    */

    'jsonwhoisapi_customer_id' => env('SAURON_JSONWHOISAPI_CUSTOMER_ID'),
    'jsonwhoisapi_api_key' => env('SAURON_JSONWHOISAPI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Github Gist ID
    |--------------------------------------------------------------------------
    |
    | https://gist.github.com/
    |
    */

    'github_gist_id' => env('SAURON_GITHUB_GIST_ID'),

    /*
    |--------------------------------------------------------------------------
    | List of DNS records to check
    |--------------------------------------------------------------------------
    |
    */
    'records' => [
        'a',        // Host Address (A records)
        'cname',    // Canonical Name (CNAME records)
        'mx',       // Mail Exchange record (MX records)
        'ns',       // Name Servers (NS records)
        'spf',      // Sender Policy Framework (SPF records)
        'txt',      // Text record (TXT records)
    ],

    /*
    |--------------------------------------------------------------------------
    | http://www.dns-lg.com/
    |--------------------------------------------------------------------------
    |
    */
    'locations' => [
        //[
        //    'location' => 'China',
        //    'node' => 'cn01',
        //    'operator' => 'CNNIC',
        //],
        [
            'location' => 'Germany',
            'node' => 'de01',
            'operator' => 'Chaos Computer Club',
        ],
        //[
        //    'location' => 'Netherlands',
        //    'node' => 'nl01',
        //    'operator' => 'StatDNS',
        //],
        [
            'location' => 'Switzerland',
            'node' => 'ch01',
            'operator' => 'Swiss Privacy Foundation',
        ],
        [
            'location' => 'United States',
            'node' => 'us01',
            'operator' => 'DNS-OARC',
        ],
    ],

];