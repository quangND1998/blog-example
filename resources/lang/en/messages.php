<?php

return [
    'exception' => [
        'api_not_found' => [
            'status' => 400,
            'msg' => 'API endpoint not found',
        ],
        'unauthenticate' => [
            'status' => 401,
            'msg' => 'Authenticate is required',
        ],
        'authorization' => [
            'status' => 403,
            'msg' => 'Acttion forbidden',
        ],
        'model_not_found' => [
            'status' => 404,
            'msg' => 'Data not found',
        ],
        'method_not_allow' => [
            'status' => 405,
            'msg' => 'Method not allowed',
        ],
        'invalid_data' => [
            'status' => 422,
            'msg' => 'Invalid data',
        ],
        'internal_error' => [
            'status' => 500,
            'msg' => 'Internal server error',
        ],
    ],
];
