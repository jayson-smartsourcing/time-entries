<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use \GuzzleHttp\Client as Guzzle;
use Carbon\Carbon as Carbon;
use App\HarrisTimeEntries as HarrisTimeEntries;
use App\HarrisSalesTicketExport as HarrisSalesTicketExport;
use App\HarrisTimeEntriesTemp as HarrisTimeEntriesTemp;
use App\FailedTimeEntries as FailedTimeEntries;
use App\HarrisSalesTimeEntriesApi as HarrisSalesTimeEntriesApi;
use App\EmployeeRef as EmployeeRef;

class TimeEntriesController extends Controller
{   public function __construct(Guzzle $guzzle, 
                                Carbon $carbon, 
                                HarrisTimeEntries $harris_time_entries,
                                HarrisSalesTicketExport $harris_sales_ticket_export, 
                                HarrisTimeEntriesTemp $harris_time_entries_temp,
                                FailedTimeEntries $failed_time_entries,
                                HarrisSalesTimeEntriesApi $harris_sales_time_entries_api,
                                EmployeeRef $employee_ref

    )
    {  
        $this->guzzle = $guzzle;
        $this->carbon = $carbon;
        $this->harris_time_entries = $harris_time_entries;
        $this->harris_sales_ticket_export = $harris_sales_ticket_export;
        $this->harris_time_entries_temp = $harris_time_entries_temp;
        $this->failed_time_entries = $failed_time_entries;
        $this->harris_sales_time_entries_api = $harris_sales_time_entries_api;
        $this->employee_ref = $employee_ref;
    }
    
    public function getTimeEntries() {
        $client = new $this->guzzle();
        $carbon = new $this->carbon();
        $data = Input::only("username","password","link");
        $three_month_ago = Carbon::now()->subDays(90)->format("Y-m-d");

        $this->harris_time_entries_temp->truncateTable();

        $link = $data["link"]. "/api/v2/time_entries?executed_after=".$three_month_ago."&per_page=100";
        $time_entries_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                    'auth' => [$data["username"], $data["password"]]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
               for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'auth' => [$data["username"], $data["password"]]
                    ]);
                    //get status Code    
                    $status_code = $response_retry->getStatusCode(); 

                    if($status_code != 200 && $tries == 2) {
                        $failed_data["link"] = $link;
                        $failed_data["status"] = $status_code;
                        $this->failed_time_entries->addData($failed_data);
                        break 2;
                    } 

                    if($status_code == 200) {
                        $body = json_decode($response_retry->getBody());
                        break;
                    }
               }
                
            } else {
                $body = json_decode($response->getBody());
            }
            if(count($body) != 0) {
                $time_entries_data = array_merge($time_entries_data,$body);
                $x++;
            }
        }
        //sorting
        foreach ($time_entries_data as $key => $part) {
            $sort[$key] = $part->updated_at;
        }
        array_multisort($sort, SORT_DESC, $time_entries_data);
        //end

        foreach($time_entries_data as $key => $value) {

            $time_entry = array();

            $ticket_id = $value->ticket_id;
            $ticket_details = $this->harris_sales_ticket_export->getTicketData($ticket_id);

            if(count($ticket_details)) {
                
                $time_entry = array(
                        "Agent" => $ticket_details["Agent"],
                        "Billable Non-Billable" => ($value->billable == 1) ? "Billable" : "Non-Billable",
                        "Created at" =>  Carbon::parse($value->created_at),
                        "Updated at" => Carbon::parse($value->updated_at),
                        "Date" => Carbon::parse($value->executed_at),
                        "Group" => $ticket_details["Group"],
                        "Hours" => $value->time_spent,
                        "Notes" => $value->note,
                        "Priority" => $ticket_details["Priority"],
                        "Product" => $ticket_details["Product"],
                        "Status" => $ticket_details["Status"],
                        "Ticket" => $ticket_details["Ticket ID"]." - ".$ticket_details["Subject"]
                );
                $this->harris_time_entries_temp->insertTimeEntriesTemp($time_entry);
            }    
        }
        return response()->json(['success'=> true], 200);
       
    }

    public function getAllAgents() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        $link = $data["link"]. "/api/v2/agents?&per_page=100";
        $employee_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                    'auth' => [$data["username"], $data["password"]]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
               for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'auth' => [$data["username"], $data["password"]]
                    ]);
                    //get status Code    
                    $status_code = $response_retry->getStatusCode(); 

                    if($status_code != 200 && $tries == 2) {
                        $failed_data["link"] = $link;
                        $failed_data["status"] = $status_code;
                        $this->failed_time_entries->addData($failed_data);
                        break 2;
                    } 

                    if($status_code == 200) {
                        $body = json_decode($response_retry->getBody());
                        break;
                    }
               }
                
            } else {
                $body = json_decode($response->getBody());
            }
            if(count($body) != 0) {
                $employee_data = array_merge($employee_data,$body);
                $x++;
            }
        }

        foreach($employee_data as $key => $data) {
            $update_data["SYSTEM NAME"] = $data->contact->name;
            $update_data["SYSTEM ID"] = $data->id;
            $update = $this->employee_ref->updateSystemIdByName($update_data);
            echo json_encode(array("update" => $update, "data" => $update_data));
        }
        return response()->json(['success'=> true], 200);

    }

    public function getTimeEntriesApi() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        //$three_month_ago = Carbon::now()->subDays(90)->format("Y-m-d");
        $three_month_ago = new Carbon("first day of January 2019");
        $three_month_ago = $three_month_ago->format("Y-m-d");
        //$two_days_ago = Carbon::now()->subDays(2)->format("Y-m-d");

        $this->harris_sales_time_entries_api->truncateTable();
        $link = $data["link"]. "/api/v2/time_entries?executed_after=".$three_month_ago."&per_page=100";
        $time_entries_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                    'auth' => [$data["username"], $data["password"]]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
               for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'auth' => [$data["username"], $data["password"]]
                    ]);
                    //get status Code    
                    $status_code = $response_retry->getStatusCode(); 

                    if($status_code != 200 && $tries == 2) {
                        $failed_data["link"] = $link;
                        $failed_data["status"] = $status_code;
                        $this->failed_time_entries->addData($failed_data);
                        break 2;
                    } 

                    if($status_code == 200) {
                        $body = json_decode($response_retry->getBody());
                        break;
                    }
               }
                
            } else {
                $body = json_decode($response->getBody());
            }
            if(count($body) != 0) {
                $time_entries_data = array_merge($time_entries_data,$body);
                $x++;
            }
        }
        
        //sorting
        foreach ($time_entries_data as $key => $part) {
            $sort[$key] = $part->updated_at;
        }
        array_multisort($sort, SORT_ASC, $time_entries_data);
        //end
        $timestamp = Carbon::now();
        $final_data = array();
        $count = 0;
        $len = count($time_entries_data);
        foreach($time_entries_data as $key => $value) {
            $count++;
            $attendance_id = "";
            $agent_detail = $this->employee_ref->getEmployeeData($value->agent_id);
            $date_executed = Carbon::parse($value->executed_at)->format("Ymd");
            $attendance_id = $date_executed.$agent_detail["SAL EMP ID"];
            $time_entry = array(
                "attendance_id" => $attendance_id,
                "time_entry_id" => $value->id,
                "billable" => ($value->billable == 1) ? "Billable" : "Non-Billable",
                "note" => ($value->note == " ") ? NULL:$value->note,
                "timer_running" => $value->timer_running,
                "agent_id" => $value->agent_id,
                "ticket_id" => $value->ticket_id,
                "company_id" => $value->company_id,
                "time_spent" => $value->time_spent,
                "executed_at" => Carbon::parse($value->executed_at),
                "start_time" => Carbon::parse($value->start_time),
                "entry_created_at" => Carbon::parse($value->created_at),
                "entry_updated_at" => Carbon::parse($value->updated_at),
                "created_at" => $timestamp,
                "updated_at" => $timestamp

            );
            $final_data[] = $time_entry;

            if($count == 200) {
                $this->harris_sales_time_entries_api->bulkInsert($final_data);
                $final_data = array();
                $count = 0;
            }

            if($count < 200 && $key == ($len-1) ){
                $this->harris_sales_time_entries_api->bulkInsert($final_data);
            }
        }
        return response()->json(['success'=> true], 200);
    }

    public function getTimeEntriesThreeDaysAgo(){
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        $three_month_ago = Carbon::now()->subDays(60)->format("Y-m-d");
        $three_days_ago = Carbon::now()->subDays(3)->format("Y-m-d");
        
        $link = $data["link"]. "/api/v2/time_entries?executed_after=".$three_month_ago."&per_page=100";
        $time_entries_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                    'auth' => [$data["username"], $data["password"]]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
               for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'auth' => [$data["username"], $data["password"]]
                    ]);
                    //get status Code    
                    $status_code = $response_retry->getStatusCode(); 

                    if($status_code != 200 && $tries == 2) {
                        $failed_data["link"] = $link;
                        $failed_data["status"] = $status_code;
                        $this->failed_time_entries->addData($failed_data);
                        break 2;
                    } 

                    if($status_code == 200) {
                        $body = json_decode($response_retry->getBody());
                        break;
                    }
               }
                
            } else {
                $body = json_decode($response->getBody());
            }
            if(count($body) != 0) {
                $time_entries_data = array_merge($time_entries_data,$body);
                $x++;
            }
        }
        
        //sorting
        foreach ($time_entries_data as $key => $part) {
            $sort[$key] = $part->updated_at;
        }
        array_multisort($sort, SORT_ASC, $time_entries_data);
        //end
        $timestamp = Carbon::now();
        $final_data = array();
        $latest = array();
        $latest_ids = array();
        $latest_ids[] = 1; 
        $count = 0;
        $len = count($time_entries_data);
        foreach($time_entries_data as $key => $value) {
            $update = Carbon::parse($value->updated_at);

            if($update > $three_days_ago) {
                $count++;
                $attendance_id = "";
                $agent_detail = $this->employee_ref->getEmployeeData($value->agent_id);
                $date_executed = Carbon::parse($value->executed_at)->format("Ymd");
                $attendance_id = $date_executed.$agent_detail["SAL EMP ID"];
                $time_entry = array(
                    "attendance_id" => $attendance_id,
                    "time_entry_id" => $value->id,
                    "billable" => ($value->billable == 1) ? "Billable" : "Non-Billable",
                    "note" => ($value->note == " ") ? NULL:$value->note,
                    "timer_running" => $value->timer_running,
                    "agent_id" => $value->agent_id,
                    "ticket_id" => $value->ticket_id,
                    "company_id" => $value->company_id,
                    "time_spent" => $value->time_spent,
                    "executed_at" => Carbon::parse($value->executed_at),
                    "start_time" => Carbon::parse($value->start_time),
                    "entry_created_at" => Carbon::parse($value->created_at),
                    "entry_updated_at" => Carbon::parse($value->updated_at),
                    "created_at" => $timestamp,
                    "updated_at" => $timestamp

                );
                $final_data[] = $time_entry;
                $latest_ids[] = $value->id;
                $latest[] = $time_entry;
            }
            

            if($count == 200) {
                $this->harris_sales_time_entries_api->bulkDeleteByTimeEntryId($latest_ids);
                $this->harris_sales_time_entries_api->bulkInsert($final_data);
                $final_data = array();
                $count = 0;
               
            }

            if($count < 200 && $key == ($len-1) ){
                $this->harris_sales_time_entries_api->bulkDeleteByTimeEntryId($latest_ids);
                $this->harris_sales_time_entries_api->bulkInsert($final_data);
            }
        }
        return response()->json(["success"=> true,"count" => count($latest),"data" => $latest], 200);
    }

    public function test() {
        $data = Input::all();
        return response()->json(["success" => true,"data" => $data]);
    }

    

   
}
