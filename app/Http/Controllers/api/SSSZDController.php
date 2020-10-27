<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use \GuzzleHttp\Client as Guzzle;
use Carbon\Carbon as Carbon;
//model
use App\SSSZDTicket as SSSZDTicket;
use App\SSSZDTicketMetric as SSSZDTicketMetric;
use App\SSSZDUser as SSSZDUser;
use App\SSSZDOrganization as SSSZDOrganization;
use App\SSSZDGroup as SSSZDGroup;
use App\SSSZDTicketMetricEvent as SSSZDTicketMetricEvent;

class SSSZDController extends Controller
{

    public function __construct(
        Guzzle $guzzle,
        SSSZDTicket $sss_zd_ticket,
        SSSZDTicketMetric $sss_zd_ticket_metric,
        SSSZDUser $sss_zd_user,
        SSSZDOrganization $sss_zd_organization,
        SSSZDGroup $sss_zd_group,
        SSSZDTicketMetricEvent $sss_zd_ticket_metric_event
    )
    {  
        $this->guzzle = $guzzle;
        $this->sss_zd_ticket = $sss_zd_ticket;
        $this->sss_zd_ticket_metric = $sss_zd_ticket_metric;
        $this->sss_zd_user = $sss_zd_user;
        $this->sss_zd_organization = $sss_zd_organization;
        $this->sss_zd_group = $sss_zd_group;
        $this->sss_zd_ticket_metric_event = $sss_zd_ticket_metric_event;
    }

    public function getLatestTickets() {
        $client = new $this->guzzle();
        $data = config('constants.sss_zd');
        $two_days_ago = Carbon::today()->subDays(8)->timestamp; //should be subDays(2)

        //$link = $data["link"]. "/api/v2/tickets.json?sort_by=updated_at&sort_order=desc&per_page=100"; //should be desc
        $api_key = $data["api_key"];
        $ticket_export_data = array();
        $x = 1;
        $y = 3;
        $has_next = true;
        $per_page = '&per_page=100';
        $link = $data["link"] . '/api/v2/tickets.json?sort_by=updated_at&sort_order=desc&page=1' . $per_page; //should be desc
        // $counter = 0;

        while ($has_next) {
            // $counter++;            
            //call to api
            $response = $client->request('GET', $link, [
                'headers' => [
                    'Authorization' => "Basic ". $api_key
                ]
            ]);
            // get Status Code
            $status_code = $response->getStatusCode();  

            if ($status_code !== 200) {
               for ($tries = 0; $tries < $y; $tries++) {
                    //retry call api
                    $response_retry = $client->request('GET', $link, [
                        'headers' => [
                            'Authorization' => "Basic ".$api_key
                        ]
                    ]);
                    //get status Code    
                    $status_code = $response_retry->getStatusCode(); 

                    if ($status_code != 200 && $tries == 2) {
                        $failed_data["link"] = $link;
                        $failed_data["status"] = $status_code;
                        $this->failed_time_entries->addData($failed_data);
                        break 2;
                    } 

                    if ($status_code == 200) {
                        $body = json_decode($response_retry->getBody(), true);
                        break;
                    }
               }
                
            } else {
                $body = json_decode($response->getBody(), true);
            }

            if (is_null($body['next_page'])) {
                $has_next = false;
                $link = null;
                // dd('test');
            } else {
                $has_next = true;
                $link = $body['next_page'] . $per_page;
                // dd('asd');
            }
            // if ($counter > 1) {
            //     dd(count($body['tickets']));
            // }
           
            if (count($body['tickets']) > 0) {
                $ticket_export_data = $body['tickets'];

                $final_data = array();
                $count = 0;
                $len = count($ticket_export_data);
                $not_found = array();
                $ids = array();  

                foreach ($ticket_export_data as $key => $value) {

                    $now = Carbon::now();
                    // $due_by = Carbon::parse($value->due_by)->setTimezone('Asia/Manila');
                    // $resolved_at = Carbon::parse($value->stats->resolved_at)->setTimezone('Asia/Manila');
                    // $group_name = ""; 
                    // $department_name = ""; 
                    // print_r($value["custom_fields"][0]["id"]);
                    // die;
                    $updated_at = Carbon::parse($value["updated_at"])->timestamp;
                    // if ($counter > 1) {
                    //     dd($updated_at >= $two_days_ago);
                    // }
                   
                    if ($updated_at >= $two_days_ago) {
                        //  echo($updated_at."<br>");
                        // echo($two_days_ago."<br>");
                        // echo("getTicketMetric");
                        // die;
                        //$this->getTicketMetric($value["id"]); takes too long, have to do collate all latest ticket_id 's first

                        $ids[] = $value["id"];

                        foreach ($value["custom_fields"] as $key => $custom_field) {

                            switch ($custom_field["id"]) {
                                case "360036760492":
                                $sub_process = $custom_field["value"];
                                break;
                                case "360036679171":
                                $task = $custom_field["value"];
                                break;
                                case "360036762292":
                                $total_time_spent_sec = $custom_field["value"];
                                break;
                                case "360036679631":
                                $channel = $custom_field["value"];
                                break;
                                case "360036762312":
                                $time_spent_last_update_sec = $custom_field["value"];
                                break;     
                                case "360036680611":
                                $turnaround_time = $custom_field["value"];
                                break;        
                                case "360036847472":
                                $task_count = $custom_field["value"];
                                break;   
                                case "360036760472":
                                $process = $custom_field["value"];
                                break;                                 
                                default:
                                $unknown_value = $custom_field["value"];
                            }
                        }

                        //$date_executed = Carbon::parse($value[->created_at])->format("Ymd");
                        //print_r($value["id"]);
                        if (count($value["via"]["source"]["from"]) < 1) {
                            //echo("SOURCE FROM LESS THAN 1!");
                            $source_from_address = NULL;
                            $source_from_name = NULL;
                            //print_r($value["id"]);
                        } else {
                            $source_from_address = $value["via"]["source"]["from"]["address"];
                            $source_from_name = $value["via"]["source"]["from"]["name"];
                        }

                        if (count($value["via"]["source"]["to"]) < 1) {
                            //echo("SOURCE TO LESS THAN 1");
                            $source_to_address = NULL;
                            $source_to_name = NULL;
                            //print_r($value["id"]);
                        } else {
                            $source_to_address = $value["via"]["source"]["to"]["address"];
                            $source_to_name = $value["via"]["source"]["to"]["name"];
                            //print_r($value["id"]);
                        }

                        if ($value["satisfaction_rating"]["score"] === "offered" || $value["satisfaction_rating"]["score"] === "unoffered") {
                            $satisfaction_rating_id = NULL;
                            $satisfaction_rating_comment = NULL;
                            $satisfaction_rating_reason = NULL;
                            $satisfaction_rating_reason_id = NULL;
                        } else {
                            $satisfaction_rating_id = $value["satisfaction_rating"]["id"];
                            $satisfaction_rating_comment = $value["satisfaction_rating"]["comment"];
                            $satisfaction_rating_reason = $value["satisfaction_rating"]["reason"];
                            $satisfaction_rating_reason_id = $value["satisfaction_rating"]["reason_id"];
                        }

                        $ticket_export = [
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
                        ];
                        
                        $final_data[] = $ticket_export;

                        if (($len - 1) > $key && count($final_data) <= 50) {
                            $this->sss_zd_ticket->bulkDeleteById($ids);
                            $this->sss_zd_ticket->bulkInsert($final_data);
                            $this->getTicketMetric($ids);
                            $final_data = [];
                            $ids = [];
                        }

                        if (($len - 1) === $key) {
                            $this->sss_zd_ticket->bulkDeleteById($ids);
                            $this->sss_zd_ticket->bulkInsert($final_data);
                            $this->getTicketMetric($ids);            
                            $final_data = [];
                            $ids = [];
                        }
                    } else {
                        //echo("else");    
                        //print_r($final_data[]);
                        //die;
                        // break 2;
                      break;
                    }
                }
            }
        }
        // dd($counter);

        //$this->tagflix_fd_ticket->updateLatestFdTickets("tagflix_fd");
        return response()->json(['success'=> true], 200);
    }
    


    public function getTicketMetric($ticket_id){
        $client = new $this->guzzle();
        $data = config('constants.sss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();

        foreach($ticket_id as $key => $value) {
            $link = $data["link"]. "/api/v2/tickets/".$value."/metrics.json";
            $ticket_metric_data = array();
            $x = 1;
            $y = 3;
            

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

            }
        }
        $this->sss_zd_ticket_metric->bulkDeleteById($ids);
        $this->sss_zd_ticket_metric->bulkInsert($final_data);


    }

    public function getAllUsers(){
        $client = new $this->guzzle();
        $data = config('constants.sss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();

        //$link = $data["link"]. "/api/v2/users.json?per_page=100";
        $ticket_metric_data = array();
        $x = 1;
        $y = 3;
        
        $this->sss_zd_user->truncateTable();

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
                        // "user_fields_email_address" => $value["user_fields"]["email_address"],
                        // "user_fields_full_name" => $value["user_fields"]["full_name"],
                        // "user_fields_phone_number" => $value["user_fields"]["phone_number"]
                    );
                    
                    $final_data[] = $users;

                }

                $this->sss_zd_user->bulkInsert($final_data);
            }

        }

        return response()->json(['success'=> true], 200); 
    }

    public function getAllOrganizations(){
        $client = new $this->guzzle();
        $data = config('constants.sss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();

        //$link = $data["link"]. "/api/v2/organizations.json?per_page=100";
        $ticket_metric_data = array();
        $x = 1;
        $y = 3;
        
        $this->sss_zd_organization->truncateTable();

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
                        // 'organization_fields_company_name' => $value["organization_fields"]["company_name"],
                        // 'organization_fields_domain_name' => $value["organization_fields"]["domain_name"]
                    );
                    
                    $final_data[] = $organizations;

                }

                $this->sss_zd_organization->bulkInsert($final_data);
            }

        }

        return response()->json(['success'=> true], 200); 
    }
    
    public function getAllGroups(){
        $client = new $this->guzzle();
        $data = config('constants.sss_zd');
        $api_key = $data["api_key"];
        $final_data = array();
        $ids = array();

        //$link = $data["link"]. "/api/v2/groups.json?per_page=100";
        $ticket_metric_data = array();
        $x = 1;
        $y = 3;
        
        $this->sss_zd_group->truncateTable();

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


                $count = 0;
                $len = count($user_data);


                foreach($user_data as $key => $value) {
  

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

                }

                $this->sss_zd_group->bulkInsert($final_data);
            }

        }

        return response()->json(['success'=> true], 200); 
    }

    public function getLatestTicketMetricEvents(){
        $client = new $this->guzzle();
        $data = config('constants.sss_zd');
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
    
                            $this->sss_zd_ticket_metric_event->bulkDeleteById($ids);
                            $this->sss_zd_ticket_metric_event->bulkInsert($final_data);
                            $final_data = [];
                            $ids = [];
                        } 

                        if( ($len - 1) == $key) {
                            $this->sss_zd_ticket_metric_event->bulkDeleteById($ids);
                            $this->sss_zd_ticket_metric_event->bulkInsert($final_data);        
                            $final_data = [];
                            $ids = [];
                        }

                    }

                    else {

                        if( ($len - 1) == $key) {
                            $this->sss_zd_ticket_metric_event->bulkDeleteById($ids);
                            $this->sss_zd_ticket_metric_event->bulkInsert($final_data);        
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