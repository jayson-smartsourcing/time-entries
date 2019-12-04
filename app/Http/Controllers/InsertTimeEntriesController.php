<?php

namespace App\Http\Controllers;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Support\Arr;
use Validator;
use Illuminate\Http\Request;
use App\DinglesFDTimeEntry as DinglesFDTimeEntry;
use Carbon\Carbon as Carbon;



class InsertTimeEntriesController extends Controller
{
   

    public function __construct(
        DinglesFDTimeEntry $dingles_fd_time_entries
    )
    {  
        $this->dingles_fd_time_entries = $dingles_fd_time_entries;
        
    }

    //convert hours to decimal
    public function decimalHours($time){
        $hms = explode(":", $time);
        return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
    }


    public function parseImport(Request $request){

        $validator = Validator::make($request->all(), [
            'api_key' => 'required',
            'csv_file' => 'required',
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
          
            foreach($errors as $key => $value) {
                $errors[$key] = $value[0];
            }

            return response()->json(['error'=>$errors], 200);            

        }

         // get account from API key
         //Concatenate API Key input with':X'
         $base_key = $request->input('api_key');
         $base_key .= ":X"; 
         $input_api_key = base64_encode($base_key);
 
         //Get info from constants.php
         $data = config('constants');
 
         //Counter for each Account
         $count = count($data);
         $end_of_array = 0;
 
         foreach($data as $key =>$value){
             $const_api_key = Arr::get($value, 'api_key');
           
             //Check if API Key exists
             if($const_api_key == $input_api_key){
                 $api_key_account = $const_api_key;
                 $db_init = Arr::get($value, 'db_init');
             }
             else{
                 //Check if loop is at the end of the Array
                 $end_of_array++;
                 if($end_of_array == $count){
                     $api_key_account = "API Key does not exist";
                 }
             }
         }

         //get time entry model
         $db_init .= "_time_entries";
         $time_entry_model = $db_init;

         //delete entries from the last 3 months
         $date = Carbon::now()->subMonths(3);
         $this->{$time_entry_model}->bulkDeleteByCreatedAtDate($date);

        //Parse CSV File
        $path = $request->file('csv_file')->getRealPath();
        $csv_data = array_map('str_getcsv', file($path));    
        
        $counter = 0;
        //create new array
        foreach($csv_data as $key => $value){
            
            //disregard header
            if($key !=0){

                //trim ticket ID
                $ticket_id_orig = Arr::get($value, '11');
                $result = explode('-', $ticket_id_orig, 2);
                $ticket_id = (int) Arr::get($result, '0');
                $subject = Arr::get($result, '1');

                //parse date
                $orig_date = Arr::get($value, '4');
                $parsed_date = Carbon::parse($orig_date)->toDateTimeString();

                //parse created at
                $orig_created_at = Arr::get($value, '2');
                $parsed_created_at = Carbon::parse($orig_created_at)->toDateTimeString();

                //parse hours to decimal
                $orig_hours = Arr::get($value, '6');
                $parsed_hours = $this->decimalHours($orig_hours);
                


                $new_time_entries = array(
                    'ticket_id'=> $ticket_id,
                    'agent' => Arr::get($value, '0'),
                    'charge_type' => Arr::get($value, '1'),
                    'customer' => Arr::get($value, '3'),
                    'date' => $parsed_date, 
                    'hours'=>  (float) $parsed_hours,
                    'notes'=> Arr::get($value, '7'),
                    'product'=> Arr::get($value, '9'),
                    'subject'=> $subject,
                    'attendance_id'=> " ",
                    'created_at'=> $parsed_created_at,
                    
                );

                $time_entries_final[] = $new_time_entries;
                $counter++;

                if($counter == 100){
                    $this->{$time_entry_model}->bulkInsert($time_entries_final);
                    $counter = 0;
                    $time_entries_final = array();
                }
            }
        }
           
       //stored procedure 

        return view('csv-parse-data');
    }
    
}
