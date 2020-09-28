<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use \GuzzleHttp\Client as Guzzle;
use Carbon\Carbon as Carbon;
//model
use App\SSZDTicketMetric as SSZDTicketMetric;
use App\SSZDTicket as SSZDTicket;
use App\SSZDUser as SSZDUser;
use App\SSZDGroup as SSZDGroup;
use App\SSZDOrganization as SSZDOrganization;
use App\SSZDTicketMetricEvent as SSZDTicketMetricEvent;

class SSZDController extends Controller
{
    public function __construct(
        Guzzle $guzzle,
        SSZDTicketMetric $ss_zd_ticket_metric,
        SSZDTicket $ss_zd_ticket,
        SSZDUser $ss_zd_user,
        SSZDGroup $ss_zd_group,
        SSZDOrganization $ss_zd_organization,
        SSZDTicketMetricEvent $ss_zd_ticket_metric_event
    )
    {  
        $this->guzzle = $guzzle;
        $this->ss_zd_ticket_metric = $ss_zd_ticket_metric;
        $this->ss_zd_ticket = $ss_zd_ticket;
        $this->ss_zd_user = $ss_zd_user;
        $this->ss_zd_group = $ss_zd_group;
        $this->ss_zd_organization = $ss_zd_organization;
        $this->ss_zd_ticket_metric_event = $ss_zd_ticket_metric_event;
    }

    public function getLatestTickets() {
        $client = new $this->guzzle();
        $data = config('constants.ss_zd');
        $two_days_ago = Carbon::today()->subDays(100)->timestamp; //should be subDays(2)

        //$link = $data["link"]. "/api/v2/tickets.json?sort_by=updated_at&sort_order=desc&per_page=100"; //should be desc
        $api_key = $data["api_key"];
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {
            $link = $data["link"]. "/api/v2/tickets.json?sort_by=updated_at&sort_order=desc&per_page=100"; //should be desc
            $link .= "&page=".$i;
            
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => "Basic ".$api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
               for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => "Basic ".$api_key
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
                        $body = json_decode($response_retry->getBody(), true);
                        break;
                    }
               }
                
            } else {
                $body = json_decode($response->getBody(), true);
            }
           
            if(count($body['tickets']) != 0) {
                $ticket_export_data = $body['tickets'];
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($ticket_export_data);
                $not_found = array();
                $ids = array();  

                foreach($ticket_export_data as $key => $value) {

                    $now = Carbon::now();
                    // $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    // $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    // $group_name = ""; 
                    // $department_name = ""; 
                    // print_r($value["custom_fields"][0]["id"]);
                    // die;
                    $updated_at = Carbon::parse($value["updated_at"])->timestamp;
                    if($updated_at >= $two_days_ago) {
                        // echo($updated_at."<br>");
                        // echo($two_days_ago."<br>");
                        // echo("getTicketMetric");
                        // die;
                        //$this->getTicketMetric($value["id"]); takes too long, have to do collate all latest ticket_id 's first

                        $ids[] = $value["id"];

                        foreach($value["custom_fields"] as $key => $custom_field) {

                            switch ($custom_field["id"]) {
                                case "360001334796":
                                $sub_process = $custom_field["value"];
                                break;
                                case "360001365775":
                                $task = $custom_field["value"];
                                break;
                                case "360001392415":
                                $total_time_spent_sec = $custom_field["value"];
                                break;
                                case "360001334816":
                                $channel = $custom_field["value"];
                                break;
                                case "360001392435":
                                $time_spent_last_update_sec = $custom_field["value"];
                                break;     
                                case "360001334836":
                                $turnaround_time = $custom_field["value"];
                                break;        
                                case "360001392455":
                                $task_count = $custom_field["value"];
                                break;   
                                case "360001365755":
                                $process = $custom_field["value"];
                                break;                                 
                                default:
                                $unknown_value = $custom_field["value"];
                            }
                        }

                        //print_r($unknown_value);
                        // $process = html_entity_decode($value->custom_fields->cf_process);
                        // $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                        // $task = html_entity_decode($value->custom_fields->cf_task);

                        // if($value->type == "No SLA") {
                        //     $resolution_status = "Within SLA";
                        // } else {
                        //     if($resolved_at < $due_by) {
                        //         $resolution_status = "Within SLA";
                        //     } else {
                        //         $resolution_status = "SLA Violated";    
                        //     }
                        // }
    
                        // $first_responded_at = Carbon::parse($value->stats->first_responded_at)->setTimezone('Asia/Manila');
                        // $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                        // if($first_responded_at == NULL || $first_responded_at == "") {
                        //     $fr_resolution_status = "";
                        // } else {
                        //     if($first_responded_at < $fr_due_by) {
                        //         $fr_resolution_status = "Within SLA";
                        //     } else {
                        //         $fr_resolution_status = "SLA Violated";    
                        //     }
                        // }
                    
                        //$date_executed = Carbon::parse($value[->created_at])->format("Ymd");
                        //print_r($value["id"]);
                        if(count($value["via"]["source"]["from"]) < 1) {
                            //echo("SOURCE FROM LESS THAN 1!");
                            $source_from_address = NULL;
                            $source_from_name = NULL;
                            //print_r($value["id"]);
                        }
                        else{
                            $source_from_address = $value["via"]["source"]["from"]["address"];
                            $source_from_name = $value["via"]["source"]["from"]["name"];
                        }

                        if(count($value["via"]["source"]["to"]) < 1) {
                            //echo("SOURCE TO LESS THAN 1");
                            $source_to_address = NULL;
                            $source_to_name = NULL;
                            //print_r($value["id"]);
                        }
                        else{
                            $source_to_address = $value["via"]["source"]["to"]["address"];
                            $source_to_name = $value["via"]["source"]["to"]["name"];
                            //print_r($value["id"]);
                        }

                        if($value["satisfaction_rating"]["score"] === "offered" || $value["satisfaction_rating"]["score"] === "unoffered") {
                            $satisfaction_rating_id = NULL;
                            $satisfaction_rating_comment = NULL;
                            $satisfaction_rating_reason = NULL;
                            $satisfaction_rating_reason_id = NULL;
                        }
                        else{
                            $satisfaction_rating_id = $value["satisfaction_rating"]["id"];
                            $satisfaction_rating_comment = $value["satisfaction_rating"]["comment"];
                            $satisfaction_rating_reason = $value["satisfaction_rating"]["reason"];
                            $satisfaction_rating_reason_id = $value["satisfaction_rating"]["reason_id"];
                        }



                        $ticket_export = array(
                            'url' => $value["url"],
                            'id' => $value["id"],
                            'external_id' => $value["external_id"],
                            'channel_zendesk' => $value["via"]["channel"],
                            'source_from_address' => $source_from_address,
                            'source_from_name'=> $source_from_name,
                            'source_to_address'=> $source_to_address,
                            'source_to_name' => $source_to_name,
                            'source_rel'=> $value["via"]["source"]["rel"],
                            'created_at' => $value["created_at"],
                            'updated_at' => $value["updated_at"],
                            'type' => $value["type"],
                            'subject' => $value["subject"],
                            'raw_subject' => $value["raw_subject"],
                            'description' => $value["description"],
                            'priority' => $value["priority"],
                            'status' => $value["status"],
                            'recipient' => $value["status"],
                            'requester_id' => $value["requester_id"],
                            'submitter_id' => $value["submitter_id"],
                            'assignee_id' => $value["assignee_id"],
                            'organization_id' => $value["organization_id"],
                            'group_id' => $value["group_id"],
                            'collaborator_ids' => json_encode($value["collaborator_ids"]),
                            //'follower_ids' => json_encode($value["follower_ids"]),
                            'email_cc_ids' => json_encode($value["email_cc_ids"]),
                            //'forum_topic_id' => $value["forum_topic_id"],
                            'problem_id' => $value["problem_id"],
                            'has_incidents' => $value["has_incidents"],
                            //'is_public' => $value["is_public"],
                            'due_at' => $value["due_at"],
                            //'tags' => json_encode($value["tags"]),
                            'sub_process' => $sub_process,
                            'task'=> $task,
                            'total_time_spent_sec' => $total_time_spent_sec,
                            'channel' => $channel,
                            'time_spent_last_update_sec' => $time_spent_last_update_sec,
                            'turnaround_time' => $turnaround_time,
                            'task_count' => $task_count,
                            'process' => $process,
                            'satisfaction_rating_score' => $value["satisfaction_rating"]["score"],
                            'satisfaction_rating_id' => $satisfaction_rating_id,
                            'satisfaction_rating_comment' => $satisfaction_rating_comment,
                            'satisfaction_rating_reason' => $satisfaction_rating_reason,
                            'satisfaction_rating_reason_id' => $satisfaction_rating_reason_id

                        );
                        
                        $final_data[] = $ticket_export;

                        if( ($len - 1) > $key && count($final_data) == 50) {    

                            $this->ss_zd_ticket->bulkDeleteById($ids);
                            $this->ss_zd_ticket->bulkInsert($final_data);
                            $this->getTicketMetric($ids);
                            $final_data = [];
                            $ids = [];
                        } 

                        if( ($len - 1) == $key) {

                            $this->ss_zd_ticket->bulkDeleteById($ids);
                            $this->ss_zd_ticket->bulkInsert($final_data);
                            $this->getTicketMetric($ids);            
                            $final_data = [];
                            $ids = [];
                        }
                    }
                    else{
                    //echo("else");    
                    //print_r($final_data[]);
                    //die;
                    break 2;
                    
                    }
                }

                //echo("end of foreach");    
                //print_r($final_data[]);
                //die;
               
                // if(count($not_found) > 0) {
                //     $this->bp_not_found->bulkInsert($not_found);
                // }
               
            } 


        }

        //$this->tagflix_fd_ticket->updateLatestFdTickets("tagflix_fd");
        return response()->json(['success'=> true], 200);
    }

    public function getTicketMetric($ticket_id){
        $client = new $this->guzzle();
        $data = config('constants.ss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();
        ////$three_month_ago = new Carbon("2019-07-22");
        //$three_month_ago = new Carbon("Last Day of September 2018");
        //$three_month_ago = $three_month_ago->format("Y-m-d");

        //$link = $data["link"]. "/api/v2/tickets?updated_since=".$three_month_ago."&order_type=asc&include=stats&per_page=100";
        foreach($ticket_id as $key => $value) {
            $link = $data["link"]. "/api/v2/tickets/".$value."/metrics.json";
            $ticket_metric_data = array();
            $x = 1;
            $y = 3;
            
            //$this->ss_zd_ticket_metric->truncateTable();

            // for( $i = 1; $i<= $x; $i++ ) {
            //     $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => "Basic ".$api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
                for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => "Basic ".$api_key
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
                        $body = json_decode($response_retry->getBody(), true);
                        break;
                    }
                }
                
            } else {
                $body = json_decode($response->getBody(), true);
            }

            if(count($body["ticket_metric"]) != 0) {
                $ticket_metric_data = $body["ticket_metric"];
                $x++;

                // $final_data = array();
                // $ids = array();
                $count = 0;
                $len = count($ticket_metric_data);
                //$not_found = array();

                //foreach($ticket_metric_data as $key => $value) {
                    
                //$now = Carbon::now();
                // $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                // $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                // $group_name = ""; 
                // $department_name = ""; 

                // $group_name = html_entity_decode($group_name);
                // $process = html_entity_decode($value->custom_fields->cf_process);
                // $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                // $task = html_entity_decode($value->custom_fields->cf_task);

                // if($value->type == "No SLA") {
                //     $resolution_status = "Within SLA";
                // } else {
                //     if($resolved_at < $due_by) {
                //         $resolution_status = "Within SLA";
                //     } else {
                //         $resolution_status = "SLA Violated";    
                //     }
                // }

                // $first_responded_at = Carbon::parse($value->stats->first_responded_at)->setTimezone('Asia/Manila');
                // $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                // if($first_responded_at == NULL || $first_responded_at == "") {
                //     $fr_resolution_status = "";
                // } else {
                //     if($first_responded_at < $fr_due_by) {
                //         $fr_resolution_status = "Within SLA";
                //     } else {
                //         $fr_resolution_status = "SLA Violated";    
                //     }
                // }
            
                // $date_executed = Carbon::parse($value->created_at)->format("Ymd");

                $ticket_export = array(
                    "url" => $ticket_metric_data["url"],
                    "id" => $ticket_metric_data["id"],
                    "ticket_id" => $ticket_metric_data["ticket_id"],
                    "created_at" => Carbon::parse($ticket_metric_data["created_at"])->setTimezone('Asia/Manila'),
                    "updated_at" => Carbon::parse($ticket_metric_data["updated_at"])->setTimezone('Asia/Manila'),
                    "group_stations" => $ticket_metric_data["group_stations"],
                    "assignee_stations" => $ticket_metric_data["assignee_stations"],
                    "reopens" => $ticket_metric_data["reopens"],
                    "replies" => $ticket_metric_data["replies"],
                    "assignee_updated_at" => Carbon::parse($ticket_metric_data["assignee_updated_at"])->setTimezone('Asia/Manila'),
                    "requester_updated_at" => Carbon::parse($ticket_metric_data["requester_updated_at"])->setTimezone('Asia/Manila'),
                    "status_updated_at" => Carbon::parse($ticket_metric_data["status_updated_at"])->setTimezone('Asia/Manila'),
                    "initially_assigned_at" => Carbon::parse($ticket_metric_data["initially_assigned_at"])->setTimezone('Asia/Manila'),
                    "assigned_at" => Carbon::parse($ticket_metric_data["assigned_at"])->setTimezone('Asia/Manila'),
                    "solved_at" => Carbon::parse($ticket_metric_data["solved_at"])->setTimezone('Asia/Manila'),
                    "latest_comment_added_at" => Carbon::parse($ticket_metric_data["latest_comment_added_at"])->setTimezone('Asia/Manila'),
                    "reply_time_in_minutes_calendar" => $ticket_metric_data["reply_time_in_minutes"]["calendar"],
                    "reply_time_in_minutes_business" => $ticket_metric_data["reply_time_in_minutes"]["business"],
                    "first_resolution_time_in_minutes_calendar" => $ticket_metric_data["first_resolution_time_in_minutes"]["calendar"],
                    "first_resolution_time_in_minutes_business" => $ticket_metric_data["first_resolution_time_in_minutes"]["business"],
                    "full_resolution_time_in_minutes_calendar" => $ticket_metric_data["full_resolution_time_in_minutes"]["calendar"],
                    "full_resolution_time_in_minutes_business" => $ticket_metric_data["full_resolution_time_in_minutes"]["business"],
                    "agent_wait_time_in_minutes_calendar" => $ticket_metric_data["agent_wait_time_in_minutes"]["calendar"],
                    "agent_wait_time_in_minutes_business" => $ticket_metric_data["agent_wait_time_in_minutes"]["business"],
                    "requester_wait_time_in_minutes_calendar" => $ticket_metric_data["requester_wait_time_in_minutes"]["calendar"],
                    "requester_wait_time_in_minutes_business" => $ticket_metric_data["requester_wait_time_in_minutes"]["business"],
                    "on_hold_time_in_minutes_calendar" => $ticket_metric_data["on_hold_time_in_minutes"]["calendar"],
                    "on_hold_time_in_minutes_business" => $ticket_metric_data["on_hold_time_in_minutes"]["business"]
                );
                
                $final_data[] = $ticket_export;
                $ids[] = $ticket_metric_data["id"];
                // print_r($ticket_metric_data["id"]."</br>");
                // print_r($ticket_metric_data["ticket_id"]."</br>");
                // die;
                // $this->ss_zd_ticket_metric->deleteById($ticket_metric_data["id"]);
                // $this->ss_zd_ticket_metric->insert($ticket_export);

                // if( ($len - 1) > $key && count($final_data) == 50) {
                // $this->ss_zd_ticket_metric->bulkInsert($final_data);
                //     $final_data = [];
                // } 

                // if( ($len - 1) == $key) {
                //     $this->ss_zd_ticket_metric->bulkInsert($final_data);
                //     $final_data = [];
                // }
                //}
                
                // if(count($not_found) > 0) {
                //     $this->bp_not_found->bulkInsert($not_found);
                // }
            }
        }
        $this->ss_zd_ticket_metric->bulkDeleteById($ids);
        $this->ss_zd_ticket_metric->bulkInsert($final_data);

        //$this->ss_zd_ticket_metric->updateAllFdTickets("ss_zd");
        //return response()->json(['success'=> true], 200);
    }

    public function getAllUsers(){
        $client = new $this->guzzle();
        $data = config('constants.ss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();

        //$link = $data["link"]. "/api/v2/users.json?per_page=100";
        $ticket_metric_data = array();
        $x = 1;
        $y = 3;
        
        $this->ss_zd_user->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link = $data["link"]. "/api/v2/users.json?per_page=100";
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => "Basic ".$api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
                for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => "Basic ".$api_key
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
                        $body = json_decode($response_retry->getBody(), true);
                        break;
                    }
                }
                
            } else {
                $body = json_decode($response->getBody(), true);
            }

            if(count($body["users"]) != 0) {
                $user_data = $body["users"];
                $x++;

                // $final_data = array();
                // $ids = array();
                $count = 0;
                $len = count($user_data);
                //$not_found = array();

                foreach($user_data as $key => $value) {
                    //$now = Carbon::now();
                    // $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    // $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    // $group_name = ""; 
                    // $department_name = ""; 

                    // $group_name = html_entity_decode($group_name);
                    // $process = html_entity_decode($value->custom_fields->cf_process);
                    // $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                    // $task = html_entity_decode($value->custom_fields->cf_task);

                    // if($value->type == "No SLA") {
                    //     $resolution_status = "Within SLA";
                    // } else {
                    //     if($resolved_at < $due_by) {
                    //         $resolution_status = "Within SLA";
                    //     } else {
                    //         $resolution_status = "SLA Violated";    
                    //     }
                    // }

                    // $first_responded_at = Carbon::parse($value->stats->first_responded_at)->setTimezone('Asia/Manila');
                    // $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                    // if($first_responded_at == NULL || $first_responded_at == "") {
                    //     $fr_resolution_status = "";
                    // } else {
                    //     if($first_responded_at < $fr_due_by) {
                    //         $fr_resolution_status = "Within SLA";
                    //     } else {
                    //         $fr_resolution_status = "SLA Violated";    
                    //     }
                    // }
                
                    // $date_executed = Carbon::parse($value->created_at)->format("Ymd");

                    $users = array(
                        "id" => $value["id"],
                        "url" => $value["url"],
                        "name" => $value["name"],
                        "email" => $value["email"],
                        "created_at" => Carbon::parse($value["created_at"])->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value["updated_at"])->setTimezone('Asia/Manila'),
                        "time_zone" => $value["time_zone"],
                        "iana_time_zone" => $value["iana_time_zone"],
                        "phone" => $value["phone"],
                        "shared_phone_number" => $value["shared_phone_number"],
                        "organization_id" => $value["organization_id"],
                        "role" => $value["role"],
                        "verified" => $value["verified"],
                        "external_id" => $value["external_id"],
                        "tags" => json_encode($value["tags"]),
                        "alias" => $value["alias"],
                        "active" => $value["active"],
                        "shared" => $value["shared"],
                        "shared_agent" => $value["shared_agent"],
                        "last_login_at" => Carbon::parse($value["last_login_at"])->setTimezone('Asia/Manila'),
                        "two_factor_auth_enabled" => $value["two_factor_auth_enabled"],
                        "signature" => $value["signature"],
                        "details" => $value["details"],
                        "notes" => $value["notes"],
                        "role_type" => $value["role_type"],
                        "custom_role_id" => $value["custom_role_id"],
                        "moderator" => $value["moderator"],
                        "ticket_restriction" => $value["ticket_restriction"],
                        "only_private_comments" => $value["only_private_comments"],
                        "restricted_agent" => $value["restricted_agent"],
                        "suspended" => $value["suspended"],
                        "chat_only" => $value["chat_only"],
                        "default_group_id" => $value["default_group_id"],
                        "report_csv" => $value["report_csv"],
                        "user_fields_email_address" => $value["user_fields"]["email_address"],
                        "user_fields_full_name" => $value["user_fields"]["full_name"],
                        "user_fields_phone_number" => $value["user_fields"]["phone_number"]
                    );
                    
                    $final_data[] = $users;
                    //$ids[] = $ticket_metric_data["id"];
                    // print_r($ticket_metric_data["id"]."</br>");
                    // print_r($ticket_metric_data["ticket_id"]."</br>");
                    // die;
                    // $this->ss_zd_ticket_metric->deleteById($ticket_metric_data["id"]);
                    // $this->ss_zd_ticket_metric->insert($ticket_export);

                    // if( ($len - 1) > $key && count($final_data) == 50) {
                    // $this->ss_user->bulkInsert($final_data);
                    //     $final_data = [];
                    // } 

                    // if( ($len - 1) == $key) {
                    //     $this->ss_zd_ticket_metric->bulkInsert($final_data);
                    //     $final_data = [];
                    // }
                    //}
                    
                    // if(count($not_found) > 0) {
                    //     $this->bp_not_found->bulkInsert($not_found);
                }

                $this->ss_zd_user->bulkInsert($final_data);
            }

        }

        return response()->json(['success'=> true], 200); 
    }

    public function getAllGroups(){
        $client = new $this->guzzle();
        $data = config('constants.ss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();

        //$link = $data["link"]. "/api/v2/groups.json?per_page=100";
        $ticket_metric_data = array();
        $x = 1;
        $y = 3;
        
        $this->ss_zd_group->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link = $data["link"]. "/api/v2/groups.json?per_page=100";
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => "Basic ".$api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
                for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => "Basic ".$api_key
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
                        $body = json_decode($response_retry->getBody(), true);
                        break;
                    }
                }
                
            } else {
                $body = json_decode($response->getBody(), true);
            }

            if(count($body["groups"]) != 0) {
                $user_data = $body["groups"];
                $x++;

                // $final_data = array();
                // $ids = array();
                $count = 0;
                $len = count($user_data);
                //$not_found = array();

                foreach($user_data as $key => $value) {
                    //$now = Carbon::now();
                    // $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    // $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    // $group_name = ""; 
                    // $department_name = ""; 

                    // $group_name = html_entity_decode($group_name);
                    // $process = html_entity_decode($value->custom_fields->cf_process);
                    // $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                    // $task = html_entity_decode($value->custom_fields->cf_task);

                    // if($value->type == "No SLA") {
                    //     $resolution_status = "Within SLA";
                    // } else {
                    //     if($resolved_at < $due_by) {
                    //         $resolution_status = "Within SLA";
                    //     } else {
                    //         $resolution_status = "SLA Violated";    
                    //     }
                    // }

                    // $first_responded_at = Carbon::parse($value->stats->first_responded_at)->setTimezone('Asia/Manila');
                    // $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                    // if($first_responded_at == NULL || $first_responded_at == "") {
                    //     $fr_resolution_status = "";
                    // } else {
                    //     if($first_responded_at < $fr_due_by) {
                    //         $fr_resolution_status = "Within SLA";
                    //     } else {
                    //         $fr_resolution_status = "SLA Violated";    
                    //     }
                    // }
                
                    // $date_executed = Carbon::parse($value->created_at)->format("Ymd");

                    $groups = array(
                        "url" => $value["url"],
                        "id" => $value["id"],
                        "name" => $value["name"],
                        "description" => $value["description"],
                        "default" => $value["default"],
                        "deleted" => $value["deleted"],
                        "created_at" => Carbon::parse($value["created_at"])->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value["updated_at"])->setTimezone('Asia/Manila')
                    );
                    
                    $final_data[] = $groups;
                    //$ids[] = $ticket_metric_data["id"];
                    // print_r($ticket_metric_data["id"]."</br>");
                    // print_r($ticket_metric_data["ticket_id"]."</br>");
                    // die;
                    // $this->ss_zd_ticket_metric->deleteById($ticket_metric_data["id"]);
                    // $this->ss_zd_ticket_metric->insert($ticket_export);

                    // if( ($len - 1) > $key && count($final_data) == 50) {
                    // $this->ss_user->bulkInsert($final_data);
                    //     $final_data = [];
                    // } 

                    // if( ($len - 1) == $key) {
                    //     $this->ss_zd_ticket_metric->bulkInsert($final_data);
                    //     $final_data = [];
                    // }
                    //}
                    
                    // if(count($not_found) > 0) {
                    //     $this->bp_not_found->bulkInsert($not_found);
                }

                $this->ss_zd_group->bulkInsert($final_data);
            }

        }

        return response()->json(['success'=> true], 200); 
    }

    public function getAllOrganizations(){
        $client = new $this->guzzle();
        $data = config('constants.ss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();

        //$link = $data["link"]. "/api/v2/organizations.json?per_page=100";
        $ticket_metric_data = array();
        $x = 1;
        $y = 3;
        
        $this->ss_zd_organization->truncateTable();

        for( $i = 1; $i<= $x; $i++ ) {
            $link = $data["link"]. "/api/v2/organizations.json?per_page=100";
            $link .= "&page=".$i;
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => "Basic ".$api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
                for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => "Basic ".$api_key
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
                        $body = json_decode($response_retry->getBody(), true);
                        break;
                    }
                }
                
            } else {
                $body = json_decode($response->getBody(), true);
            }

            if(count($body["organizations"]) != 0) {
                $user_data = $body["organizations"];
                $x++;

                // $final_data = array();
                // $ids = array();
                $count = 0;
                $len = count($user_data);
                //$not_found = array();

                foreach($user_data as $key => $value) {
                    //$now = Carbon::now();
                    // $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    // $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    // $group_name = ""; 
                    // $department_name = ""; 

                    // $group_name = html_entity_decode($group_name);
                    // $process = html_entity_decode($value->custom_fields->cf_process);
                    // $sub_process = html_entity_decode($value->custom_fields->cf_sub_process);
                    // $task = html_entity_decode($value->custom_fields->cf_task);

                    // if($value->type == "No SLA") {
                    //     $resolution_status = "Within SLA";
                    // } else {
                    //     if($resolved_at < $due_by) {
                    //         $resolution_status = "Within SLA";
                    //     } else {
                    //         $resolution_status = "SLA Violated";    
                    //     }
                    // }

                    // $first_responded_at = Carbon::parse($value->stats->first_responded_at)->setTimezone('Asia/Manila');
                    // $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                    // if($first_responded_at == NULL || $first_responded_at == "") {
                    //     $fr_resolution_status = "";
                    // } else {
                    //     if($first_responded_at < $fr_due_by) {
                    //         $fr_resolution_status = "Within SLA";
                    //     } else {
                    //         $fr_resolution_status = "SLA Violated";    
                    //     }
                    // }
                
                    // $date_executed = Carbon::parse($value->created_at)->format("Ymd");

                    $organizations = array(
                        "url" => $value["url"],
                        "id" => $value["id"],
                        "name" => $value["name"],
                        "shared_tickets" => $value["shared_tickets"],
                        "shared_comments" => $value["shared_comments"],
                        "external_id" => $value["external_id"],
                        "created_at" => Carbon::parse($value["created_at"])->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value["updated_at"])->setTimezone('Asia/Manila'),
                        'domain_names' => json_encode($value["domain_names"]),
                        'details' => $value["details"],
                        'notes' => $value["notes"],
                        'group_id' => $value["group_id"],
                        'tags' => json_encode($value["tags"]),
                        'organization_fields_company_name' => $value["organization_fields"]["company_name"],
                        'organization_fields_domain_name' => $value["organization_fields"]["domain_name"]
                    );
                    
                    $final_data[] = $organizations;
                    //$ids[] = $ticket_metric_data["id"];
                    // print_r($ticket_metric_data["id"]."</br>");
                    // print_r($ticket_metric_data["ticket_id"]."</br>");
                    // die;
                    // $this->ss_zd_ticket_metric->deleteById($ticket_metric_data["id"]);
                    // $this->ss_zd_ticket_metric->insert($ticket_export);

                    // if( ($len - 1) > $key && count($final_data) == 50) {
                    // $this->ss_user->bulkInsert($final_data);
                    //     $final_data = [];
                    // } 

                    // if( ($len - 1) == $key) {
                    //     $this->ss_zd_ticket_metric->bulkInsert($final_data);
                    //     $final_data = [];
                    // }
                    //}
                    
                    // if(count($not_found) > 0) {
                    //     $this->bp_not_found->bulkInsert($not_found);
                }

                $this->ss_zd_organization->bulkInsert($final_data);
            }

        }

        return response()->json(['success'=> true], 200); 
    }

    public function getLatestTicketMetricEvents(){
        $client = new $this->guzzle();
        $data = config('constants.ss_zd');
        $two_days_ago = Carbon::today()->subDays(100)->timestamp; //should be subDays(2)
        $next_start_time = NULL;

        $api_key = $data["api_key"];
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        for( $i = 1; $i<= $x; $i++ ) {

            if (is_null($next_start_time) && $i == 1) {

                $link = $data["link"]. "/api/v2/incremental/ticket_metric_events.json?start_time="; 
                $link .= $two_days_ago;    

            }
            else if (is_null($next_start_time) && $i > 1) {

                break;

            }
            else {

                $link = $data["link"]. "/api/v2/incremental/ticket_metric_events.json?start_time="; 
                $link .= $next_start_time;   
                   
            }
      
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => "Basic ".$api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if($status_code != 200 ) {
               for($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => "Basic ".$api_key
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
                        $body = json_decode($response_retry->getBody(), true);
                        if ($next_start_time == $body['end_time']) {
                            $next_start_time = NULL;
                        }
                        else {
                            $next_start_time = $body['end_time'];
                        }
                        break;
                    }
               }
                
            } else {
                $body = json_decode($response->getBody(), true);
                if ($next_start_time == $body['end_time']) {
                    $next_start_time = NULL;
                }
                else {
                    $next_start_time = $body['end_time'];
                }
                
            }
            
            if(count($body['ticket_metric_events']) != 0) {
                $ticket_metric_events_data = $body['ticket_metric_events'];
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($ticket_metric_events_data);
                $not_found = array();
                $ids = array();  
                $duplicates = array();  //delete

                foreach($ticket_metric_events_data as $key => $value) {

                    $deleted = NULL;
                    $status_calendar = NULL;
                    $status_business = NULL;
                    $sla_target = NULL;
                    $sla_business_hours = NULL;
                    $sla_policy_id = NULL;
                    $sla_policy_title = NULL;
                    $sla_policy_description = NULL;      
                    
                    if ($value["metric"] == 'requester_wait_time') {      

                        if (in_array($value["id"], $ids)) {
                            break;
                        } 
                        
                        $ids[] = $value["id"];               

                        switch ($value["type"]) {
                            case "breach":
                            $deleted = $value["deleted"];
                            break;
                            case "update_status":
                            $status_calendar = $value["status"]["calendar"];
                            $status_business = $value["status"]["business"];    
                            break;
                            case "apply_sla":
                            $sla_target = $value["sla"]["target"];
                            $sla_business_hours = $value["sla"]["business_hours"];
                            $sla_policy_id = $value["sla"]["policy"]["id"];
                            $sla_policy_title = $value["sla"]["policy"]["title"];
                            $sla_policy_description = $value["sla"]["policy"]["description"];                  
                        }

                        $ticket_metric_events = array(
                            'id' => $value["id"],
                            'ticket_id' => $value["ticket_id"],
                            'metric' => $value["metric"],
                            'instance_id' => $value["instance_id"],
                            'type' => $value["type"],
                            'time' => Carbon::parse($value["time"])->setTimezone('Asia/Manila'),
                            'deleted' => $deleted,
                            'status_calendar' => $status_calendar,
                            'status_business' => $status_business,
                            'sla_target_min' => $sla_target,
                            'sla_business_hours' => $sla_business_hours,
                            'sla_policy_id' => $sla_policy_id,
                            'sla_policy_title' => $sla_policy_title,
                            'sla_policy_description' => $sla_policy_description
                        );
                        
                        $final_data[] = $ticket_metric_events;
    
                        if( ($len - 1) > $key && count($final_data) == 50) {    
    
                            $this->ss_zd_ticket_metric_event->bulkDeleteById($ids);
                            $this->ss_zd_ticket_metric_event->bulkInsert($final_data);
                            $final_data = [];
                            $ids = [];
                        } 

                        if( ($len - 1) == $key) {
                            $this->ss_zd_ticket_metric_event->bulkDeleteById($ids);
                            $this->ss_zd_ticket_metric_event->bulkInsert($final_data);        
                            $final_data = [];
                            $ids = [];
                        }

                    }

                    else {

                        if( ($len - 1) == $key) {
                            $this->ss_zd_ticket_metric_event->bulkDeleteById($ids);
                            $this->ss_zd_ticket_metric_event->bulkInsert($final_data);        
                            $final_data = [];
                            $ids = [];
                        }
                    }


                }
            }
               
        } 

        //$this->tagflix_fd_ticket->updateLatestFdTickets("tagflix_fd");
        return response()->json(['success'=> true], 200);
    }

    public function updateAll() {
        $lists = [
            ["function" => "getAllGroups","type" => "groups"],
            ["function" => "getAllOrganizations","type" => "organizations"],
            ["function" => "getAllUsers","type" => "users"],
        ];
    
        foreach($lists as $val) {
            $response = $this->{$val["function"]}();
            $return = $this->loopUpdate($response, $val);
            
            if(!$return) {
                return response()->json(['success'=> false,'message' => 'error on '.$val["type"]], 200);
            }
        }
        //$this->tagflix_fd_contact->deleteDuplicates("tagflix_fd_contacts");
        //$this->tagflix_fd_contact->addAgentsToContacts("tagflix_fd");

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
}