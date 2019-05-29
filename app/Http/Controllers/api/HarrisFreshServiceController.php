<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use \GuzzleHttp\Client as Guzzle;
use Carbon\Carbon as Carbon;
//models
use App\HarrisFSGroup as HarrisFSGroup;
use App\HarrisFSDepartment as HarrisFSDepartment;
use App\FailedTimeEntries as FailedTimeEntries;
use App\HarrisFSRequester as HarrisFSRequester;
use App\HarrisFSAgent as HarrisFSAgent;
use App\EmployeeRef as EmployeeRef;
use App\TicketExportStatus as TicketExportStatus;
use App\BPNotFoundId as BPNotFoundId;
use App\HarrisFSTicket as HarrisFSTicket;


class HarrisFreshServiceController extends Controller
{   
    public function __construct(
        Guzzle $guzzle, 
        HarrisFSGroup $harris_fs_group,
        HarrisFSDepartment $harris_fs_department,
        FailedTimeEntries $failed_time_entries,
        HarrisFSRequester $harris_fs_requester,
        HarrisFSAgent $harris_fs_agent,
        EmployeeRef $employee_ref,
        TicketExportStatus $bp_ticket_status,
        BPNotFoundId $bp_not_found,
        HarrisFSTicket $harris_fs_ticket

    )
    {  
        $this->guzzle = $guzzle;
        $this->harris_fs_group = $harris_fs_group;
        $this->harris_fs_department = $harris_fs_department;
        $this->failed_time_entries = $failed_time_entries;
        $this->harris_fs_requester = $harris_fs_requester;
        $this->harris_fs_agent = $harris_fs_agent;
        $this->employee_ref = $employee_ref;
        $this->bp_ticket_status = $bp_ticket_status;
        $this->bp_not_found = $bp_not_found;
        $this->harris_fs_ticket = $harris_fs_ticket;
       
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

            if(count($body->groups) != 0) {
                $groups = $body->groups;
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
                        "business_hours_id" => $value->business_hours_id,
                        "escalate_to" => $value->escalate_to,
                        "unassigned_for" => $value->unassigned_for,
                        "agent_ids" => json_encode($value->agent_ids),
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    $final_data[] = $group;
                }

                $this->harris_fs_group->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllDepartments() {
        $now = Carbon::now();
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");

        $link = $data["link"]. "/api/v2/departments?per_page=100";
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

            if(count($body->departments) != 0) {
                $departments = $body->departments;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($departments);
                $date_created = new Carbon();
                foreach($departments as $key => $value) {

                    $department = array(
                        "id" => $value->id,
                        "name" => $value->name,
                        "description" => $value->description,
                        "head_user_id" => $value->head_user_id,
                        "prime_user_id" => $value->prime_user_id,
                        "domains" => json_encode($value->domains),
                        "custom_fields" => json_encode($value->custom_fields),
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    $final_data[] = $department;
                }

                $this->harris_fs_department->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllRequester() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");

        $link = $data["link"]. "/api/v2/requesters?per_page=100";
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

            if(count($body->requesters) != 0) {
                $requesters = $body->requesters;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($requesters);
                $date_created = new Carbon();
                foreach($requesters as $key => $value) {

                    $requester = array(
                        "id" => $value->id,
                        "first_name" => $value->first_name,
                        "last_name" => $value->last_name,
                        "job_title" => $value->job_title,
                        "primary_email" => $value->primary_email,
                        "secondary_emails" => json_encode($value->secondary_emails),
                        "work_phone_number" => $value->work_phone_number,
                        "mobile_phone_number" => $value->mobile_phone_number,
                        "department_ids" => json_encode($value->department_ids),
                        "created_at" => $date_created,
                        "updated_at" => $date_created
                    );

                    $final_data[] = $requester;
                }
                $this->harris_fs_requester->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllAgents() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        $three_days_ago = Carbon::now()->subDays(3)->format('Y-m-d');
        $link = $data["link"]. "/api/v2/agents?per_page=50";
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
                        $final_data["account"] = "HARRISFS";
                        $final_data["created_at"] = Carbon::now()->setTimezone('Asia/Manila');
                        $final_data["updated_at"] = Carbon::now()->setTimezone('Asia/Manila');
                        $this->bp_ticket_status->insert($failed_data);
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

            if(count($body->agents) != 0) {
                $agents = $body->agents;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($agents);
                $ref_data = array();
                $now = Carbon::now();
                foreach($agents as $key => $value) {
                    $agent = array(
                        "id" => $value->id,
                        "first_name" => $value->first_name,
                        "last_name" => $value->last_name,
                        "created_at" => $now->setTimezone('Asia/Manila'),
                        "updated_at" => $now->setTimezone('Asia/Manila')

                    );

                    $ref_data = array(
                        "SYSTEM NAME" => $value->first_name." ".$value->last_name,
                        "SYSTEM ID" => $value->id
                    );
                    //update agent Id
                    $this->employee_ref->updateSystemIdByName($ref_data);

                    $final_data[] = $agent;
                }

                $this->harris_fs_agent->bulkInsert($final_data);
                return response()->json(['success'=> true], 200);
            } 
        }    
    }

    public function getAllTicketExport() {
        $client = new $this->guzzle();
        $data = Input::only("username","password","link");
        $three_month_ago = new Carbon("Last Day of September 2018");
        $three_month_ago = $three_month_ago->format("Y-m-d");

        $link = $data["link"]. "/api/v2/tickets?updated_since=".$three_month_ago."&order_type=asc&include=stats&include=stats&per_page=50";
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
           
            if(count($body->tickets) != 0) {
                $ticket_export_data = $body->tickets;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($ticket_export_data);
                $not_found = array();

                foreach($ticket_export_data as $key => $value) {
                    $now = Carbon::now();
                    $group = $this->harris_fs_group->getDataById($value->group_id);
                    $department = $this->harris_fs_department->getDataById($value->department_id);
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

                    if(count($department) == 0) {
                        $department_data["id"] = $value->department_id;
                        $department_data["entity"] = "department";
                        $department_data["ticket_id"] = $value->id;
                        $department_data["created_at"] = $now;
                        $department_data["updated_at"] = $now;
                        $not_found[] = $department_data;
                    } else {
                        $department_name = $department->name;
                    }
                    
                    $unique_id = $group_name.$value->custom_fields->process.$value->custom_fields->sub_process.$value->custom_fields->task;
                    if($value->category == "No SLA") {
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
                        "unique_id" => $unique_id,
                        "resolution_status" => $resolution_status,
                        'category' => $value->category,
                        'task' => $value->custom_fields->task,
                        'process' => $value->custom_fields->process,
                        'subprocess' => $value->custom_fields->sub_process,
                        //'bill' => $value->custom_fields->bill,
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
                        "department_id" => $value->department_id,
                        "group_id" => $value->group_id,
                        "agent_id" => $value->responder_id,
                        "type" => $value->type,
                        "due_by" => Carbon::parse($value->due_by)->setTimezone('Asia/Manila'),
                        "fr_due_by" => Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila'),
                        "is_escalated" => $value->is_escalated,
                        "channel" => $value->custom_fields->channel,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                        "deleted" => $value->deleted,
                        'attendance_id' => $attendance_id

                    );
                    $final_data[] = $ticket_export;
                }
                $this->harris_fs_ticket->bulkInsert($final_data);
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
        
        $link = $data["link"]. "/api/v2/tickets?updated_since=".$two_days_ago."&order_type=asc&include=stats&include=stats&per_page=50";
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
                        $final_data["account"] = "HarrisFS";
                        $final_data["created_at"] = Carbon::now()->setTimezone('Asia/Manila');
                        $final_data["updated_at"] = Carbon::now()->setTimezone('Asia/Manila');
                        $this->bp_ticket_status->insert($failed_data);
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

            if(count($body->tickets) != 0) {
                $ticket_export_data = $body->tickets;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($ticket_export_data);
                $not_found = array();
                $ids = array();  

                foreach($ticket_export_data as $key => $value) {
                    $now = Carbon::now();
                    $group = $this->harris_fs_group->getDataById($value->group_id);
                    $department = $this->harris_fs_department->getDataById($value->department_id);
                    $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    $group_name = ""; 
                    $department_name = "";
                    $ids[] = $value->id;
                    
                    if(empty($group)) {
                        $group_data["id"] = $value->group_id;
                        $group_data["ticket_id"] = $value->id;
                        $group_data["entity"] = "group";
                        $group_data["created_at"] = $now;
                        $group_data["updated_at"] = $now;
                        $not_found[] = $group_data;
                        
                    } else {
                        $group_name = $group->name;
                    }

                    if(empty($department)) {
                        $department_data["id"] = $value->department_id;
                        $department_data["entity"] = "department";
                        $department_data["ticket_id"] = $value->id;
                        $department_data["created_at"] = $now;
                        $department_data["updated_at"] = $now;
                        $not_found[] = $department_data;
                    } else {
                        $department_name = $department->name;
                    }
                    
                    $unique_id = $group_name.$value->custom_fields->process.$value->custom_fields->sub_process.$value->custom_fields->task;
                    if($value->category == "No SLA") {
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
                        "unique_id" => $unique_id,
                        "resolution_status" => $resolution_status,
                        'category' => $value->category,
                        'task' => $value->custom_fields->task,
                        'process' => $value->custom_fields->process,
                        'subprocess' => $value->custom_fields->sub_process,
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
                        "department_id" => $value->department_id,
                        "group_id" => $value->group_id,
                        "agent_id" => $value->responder_id,
                        "type" => $value->type,
                        "due_by" => Carbon::parse($value->due_by)->setTimezone('Asia/Manila'),
                        "fr_due_by" => Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila'),
                        "is_escalated" => $value->is_escalated,
                        "channel" => $value->custom_fields->channel,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                        "deleted" => $value->deleted,
                        'attendance_id' => $attendance_id

                    );
                    $final_data[] = $ticket_export;
                }
                $this->harris_fs_ticket->bulkDeleteByTicketExportId($ids);
                $this->harris_fs_ticket->bulkInsert($final_data);
                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }
               
            } 
        }

        $success["status"] = 200;
        $success["link"] = $data["link"];
        $success["account"] = "JC FS";
        $success["created_at"] = $now->setTimezone('Asia/Manila');
        $success["updated_at"] = $now->setTimezone('Asia/Manila');
        $this->bp_ticket_status->insert($success);
        return response()->json(['success'=> true], 200);
        
    }

    
}
