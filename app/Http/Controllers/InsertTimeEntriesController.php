<?php

namespace App\Http\Controllers;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Support\Arr;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon as Carbon;
use \GuzzleHttp\Client as Guzzle;


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
use App\EstoreFDTimeEntry as EstoreFDTimeEntry;
use App\UrbanAnglesFDTimeEntry as UrbanAnglesFDTimeEntry;
use App\WhiteLabelFDTimeEntry as WhiteLabelFDTimeEntry;
use App\TicketMonitoring as TicketMonitoring; //ticket monitoring model
use App\CKBFDTimeEntry as CKBFDTimeEntry;
use App\TrendTileFDTimeEntry as TrendTileFDTimeEntry;
use App\CWFDTimeEntry as CWFDTimeEntry;
use App\Mint360FDTimeEntry as Mint360FDTimeEntry;
use App\CCFDTimeEntry as CCFDTimeEntry;
use App\StoneandTileFDTimeEntry as StoneandTileFDTimeEntry;
use App\BellePropertyFDTimeEntry as BellePropertyFDTimeEntry;
use App\BeerCartelFDTimeEntry as BeerCartelFDTimeEntry;
use App\BlueRockFDTimeEntry as BlueRockFDTimeEntry;
use App\UptickFDTimeEntry as UptickFDTimeEntry;
use App\PlexusFDTimeEntry as PlexusFDTimeEntry;
use App\WBFDTimeEntry as WBFDTimeEntry;

class InsertTimeEntriesController extends Controller
{
    public function __construct(
        Guzzle $guzzle,
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
        BPTimeEntry $bp_fs_time_entries,
        EstoreFDTimeEntry $estore_fd_time_entries,
        UrbanAnglesFDTimeEntry $urban_angles_fd_time_entries,
        WhiteLabelFDTimeEntry $white_label_fd_time_entries,
        TicketMonitoring $ticket_monitoring,
        CKBFDTimeEntry $ckb_fd_time_entries,
        TrendTileFDTimeEntry $trendtile_fd_time_entries,
        CWFDTimeEntry $cw_fd_time_entries,
        Mint360FDTimeEntry $mint360_fd_time_entries,
        CCFDTimeEntry $cc_fd_time_entries,
        StoneandTileFDTimeEntry $stone_and_tile_fd_time_entries,
        BellePropertyFDTimeEntry $belle_property_fd_time_entries,
        BeerCartelFDTimeEntry $beer_cartel_fd_time_entries,
        BlueRockFDTimeEntry $blue_rock_fd_time_entries,
        UptickFDTimeEntry $uptick_fd_time_entries,
        PlexusFDTimeEntry $plexus_fd_time_entries,
        WBFDTimeEntry $wb_fd_time_entries
    )
    {  
        $this->guzzle = $guzzle;
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
        $this->estore_fd_time_entries = $estore_fd_time_entries;
        $this->urban_angles_fd_time_entries = $urban_angles_fd_time_entries;
        $this->white_label_fd_time_entries = $white_label_fd_time_entries;
        $this->ticket_monitoring = $ticket_monitoring;
        $this->ckb_fd_time_entries = $ckb_fd_time_entries;
        $this->trendtile_fd_time_entries = $trendtile_fd_time_entries;
        $this->cw_fd_time_entries = $cw_fd_time_entries;
        $this->mint360_fd_time_entries = $mint360_fd_time_entries;
        $this->cc_fd_time_entries = $cc_fd_time_entries;
        $this->stone_and_tile_fd_time_entries = $stone_and_tile_fd_time_entries;
        $this->belle_property_fd_time_entries = $belle_property_fd_time_entries;
        $this->beer_cartel_fd_time_entries = $beer_cartel_fd_time_entries;
        $this->blue_rock_fd_time_entries = $blue_rock_fd_time_entries;
        $this->uptick_fd_time_entries = $uptick_fd_time_entries;
        $this->plexus_fd_time_entries = $plexus_fd_time_entries;
        $this->wb_fd_time_entries = $wb_fd_time_entries;
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

         //for ticket monitoring
         $monitoring_array = array();
         $account_name = "";
 
         //loop through constants 
         foreach($constant_data as $key => $value){
             $const_api_key = Arr::get($value, 'api_key');
           
             //Check if API Key exists
             if($const_api_key == $input_api_key){
                 $api_key_account = $const_api_key;
                 $db_init = Arr::get($value, 'db_init'); //initials of db table name
                 $orig_db_init = $db_init;
                 $is_sp_update = Arr::get($value, 'sp_update');

                 $link = Arr::get($value, 'link');

                 $account_name = $key;
                 
                 //fill up ticket monitoring array
                 $execution = array(
                     'account_name' => $account_name,
                     'execution_type' => "Time Sheet Submission",
                     'created_at' => Carbon::now()->setTimezone('Singapore')
                 );

                //  print_r($execution);
                //  die;
 
                 $monitoring_array = $execution;

                 //check if API key is for freshdesk or freshservice.
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
                    return back()->withErrors('API Key is incorrect')->with(['name' => 'time_entries']);
                 }
             }
         }
        

        //concatenate db_init + _time_entries to get time entry model 
        $db_init .= "_time_entries";
        $time_entry_model = $db_init;     

        //for stored proc udpate update_[account]_[fd/fs]_time_entries_v2
        $sp = 'update_';
        $sp .= $time_entry_model;
        $sp .= '_v2';
  
        //map csv file 
        $csv_data = $fields = array(); $i = 0;
        $handle = fopen($request->file('csv_file'), "r");
       
        if ($handle) {
            //while (($row = fgetcsv($handle, 4096)) !== false) {
            while (($row = fgetcsv($handle, 4096,",",'"','"')) !== false) {    
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
            usort($csv_data, function($a, $b) {
                return ($a['Executed at'] > $b['Executed at']) ? -1 : 1;
            });

            $length = count($csv_data);        
            $last_entry = $csv_data[$length - 1];
            $first_entry = $csv_data[0];
            
            //get start date and end date on csv file
            $start_date = Carbon::parse($last_entry["Executed at"])->subSeconds(1)->format("Y-m-d H:m:s");
            $end_date = Carbon::parse($first_entry["Executed at"])->addSeconds(1)->format("Y-m-d H:m:s");

        } else {
            $row = reset($csv_data); 
            $date = Carbon::parse($row['Date']); 
            usort($csv_data, function($a, $b) {
                return ($a['Date'] > $b['Date']) ? -1 : 1;
            });
            $length = count($csv_data);        
            $last_entry = $csv_data[$length - 1];
            $first_entry = $csv_data[0];

            //get start date and end date on csv file
            $start_date = Carbon::parse($last_entry["Date"])->subSeconds(1)->format("Y-m-d H:m:s");
            $end_date = Carbon::parse($first_entry["Date"])->addSeconds(1)->format("Y-m-d H:m:s");

        }

        //delete date within date range
        $this->{$time_entry_model}->bulkDeleteByLimitDate($start_date,$end_date);

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
                //$orig_notes = $value['Note'];
                //$new_notes = preg_replace("/[^a-zA-ZñÑ0-9-\s]/", "", $orig_notes);

                $orig_notes = htmlspecialchars_decode($value['Note']);
                $new_notes = $orig_notes;

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
                // $this->{$time_entry_model}->bulkDeleteByUniqueTicketDate($unique);
                $this->{$time_entry_model}->bulkInsert($time_entries_final);
                $counter = 0; //reset counter
                $time_entries_final = array(); //reset array
                $over_all_count +=100; 
                $unique = [];
            }
        
        }

        //insert to ticket monitoring
        $this->ticket_monitoring->insert($monitoring_array);

        //stored procedure for attendance_id
         $this->{$time_entry_model}->updateAllAttendanceID($orig_db_init);

        //  check if account is old, run sp
         if($is_sp_update == 1){
            $this->{$time_entry_model}->updateTimeEntries($sp);
         }
       
        return back()->with('message', 'CSV File Imported Successfully')->with(['name' => 'time_entries']);
        
    }

    //function for ticket export - accepts api key only
    public function refreshTicketExport($token){

         //Concatenate API Key input with ':X'
         $base_key = $token;
         $base_key .= ":X"; 
         $input_api_key = base64_encode($base_key);
 
         //Get data from constants.php
         $constant_data = config('constants');
        
         //Counter for each Account
         $acc_counter = count($constant_data);
         $end_of_array = 0;
 
         $token = $input_api_key;

         //for ticket monitoring 
         $account_name = "";
         $monitoring_array = array();

         //loop through constants 
         foreach($constant_data as $key => $value){
             $const_api_key = Arr::get($value, 'api_key');
                       
             //Check if API Key exists
             if($const_api_key == $input_api_key){
                 $api_key_account = $const_api_key; 
                 $ticket_link = Arr::get($value, 'ticket_link');
                 $url =  url('/');
                 $url .=$ticket_link;  

                 $account_name = $key;

                 //fill up ticket monitoring array
                 $execution = array(
                    'account_name' => $account_name,
                    'execution_type' => "Ticket Refresh",
                    'created_at' => Carbon::now()->setTimezone('Singapore')
                );

                $monitoring_array = $execution;
             }
             else{
                 //Check if loop is at the end of the Array
                 $end_of_array++;
                 if($end_of_array == $acc_counter){
                    return response()->json(['error'=> true, 'message'=>"Incorrect API Key"], 200);
                 }
             }
         }

         $this->ticket_monitoring->insert($monitoring_array);

         return response()->json(['success'=> true, 'link'=>$url], 200);
    }
    
}




