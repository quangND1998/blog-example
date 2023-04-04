<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    public function __invoke()
    {
        return file_get_contents(public_path() . '/build/index.html');
    }
}
