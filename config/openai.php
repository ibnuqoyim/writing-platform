<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | This value is the API key for OpenAI. This value is used when the
    | framework needs to interact with OpenAI services.
    |
    */
    'api_key' => env('OPENAI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Organization ID
    |--------------------------------------------------------------------------
    |
    | This value is the Organization ID for OpenAI. This is optional and only
    | needed if you belong to multiple organizations.
    |
    */
    'organization' => env('OPENAI_ORGANIZATION'),
];
