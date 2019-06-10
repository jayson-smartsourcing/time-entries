<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use \GuzzleHttp\Client as Guzzle;
use Carbon\Carbon as Carbon;
//model
use App\EmployeeRef as EmployeeRef;
use App\BPNotFoundId as BPNotFoundId;
use App\FailedTimeEntries as FailedTimeEntries;
use App\HarrisSalesFDGroup as HarrisSalesFDGroup;
use App\HarrisSalesFDCompanies as HarrisSalesFDCompanies;
use App\HarrisSalesFDAgent as HarrisSalesFDAgent;
use App\HarrisSalesFDContact as HarrisSalesFDContact;
use App\HarrisSalesFDTicket as HarrisSalesFDTicket;
use App\HarrisSalesFDTimeEntry as HarrisSalesFDTimeEntry;
 

class HarrisSalesController extends Controller
{
    public function __construct(
        Guzzle $guzzle,
        HarrisSalesFDGroup $hs_fd_group,
        HarrisSalesFDCompanies $hs_fd_company,
        HarrisSalesFDAgent $hs_fd_agent,
        HarrisSalesFDContact $hs_fd_contact,
        HarrisSalesFDTicket $hs_fd_ticket,
        BPNotFoundId  $bp_not_found,
        EmployeeRef $employee_ref,
        FailedTimeEntries $failed_time_entries,
        HarrisSalesFDTimeEntry $hs_fd_time_entries
    )
    {  
        $this->guzzle = $guzzle;
        $this->hs_fd_group = $hs_fd_group;
        $this->hs_fd_company = $hs_fd_company;
        $this->hs_fd_agent = $hs_fd_agent;
        $this->hs_fd_contact = $hs_fd_contact;
        $this->hs_fd_ticket = $hs_fd_ticket;
        $this->bp_not_found = $bp_not_found;
        $this->employee_ref = $employee_ref;
        $this->failed_time_entries = $failed_time_entries;
        $this->hs_fd_time_entries = $hs_fd_time_entries;
    }  
    
    
    public function getAllGroups() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");

        $link = $data["link"]. "/api/v2/groups?per_page=100";
        $ticket_export_data = array();
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
                $groups = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($groups);
                $date_created = new Carbon();
                foreach($groups as $key => $value) {
                    $group = array(
                        "id" => $value->id,
                        "name" => $value->name,
                        "description" => $value->description,
                        "business_hours_id" => $value->business_hour_id,
                        "escalate_to" => $value->escalate_to,
                        "unassigned_for" => $value->unassigned_for,
                        "agent_ids" => null,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    $final_data[] = $group;
                }

                $this->hs_fd_group->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllCompanies() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");

        $link = $data["link"]. "/api/v2/companies?per_page=100";
        $ticket_export_data = array();
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
                $companies = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($companies);
                $date_created = new Carbon(); 
                foreach($companies as $key => $value) {
                    
                    $company = array(
                        "id" => $value->id,
                        "name" => $value->name,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    $final_data[] = $company;
                }

                $this->hs_fd_company->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllAgents(){
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");

        $link = $data["link"]. "/api/v2/agents?per_page=100";
        $ticket_export_data = array();
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
                $agents = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($agents);
                $date_created = new Carbon(); 
                foreach($agents as $key => $value) {
                    $agent = array(
                        "id" => $value->id,
                        "name" => $value->contact->name,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now()
                    );

                    $final_data[] = $agent;

                    $ref_data = array(
                        "SYSTEM NAME" => $value->contact->name,
                        "SYSTEM ID" => $value->id
                    );
                    //update agent Id
                    $this->employee_ref->updateSystemIdByName($ref_data);
                }

                $this->hs_fd_agent->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200); 
    }

    public function getAllContacts(){
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");

        $link = $data["link"]. "/api/v2/contacts?per_page=100";
        $ticket_export_data = array();
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
                $agents = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($agents);
                $date_created = new Carbon(); 
                foreach($agents as $key => $value) {
                    
                    $agent = array(
                        "id" => $value->id,
                        "name" => $value->name,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    $final_data[] = $agent;
                }

                $this->hs_fd_contact->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200); 
    }

    public function getAllTickets(){
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        $three_month_ago = new Carbon("Last Day of September 2018");
        $three_month_ago = $three_month_ago->format("Y-m-d");

        $link = $data["link"]. "/api/v2/tickets?updated_since=".$three_month_ago."&order_type=asc&include=stats&per_page=100";
        $ticket_export_data = array();
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
                $ticket_export_data = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($ticket_export_data);
                $not_found = array();

                foreach($ticket_export_data as $key => $value) {
                    $now = Carbon::now();
                    $group = $this->hs_fd_group->getDataById($value->group_id);
                    $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    $group_name = ""; 
                    $department_name = ""; 
                    
                    if(count($group) == 0) {
                        $group_data["id"] = $value->group_id;
                        $group_data["ticket_id"] = $value->id;
                        $group_data["entity"] = "group";
                        $group_data["created_at"] = $now;
                        $group_data["updated_at"] = $now;
                        $not_found[] = $group_data;
                        
                    } else {
                        $group_name = $group->name;
                    }

                    $group_name = html_entity_decode($group_name);
                    $process = html_entity_decode($value->custom_fields->cf_process);
                    $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                    $task = html_entity_decode($value->custom_fields->cf_task);
                    
                    $hierarchy_id = $group_name.$process.$sub_process.$task;
                    if($value->type == "No SLA") {
                        $resolution_status = "Within SLA";
                    } else {
                        if($resolved_at < $due_by) {
                            $resolution_status = "Within SLA";
                        } else {
                            $resolution_status = "SLA Violated";    
                        }
                    }
                

                    $agent_detail = $this->employee_ref->getEmployeeData($value->responder_id);
                    $date_executed = Carbon::parse($value->created_at)->format("Ymd");
                    $attendance_id = $date_executed.$agent_detail["SAL EMP ID"];

                    $ticket_export = array(
                        "id" => $value->id,
                        "hierarchy_id" => $hierarchy_id,
                        "resolution_status" => $resolution_status,
                        'type' => $value->type,
                        'task' => $task,
                        'process' => $process,
                        'subprocess' => $sub_process,
                        'resolved_at' => Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila'),
                        'closed_at' => Carbon::parse($value->stats->closed_at)->setTimezone('Asia/Manila'),
                        "cc_emails" => json_encode($value->cc_emails),
                        "fwd_emails" => json_encode($value->fwd_emails),
                        "reply_cc_emails" => json_encode($value->reply_cc_emails),
                        "fr_escalated" => $value->fr_escalated,
                        "spam" => $value->spam,
                        "priority" => $value->priority,
                        "requester_id" => $value->requester_id,
                        "source" => $value->source,
                        "status" => $value->status,
                        "subject" => $value->subject,
                        "to_emails" => json_encode($value->to_emails),
                        "company_id" => $value->company_id,
                        "group_id" => $value->group_id,
                        "agent_id" => $value->responder_id,
                        "due_by" => Carbon::parse($value->due_by)->setTimezone('Asia/Manila'),
                        "fr_due_by" => Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila'),
                        "is_escalated" => $value->is_escalated,
                        "channel" => $value->custom_fields->cf_channel,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                        'attendance_id' => $attendance_id

                    );
                    
                    $final_data[] = $ticket_export;
                }
                $this->hs_fd_ticket->bulkInsert($final_data);
                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }
               
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }    

    public function getLatestTicketExport() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        $two_days_ago = Carbon::now()->subDays(3)->format('Y-m-d');

        $link = $data["link"]. "/api/v2/tickets?updated_since=".$two_days_ago."&order_type=asc&include=stats&per_page=50";
        $ticket_export_data = array();
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
                $ticket_export_data = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($ticket_export_data);
                $not_found = array();
                $ids = array();  

                foreach($ticket_export_data as $key => $value) {
                    $now = Carbon::now();
                    $group = $this->hs_fd_group->getDataById($value->group_id);
                    $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    $group_name = ""; 
                    $department_name = ""; 
                    $ids[] = $value->id;
                    
                    if(count($group) == 0) {
                        $group_data["id"] = $value->group_id;
                        $group_data["ticket_id"] = $value->id;
                        $group_data["entity"] = "group";
                        $group_data["created_at"] = $now;
                        $group_data["updated_at"] = $now;
                        $not_found[] = $group_data;
                        
                    } else {
                        $group_name = $group->name;
                    }
                    $group_name = html_entity_decode($group_name);
                    $process = html_entity_decode($value->custom_fields->cf_process);
                    $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                    $task = html_entity_decode($value->custom_fields->cf_task);
                    
                    $hierarchy_id = $group_name.$process.$sub_process.$task;
                    if($value->type == "No SLA") {
                        $resolution_status = "Within SLA";
                    } else {
                        if($resolved_at < $due_by) {
                            $resolution_status = "Within SLA";
                        } else {
                            $resolution_status = "SLA Violated";    
                        }
                    }
                

                    $agent_detail = $this->employee_ref->getEmployeeData($value->responder_id);
                    $date_executed = Carbon::parse($value->created_at)->format("Ymd");
                    $attendance_id = $date_executed.$agent_detail["SAL EMP ID"];

                    $ticket_export = array(
                        "id" => $value->id,
                        "hierarchy_id" => $hierarchy_id,
                        "resolution_status" => $resolution_status,
                        'type' => $value->type,
                        'task' => $task,
                        'process' => $process,
                        'subprocess' => $sub_process,
                        'resolved_at' => Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila'),
                        'closed_at' => Carbon::parse($value->stats->closed_at)->setTimezone('Asia/Manila'),
                        "cc_emails" => json_encode($value->cc_emails),
                        "fwd_emails" => json_encode($value->fwd_emails),
                        "reply_cc_emails" => json_encode($value->reply_cc_emails),
                        "fr_escalated" => $value->fr_escalated,
                        "spam" => $value->spam,
                        "priority" => $value->priority,
                        "requester_id" => $value->requester_id,
                        "source" => $value->source,
                        "status" => $value->status,
                        "subject" => $value->subject,
                        "to_emails" => json_encode($value->to_emails),
                        "company_id" => $value->company_id,
                        "group_id" => $value->group_id,
                        "agent_id" => $value->responder_id,
                        "due_by" => Carbon::parse($value->due_by)->setTimezone('Asia/Manila'),
                        "fr_due_by" => Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila'),
                        "is_escalated" => $value->is_escalated,
                        "channel" => $value->custom_fields->cf_channel,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                        'attendance_id' => $attendance_id

                    );
                    
                    $final_data[] = $ticket_export;
                }
                $this->hs_fd_ticket->bulkDeleteByTicketExportId($ids);
                $this->hs_fd_ticket->bulkInsert($final_data);
                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }
               
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllTimeEntries() {

        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        //$three_month_ago = Carbon::now()->subDays(90)->format("Y-m-d");
        $three_month_ago = new Carbon("first day of January 2019");
        $three_month_ago = $three_month_ago->format("Y-m-d");
        //$two_days_ago = Carbon::now()->subDays(2)->format("Y-m-d");

        $this->hs_fd_time_entries->truncateTable();
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
                "id" => $value->id,
                "attendance_id" => $attendance_id,
                "billable" => ($value->billable == 1) ? "Billable" : "Non-Billable",
                "note" => ($value->note == " ") ? NULL:$value->note,
                "timer_running" => $value->timer_running,
                "agent_id" => $value->agent_id,
                "ticket_id" => $value->ticket_id,
                "company_id" => $value->company_id,
                "time_spent" => $value->time_spent,
                "executed_at" => Carbon::parse($value->executed_at)->setTimezone('Asia/Manila'),
                "start_time" => Carbon::parse($value->start_time)->setTimezone('Asia/Manila'),
                "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),

            );
            $final_data[] = $time_entry;

            if($count == 200) {
                $this->hs_fd_time_entries->bulkInsert($final_data);
                $final_data = array();
                $count = 0;
            }

            if($count < 200 && $key == ($len-1) ){
                $this->hs_fd_time_entries->bulkInsert($final_data);
            }
        }
        return response()->json(['success'=> true], 200);
    }

    public function getTimeEntriesThreeDaysAgo(){
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        $three_month_ago = Carbon::now()->subDays(60)->format("Y-m-d");
        $three_days_ago = Carbon::now()->subDays(2)->format("Y-m-d");
        
        $link = $data["link"]. "/api/v2/time_entries?executed_after=".$three_month_ago."&per_page=50";
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
                    "id" => $value->id,
                    "attendance_id" => $attendance_id,
                    "billable" => ($value->billable == 1) ? "Billable" : "Non-Billable",
                    "note" => ($value->note == " ") ? NULL:$value->note,
                    "timer_running" => $value->timer_running,
                    "agent_id" => $value->agent_id,
                    "ticket_id" => $value->ticket_id,
                    "company_id" => $value->company_id,
                    "time_spent" => $value->time_spent,
                    "executed_at" => Carbon::parse($value->executed_at)->setTimezone('Asia/Manila'),
                    "start_time" => Carbon::parse($value->start_time)->setTimezone('Asia/Manila'),
                    "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                    "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),

                );
                $final_data[] = $time_entry;
                $latest_ids[] = $value->id;
                $latest[] = $time_entry;
            }
            

            if($count == 50) {
                $this->hs_fd_time_entries->bulkDeleteByTimeEntryId($latest_ids);
                $this->hs_fd_time_entries->bulkInsert($final_data);
                $final_data = array();
                $count = 0;
               
            }

            if($count < 50 && $key == ($len-1) ){
                $this->hs_fd_time_entries->bulkDeleteByTimeEntryId($latest_ids);
                $this->hs_fd_time_entries->bulkInsert($final_data);
            }
        }
        return response()->json(["success"=> true,"count" => count($latest),"data" => $latest], 200);
    }
}
