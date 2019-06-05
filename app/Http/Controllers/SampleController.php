<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
//models
use App\flights as flights;

use Illuminate\Http\Request;

class SampleController extends Controller
{   
    public function __construct(
        flights $flights
    )
    {  
       $this->flights = $flights;
    }

    public function testMethod() {
        $data = Input::all();
        $return = $this->flights->insert($data);
        $all_data = $this->flights->getAll();
        return response()->json(['success'=> true,'all_data'=>$all_data], 200);
    }

    // public function getAllData() {
    //     $data = $this->
    // }
}
