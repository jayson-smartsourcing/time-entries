<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \GuzzleHttp\Client as Guzzle;
use Carbon\Carbon as Carbon;
//model
use App\EmployeeRef as EmployeeRef;
use App\BPNotFoundId as BPNotFoundId;
use App\FailedTimeEntries as FailedTimeEntries;
use App\CKBFDGroup as CKBFDGroup;
use App\CKBFDCompany as CKBFDCompany;
use App\CKBFDAgent as CKBFDAgent;
use App\CKBFDContact as CKBFDContact;
use App\CKBFDTicket as CKBFDTicket;
use App\CKBFDTimeEntryV3 as CKBFDTimeEntryV3;

class CKBFDController extends Controller
{
    public function __construct(
        Guzzle $guzzle,
        BPNotFoundId  $bp_not_found,
        EmployeeRef $employee_ref,
        CKBFDGroup $ckb_group,
        CKBFDCompany $ckb_company,
        CKBFDAgent $ckb_agent,
        CKBFDContact $ckb_contact,
        CKBFDTicket $ckb_ticket,
        CKBFDTimeEntryV3 $ckb_time_entry
    )
    {
        $this->guzzle = $guzzle;
        $this->bp_not_found = $bp_not_found;
        $this->employee_ref = $employee_ref;
        $this->ckb_group = $ckb_group;
        $this->ckb_company = $ckb_company;
        $this->ckb_agent = $ckb_agent;
        $this->ckb_contact = $ckb_contact;
        $this->ckb_ticket = $ckb_ticket;
        $this->ckb_time_entry = $ckb_time_entry;
    }

    public function getAllGroups() {
        $client = new $this->guzzle();
        $data = config('constants.ckb_fd');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/groups?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->ckb_group->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;

            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => $api_key
                ]
            ]);

            // get Status Code
            $status_code = $response->getStatusCode(); 

            if($status_code != 200 ) {
            for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => $api_key
                        ]
                    ]);
                    //get status Code    
                    $status_code = $response_retry->getStatusCode(); 

                    if($status_code != 200 && $tries == 2) {
                        $failed_data["link"] = $link;
                        $failed_data["status"] = $status_code;
                        $this->failed_time_entries->addData($failed_data);
                        break;
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

                $this->ckb_group->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllCompanies() {

        $client = new $this->guzzle();
        $data = config('constants.ckb_fd');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/companies?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->ckb_company->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => $api_key
                ]
            ]);
        
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
            for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => $api_key
                        ]
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

                $this->ckb_company->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllAgents(){
        $client = new $this->guzzle();
        $data = config('constants.ckb_fd');
        $api_key = $data["api_key"];

        $link = $data["link"]. "/api/v2/agents?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->ckb_agent->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => $api_key
                ]
            ]);
        
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
            for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => $api_key
                        ]
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
                // $this->employee_ref->updateSystemIdByName($ref_data);
                }

                $this->ckb_agent->bulkInsert($final_data);
            } 

        }
        $this->ckb_contact->addAgentsToContacts("ckb_fd");

        return response()->json(['success'=> true], 200); 
    }

    public function getAllContacts(){

        $client = new $this->guzzle();
        $data = config('constants.ckb_fd');
        $api_key = $data["api_key"];

        $link = $data["link"]. "/api/v2/contacts?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;
        $this->ckb_contact->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => $api_key
                ]
            ]);
        
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
            for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => $api_key
                        ]
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

                $this->ckb_contact->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200); 
    }

    public function getAllTicketsV2(){
        $client = new $this->guzzle();
        $data = config('constants.ckb_fd');
        $api_key = $data["api_key"];
        //$three_month_ago = new Carbon("2019-07-22");
        $three_month_ago = new Carbon("Last Day of September 2018");
        $three_month_ago = $three_month_ago->format("Y-m-d");

        $link = $data["link"]. "/api/v2/tickets?updated_since=".$three_month_ago."&order_type=asc&include=stats&per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;
        
        $this->ckb_ticket->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => $api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
            for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => $api_key
                        ]
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
                    $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    $group_name = ""; 
                    $department_name = ""; 

                    $group_name = html_entity_decode($group_name);
                    $process = html_entity_decode($value->custom_fields->cf_process);
                    $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                    $task = html_entity_decode($value->custom_fields->cf_task);

                    if($value->type == "No SLA") {
                        $resolution_status = "Within SLA";
                    } else {
                        if($resolved_at < $due_by) {
                            $resolution_status = "Within SLA";
                        } else {
                            $resolution_status = "SLA Violated";    
                        }
                    }

                    $first_responded_at = Carbon::parse($value->stats->first_responded_at)->setTimezone('Asia/Manila');
                    $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                    if($first_responded_at == NULL || $first_responded_at == "") {
                        $fr_resolution_status = "";
                    } else {
                        if($first_responded_at < $fr_due_by) {
                            $fr_resolution_status = "Within SLA";
                        } else {
                            $fr_resolution_status = "SLA Violated";    
                        }
                    }
                
                    $date_executed = Carbon::parse($value->created_at)->format("Ymd");

                    $ticket_export = array(
                        "id" => $value->id,
                        "hierarchy_id" => "",
                        "resolution_status" => $resolution_status,
                        'type' => $value->type,
                        'task' => $task,
                        'process' => $process,
                        'sub_process' => $sub_process,
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
                        "fr_due_by" => $fr_due_by,
                        "is_escalated" => $value->is_escalated,
                        "channel" => $value->custom_fields->cf_channel,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                        "attendance_id" => "",
                        "first_responded_at" => $first_responded_at,
                        "fr_resolution_status" => $fr_resolution_status
                    );
                    
                    $final_data[] = $ticket_export;

                    if( ($len - 1) > $key && count($final_data) == 50) {
                        $this->ckb_ticket->bulkInsert($final_data);
                        $final_data = [];
                    } 

                    if( ($len - 1) == $key) {
                        $this->ckb_ticket->bulkInsert($final_data);
                        $final_data = [];
                    }
                }
                
                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }
            }
        }

        $this->ckb_ticket->updateAllFdTickets("ckb_fd");
        return response()->json(['success'=> true], 200);
    }

    public function getLatestTicketExportV2() {
        $client = new $this->guzzle();
        $data = config('constants.ckb_fd');
        $two_days_ago = Carbon::now()->subDays(2)->format('Y-m-d');

        $link = $data["link"]. "/api/v2/tickets?updated_since=".$two_days_ago."&order_type=asc&include=stats&per_page=100";
        $api_key = $data["api_key"];
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;
            
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => $api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
            for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => $api_key
                        ]
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
                    $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    $group_name = ""; 
                    $department_name = ""; 
                    $ids[] = $value->id;
        
                    $process = html_entity_decode($value->custom_fields->cf_process);
                    $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                    $task = html_entity_decode($value->custom_fields->cf_task);

                    if($value->type == "No SLA") {
                        $resolution_status = "Within SLA";
                    } else {
                        if($resolved_at < $due_by) {
                            $resolution_status = "Within SLA";
                        } else {
                            $resolution_status = "SLA Violated";    
                        }
                    }

                    $first_responded_at = Carbon::parse($value->stats->first_responded_at)->setTimezone('Asia/Manila');
                    $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                    if($first_responded_at == NULL || $first_responded_at == "") {
                        $fr_resolution_status = "";
                    } else {
                        if($first_responded_at < $fr_due_by) {
                            $fr_resolution_status = "Within SLA";
                        } else {
                            $fr_resolution_status = "SLA Violated";    
                        }
                    }
                
                    $date_executed = Carbon::parse($value->created_at)->format("Ymd");

                    $ticket_export = array(
                        "id" => $value->id,
                        "hierarchy_id" => "",
                        "resolution_status" => $resolution_status,
                        'type' => $value->type,
                        'task' => $task,
                        'process' => $process,
                        'sub_process' => $sub_process,
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
                        "fr_due_by" => $fr_due_by,
                        "is_escalated" => $value->is_escalated,
                        "channel" => $value->custom_fields->cf_channel,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                        "attendance_id" => "",
                        "first_responded_at" => $first_responded_at,
                        "fr_resolution_status" => $fr_resolution_status
                    );
                    
                    $final_data[] = $ticket_export;

                    if( ($len - 1) > $key && count($final_data) == 50) {
                        
                        $this->ckb_ticket->bulkDeleteByTicketExportId($ids);
                        $this->ckb_ticket->bulkInsert($final_data);
                        $final_data = [];
                        $ids = [];
                    } 

                    if( ($len - 1) == $key) {
                        
                        $this->ckb_ticket->bulkDeleteByTicketExportId($ids);
                        $this->ckb_ticket->bulkInsert($final_data);
                        $final_data = [];
                        $ids = [];
                    }
                }
            
                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }

            } 

        }

        $this->ckb_ticket->updateLatestFdTickets("ckb_fd");
        return response()->json(['success'=> true], 200);
    }

    public function updateAll() {
        $lists = [
            ["function" => "getAllGroups","type" => "groups"],
            ["function" => "getAllCompanies","type" => "companies"],
            ["function" => "getAllAgents","type" => "agents"],
            ["function" => "getAllContacts","type" => "contacts"],
        ];

        foreach($lists as $val) {
            $response = $this->{$val["function"]}();
            $return = $this->loopUpdate($response, $val);
            
            if(!$return) {
                return response()->json(['success'=> false,'message' => 'error on '.$val["type"]], 200);
            }
        }
        $this->ckb_contact->addAgentsToContacts("ckb_fd");
        $this->ckb_agent->deleteDuplicates("ckb_fd_agents");
        $this->ckb_contact->deleteDuplicates("ckb_fd_contacts");
        $this->ckb_company->deleteDuplicates("ckb_fd_companies");
        $this->ckb_group->deleteDuplicates("ckb_fd_groups");
        return response()->json(['success'=> true], 200);
    }

    public function loopUpdate($response,$val) {
        $y = 3;
        $response = json_encode($response);
        $response = json_decode($response);
        
        if($response->original->success != 1){
            for($tries = 0; $tries < $y; $tries++) {

                $response = $this->{$val["function"]}();
                $response = json_encode($response);
                $response = json_decode($response);

                if($response->original->success == 1) {
                    return true;
                    break;
                }
                if($tries == 2 && $response->original->success != 1) {
                    return false;
                    break;
                }
            }  
        } else {
            return true;
        }       
    }

    public function getLatestTimeEntriesV3() {
        $client = new $this->guzzle();
        $data = config('constants.ckb_fd');
        $date_after = Carbon::parse('last day of -2 month')->setTimezone('Singapore')->format("Y-m-d");
        //$day_after_minus_1 = substr($date_after,8,2) - 1;
        //$date_after = substr($date_after,0,4)."-".substr($date_after,5,2)."-".$day_after_minus_1."T15:59:59Z";
        $date_after = $date_after."T15:59:59Z";
        $date_on_or_before = date("Y-m-d")."T".date("h:m:s")."Z";


        //$date_after = "2021-03-15T16:00:01Z"; --use date on or before plus 1 second to manually input date_after after running a manually
        //$date_before = "2021-03-15T16:00:00Z"; --use last date you want to be inserted

        //$link = $data["link"]. "/api/v2/time_entries?executed_after=".$date."&per_page=100";
        $link = $data["link"]. "/api/v2/time_entries?executed_after=".$date_after."&executed_before=".$date_on_or_before."&per_page=100";

        $api_key = $data["api_key"];
        $time_entry_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {
            $link .= "&page=".$i;

            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => $api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();

            if($status_code != 200 ) {
            for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => $api_key
                        ]
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
                $time_entry_data = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($time_entry_data);
                $not_found = array();
                $ids = array();

                foreach($time_entry_data as $key => $value) {
                    $now = Carbon::now();
                    $ids[] = $value->id;

                    $time_entry = array(
                        "billable" => $value->billable,
                        "note" => $value->note,
                        "id" => $value->id,
                        "timer_running" => $value->timer_running,
                        "ticket_id" => $value->ticket_id,
                        "agent_id" => $value->agent_id,
                        "time_spent" => $value->time_spent,
                        "executed_at" => Carbon::parse($value->executed_at)->setTimezone('Singapore'),
                        "start_time" => Carbon::parse($value->start_time)->setTimezone('Singapore'),
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Singapore'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Singapore'),
                        "is_latest" => '1',
                    );

                    $final_data[] = $time_entry;

                    if( ($len - 1) > $key && count($final_data) == 50) {

                        $this->ckb_time_entry->bulkDeleteById($ids);
                        $this->ckb_time_entry->bulkInsert($final_data);
                        $final_data = [];
                        $ids = [];
                    }

                    if( ($len - 1) == $key) {

                        $this->ckb_time_entry->bulkDeleteById($ids);
                        $this->ckb_time_entry->bulkInsert($final_data);
                        $final_data = [];
                        $ids = [];
                    }
                }

                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }

            }

        }
        //$day_after_plus_1 = substr($date_after,8,2) + 1;
        //$date_after = Carbon::parse(substr($date_after,0,8).$day_after_plus_1."T15:59:59Z")->setTimezone('Singapore')->format("Y-m-d");
        $date_after = Carbon::parse('first day of -1 month')->setTimezone('Singapore')->format("Y-m-d");
        $date_on_or_before = Carbon::parse($date_on_or_before)->setTimezone('Singapore')->format("Y-m-d");
        //echo($date_after);
        //echo($date_on_or_before);
        //die;
        $this->ckb_time_entry->bulkDeletePreviousMonth($date_after,$date_on_or_before);
        $this->ckb_time_entry->bulkUpdateByNewInsert();
        return response()->json(['success'=> true], 200);
    }
}
