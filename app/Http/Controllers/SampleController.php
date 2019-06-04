<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class SampleController extends Controller
{
    function testMethod() {
        print_r(Input::only("username"));
    }
}
