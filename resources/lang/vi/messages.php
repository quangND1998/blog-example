<?php

return [
    'exception' => [
        'api_not_found' => [
            'status' => 400,
            'msg' => 'Không tìm thấy API',
        ],
        'unauthenticate' => [
            'status' => 401,
            'msg' => 'Yêu cầu đăng nhập',
        ],
        'authorization' => [
            'status' => 403,
            'msg' => 'Yêu cầu quyền truy cập',
        ],
        'model_not_found' => [
            'status' => 404,
            'msg' => 'Không tìm thấy dữ liệu',
        ],
        'method_not_allow' => [
            'status' => 405,
            'msg' => 'Yêu cầu không hợp lệ',
        ],
        'invalid_data' => [
            'status' => 422,
            'msg' => 'Dữ liệu không hợp lệ',
        ],
        'internal_error' => [
            'status' => 500,
            'msg' => 'Lỗi',
        ],
    ],
];
