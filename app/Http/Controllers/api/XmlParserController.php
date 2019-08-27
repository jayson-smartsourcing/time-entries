<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use File;
use Response;

class XmlParserController extends Controller
{
    public function parseToJson() {
       $file = Input::file('file');
       $full_name = $file->getClientOriginalName();
       $xml = simplexml_load_file($file);
       $data = json_encode($xml,JSON_PRETTY_PRINT);
       $full_name = explode(".", $full_name);
       $new_file_name = $full_name[0].".json";
       $path = storage_path() ."/json1/".$new_file_name;
       File::put($path,$data);

       return response()->download($path);

    }
}
