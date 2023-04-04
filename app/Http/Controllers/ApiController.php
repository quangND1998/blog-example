<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\_H\Response\ApiResponse;

class ApiController extends Controller
{
    /**
     * Return ApiResponse class
     */
    protected function response()
    {
        return (new ApiResponse);
    }
}
