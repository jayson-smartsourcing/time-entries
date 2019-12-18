<?php

namespace App\Http\Controllers;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Support\Arr;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon as Carbon;
//models 
use App\DinglesFDTimeEntry as DinglesFDTimeEntry;
use App\RaywhiteFDTimeEntry as RaywhiteFDTimeEntry;
use App\HarrisSalesFDTimeEntryV2 as HarrisSalesFDTimeEntryV2;
use App\JGFDTimeEntry as JGFDTimeEntry;
use App\CameronFDTimeEntry as CameronFDTimeEntry;
use App\DixonFDTimeEntry as DixonFDTimeEntry;
use App\ToureastFDTimeEntry as ToureastFDTimeEntry;
use App\RECDFDTimeEntry as RECDFDTimeEntry;
use App\HBurgersFDTimeEntry as HBurgersFDTimeEntry;
use App\JCNEFDTimeEntry as JCNEFDTimeEntry;
use App\JCNFDTimeEntry as JCNFDTimeEntry;
use App\JCNFinanceFDTimeEntry as JCNFinanceFDTimeEntry;
use App\EGFDTimeEntry as EGFDTimeEntry; 
use App\EmurunFDTimeEntry as EmurunFDTimeEntry;
use App\LJHookerFDTimeEntry as LJHookerFDTimeEntry;
use App\AvnuFDTimeEntry as AvnuFDTimeEntry;
use App\TagflixFDTimeEntry as TagflixFDTimeEntry; 
use App\LHFDTimeEntry as LHFDTimeEntry;
use App\DSFDTimeEntry as DSFDTimeEntry; 
use App\JCDFDTimeEntry as JCDFDTimeEntry;
use App\JCSFDTimeEntry as JCSFDTimeEntry; //armadale
use App\JCFSTimeEntry as JCFSTimeEntry; // FS
use App\JCBFSTimeEntry as JCBFSTimeEntry;
use App\HarrisFSTimeEntry as HarrisFSTimeEntry;
use App\BPTimeEntry as BPTimeEntry;


class InsertTimeEntriesController extends Controller
{
    public function __construct(
        DinglesFDTimeEntry $dingles_fd_time_entries,
        RaywhiteFDTimeEntry $raywhite_fd_time_entries,
        HarrisSalesFDTimeEntryV2 $harris_fd_time_entries,
        JGFDTimeEntry $jg_fd_time_entries,
        CameronFDTimeEntry $cameron_fd_time_entries,
        DixonFDTimeEntry $dixon_fd_time_entries,
        ToureastFDTimeEntry $toureast_fd_time_entries,
        RECDFDTimeEntry $recd_fd_time_entries,
        HBurgersFDTimeEntry $hburgers_fd_time_entries,
        JCNEFDTimeEntry $jcne_fd_time_entries,
        JCNFDTimeEntry $jcn_fd_time_entries,
        JCNFinanceFDTimeEntry $jcn_finance_fd_time_entries,
        EGFDTimeEntry $eg_fd_time_entries,
        EmurunFDTimeEntry $emurun_fd_time_entries,
        LJHookerFDTimeEntry $ljh_wl_fd_time_entries,
        AvnuFDTimeEntry $ljh_avnu_fd_time_entries,
        TagflixFDTimeEntry $tagflix_fd_time_entries,
        LHFDTimeEntry $lh_fd_time_entries,
        DSFDTimeEntry $ds_fd_time_entries,
        JCDFDTimeEntry $jcd_fd_time_entries,
        JCSFDTimeEntry $jcs_fd_time_entries,
        JCFSTimeEntry $jck_fs_time_entries, //FS
        JCBFSTimeEntry $jcb_fs_time_entries,
        HarrisFSTimeEntry $harris_fs_time_entries,
        BPTimeEntry $bp_fs_time_entries
    )
    {  
        $this->dingles_fd_time_entries = $dingles_fd_time_entries;
        $this->raywhite_fd_time_entries = $raywhite_fd_time_entries;
        $this->harris_fd_time_entries = $harris_fd_time_entries;
        $this->jg_fd_time_entries = $jg_fd_time_entries;
        $this->cameron_fd_time_entries = $cameron_fd_time_entries;
        $this->dixon_fd_time_entries = $dixon_fd_time_entries;
        $this->toureast_fd_time_entries = $toureast_fd_time_entries;
        $this->recd_fd_time_entries = $recd_fd_time_entries;
        $this->hburgers_fd_time_entries = $hburgers_fd_time_entries;
        $this->jcne_fd_time_entries = $jcne_fd_time_entries;
        $this->jcn_fd_time_entries = $jcn_fd_time_entries;
        $this->jcn_finance_fd_time_entries = $jcn_finance_fd_time_entries;
        $this->eg_fd_time_entries = $eg_fd_time_entries;
        $this->emurun_fd_time_entries = $emurun_fd_time_entries;
        $this->ljh_wl_fd_time_entries = $ljh_wl_fd_time_entries;
        $this->ljh_avnu_fd_time_entries = $ljh_avnu_fd_time_entries;
        $this->tagflix_fd_time_entries = $tagflix_fd_time_entries;
        $this->lh_fd_time_entries = $lh_fd_time_entries;
        $this->ds_fd_time_entries = $ds_fd_time_entries;
        $this->jcd_fd_time_entries = $jcd_fd_time_entries;
        $this->jcs_fd_time_entries = $jcs_fd_time_entries;
        $this->jck_fs_time_entries = $jck_fs_time_entries; //FS
        $this->jcb_fs_time_entries = $jcb_fs_time_entries;
        $this->harris_fs_time_entries = $harris_fs_time_entries;
        $this->bp_fs_time_entries = $bp_fs_time_entries;
    }

    //function to convert hours into decimal 
    public function decimalHours($time){
        $hms = explode(":", $time);
        return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
    }

    //main function for parsing csv file and inserting to db
    public function parseImport(Request $request){

        //validating required form fields
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

         //Concatenate API Key input with ':X'
         $base_key = $request->input('api_key');
         $base_key .= ":X"; 
         $input_api_key = base64_encode($base_key);
 
         //Get data from constants.php
         $constant_data = config('constants');
        
         //Counter for each Account
         $acc_counter = count($constant_data);
         $end_of_array = 0;
 
         //loop through constants 
         foreach($constant_data as $key => $value){
             $const_api_key = Arr::get($value, 'api_key');
           
             //Check if API Key exists
             if($const_api_key == $input_api_key){
                 $api_key_account = $const_api_key;
                 $db_init = Arr::get($value, 'db_init'); //initials of db table name
                 $orig_db_init = $db_init;

                 $link = Arr::get($value, 'link');

                 //check if API key is for freshdesk or freshservice
                 if(strpos($link, 'freshdesk')!== false){
                    $fresh_model = "FD";
                }else if (strpos($link, 'freshservice')!== false){
                    $fresh_model ="FS";
                }              
             }
             else{
                 //Check if loop is at the end of the Array
                 $end_of_array++;
                 if($end_of_array == $acc_counter){
                    return back()->withErrors('API Key is incorrect');
                 }
             }
         }
        

        //concatenate db_init + _time_entries to get time entry model 
        $db_init .= "_time_entries";
        $time_entry_model = $db_init;     
       
        //map csv file 
        $csv_data = $fields = array(); $i = 0;
        $handle = fopen($request->file('csv_file'), "r");
        if ($handle) {
            while (($row = fgetcsv($handle, 4096)) !== false) {
                if (empty($fields)) {
                    $fields = $row;
                    continue;
                }
                foreach ($row as $k=>$value) {
                    if(!isset($fields[$k])){
                        break;
                    }
                    $csv_data[$i][$fields[$k]] = $value;
                }
                $i++;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }

        //delete latest time entries
        if($fresh_model == "FS"){
            $row = end($csv_data); 
            $date = Carbon::parse($row['Executed at']); 
        } else {
            $row = reset($csv_data); 
            $date = Carbon::parse($row['Date']); 
        }
        // $this->{$time_entry_model}->bulkDeleteByCreatedAtDate($date);

             
    
        //create new array
        $time_entries_final = array();

        $counter = 0;
        $over_all_count = 0;

        $unique = array();

        foreach($csv_data as $key => $value){                   
            //trim ticket ID
            $ticket_id_orig = $value['Ticket'];
            $result = explode('-', $ticket_id_orig, 2);
            $ticket_id = (int) Arr::get($result, '0');
            $subject = Arr::get($result, '1');
            $new_subject = htmlspecialchars_decode($subject);
            

            //parse created at
            $orig_created_at = $value['Created at'];
            $parsed_created_at = Carbon::parse($orig_created_at)->toDateTimeString();

            //remove special characters from agent name
            $orig_agent_name = $value['Agent'];
            $new_agent_name = preg_replace('/[^a-zA-ZñÑ0-9\s]/', "", $orig_agent_name);


            if($fresh_model == "FD"){
                //parse date
                $orig_date = $value['Date'];
                $parsed_date = Carbon::parse($orig_date)->toDateTimeString();

                //parse hours to decimal
                $orig_hours = $value['Hours'];
                $parsed_hours = $this->decimalHours($orig_hours);

                //remove special characters from notes
                $orig_notes = htmlspecialchars_decode($value['Notes']);
                $new_notes = $orig_notes;

                 //check if array has Product column
                if(array_key_exists('Product', $value)){
                    $product = $value['Product'];
                }else{
                    $product = "No Product";
                }

                $ticket_date = $ticket_id.Carbon::parse($parsed_created_at)->timestamp.$parsed_hours;
                $unique[] = $ticket_date;

                $new_time_entries = array(
                    'ticket_id'=> $ticket_id,
                    'agent' => $new_agent_name,
                    'charge_type' => $value['Billable/Non-Billable'],
                    'customer' => $value['Customer'],
                    'executed_at' => $parsed_date, 
                    'hours'=>  (float) $parsed_hours,
                    'notes'=> $new_notes,
                    'product'=> $product,
                    'subject'=> $new_subject,
                    'created_at'=> $parsed_created_at,
                    'closed_at_id'=> " ",
                    'executed_at_id'=> " ",
                    'ticket_date' => $ticket_date
                );

            } else if($fresh_model == "FS"){
                //parse date
                $orig_date = $value['Executed at'];
                $parsed_date = Carbon::parse($orig_date)->toDateTimeString();

                $ticket_date = $ticket_id.Carbon::parse($parsed_created_at)->timestamp.$value['Hours'];
                $unique[] = $ticket_date;

                //remove special characters from notes
                $orig_notes = $value['Note'];
                $new_notes = preg_replace("/[^a-zA-ZñÑ0-9-\s]/", "", $orig_notes);

                $new_time_entries = array(
                    'ticket_id'=> $ticket_id,
                    'agent' => $new_agent_name,
                    'charge_type' => $value['Billable/Non-Billable'],
                    'created_at'=> $parsed_created_at,
                    'executed_at' => $parsed_date, 
                    'hours'=>  $value['Hours'],
                    'notes'=> $new_notes,
                    'subject'=> $subject,
                    'closed_at_id'=> " ",
                    'executed_at_id'=> " ",
                    'ticket_date' => $ticket_date
                );
            }
       
            //fill up array
            $time_entries_final[] = $new_time_entries;
            $counter++;            
            
            //insert when array has reached 100 entries, until the last element
            if($counter == 100 || $value == end($csv_data)){

                $this->{$time_entry_model}->bulkDeleteByUniqueTicketDate($unique);
                $this->{$time_entry_model}->bulkInsert($time_entries_final);
                $counter = 0; //reset counter
                $time_entries_final = array(); //reset array
                $over_all_count +=100; 
                $unique = [];
            }

            // if($over_all_count == 3700) {
            //     echo "<pre>";
            //     print_r($time_entries_final);
            //     echo "<pre>";
            //     die;
            // }
        
        }

        //stored procedure for attendance_id
         $this->{$time_entry_model}->updateAllAttendanceID($orig_db_init);
        
        return back()->with('message', 'CSV File Imported Successfully');
        
    }
    
}