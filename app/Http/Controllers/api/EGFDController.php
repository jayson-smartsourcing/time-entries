<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon as Carbon;
use \GuzzleHttp\Client as Guzzle;

use App\EGSurvey as EGSurvey;
use App\EGSurveyQuestion as EGSurveyQuestion;
use App\EGSatisfactoryRating as EGSatisfactoryRating;
use App\EGSatisfactoryResponse as EGSatisfactoryResponse;
use App\EGFDGroup as EGFDGroup;
use App\EGFDCompany as EGFDCompany;
use App\EGFDAgent as EGFDAgent;
use App\EGFDContact as EGFDContact;
use App\EGFDTicket as EGFDTicket;



class EGFDController extends Controller
{
    public function __construct(
        Guzzle $guzzle,
        EGSurvey $eg_survey,
        EGSurveyQuestion $eg_survey_question,
        EGSatisfactoryRating $eg_satisfactory_rating,
        EGSatisfactoryResponse $eg_satisfactory_response,
        EGFDGroup $eg_fd_group,
        EGFDCompany $eg_fd_company,
        EGFDAgent $eg_fd_agent,
        EGFDContact $eg_fd_contact,
        EGFDTicket $eg_fd_ticket
    )
    {  
        $this->guzzle = $guzzle;
        $this->eg_survey = $eg_survey;
        $this->eg_survey_question = $eg_survey_question;
        $this->eg_satisfactory_rating = $eg_satisfactory_rating;
        $this->eg_satisfactory_response = $eg_satisfactory_response;
        $this->eg_fd_group = $eg_fd_group;
        $this->eg_fd_company = $eg_fd_company;
        $this->eg_fd_agent = $eg_fd_agent;
        $this->eg_fd_contact = $eg_fd_contact;
        $this->eg_fd_ticket = $eg_fd_ticket;
    }


    public function getAllSurvey() {
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/surveys?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->eg_survey->truncateTable();
        $this->eg_survey_question->truncateTable();

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
                        return response()->json(['success'=> false], 200);
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
                $surveys = $body;
                $x++;

                $final_survey_data = array();
                $count = 0;
                $len = count($surveys);
                $date_created = new Carbon();
                foreach($surveys as $key => $value) {
                    $survey = array(
                        "id" => $value->id,
                        "title" => $value->title,
                        "active" => $value->active,
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    $final_question_data = array();
                    foreach($value->questions as $inn_key => $question) {

                        $q = array(
                            "id" => $question->id,
                            "survey_id" => $value->id,
                            "label" => $question->label,
                            "accepted_ratings" => json_encode($question->accepted_ratings),
                            "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                            "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                        );

                        $final_question_data[] = $q;
                    }
                    $this->eg_survey_question->bulkInsert($final_question_data);

                    $final_survey_data[] = $survey;
                }

                $this->eg_survey->bulkInsert($final_survey_data);
            } 

        }

        return response()->json(['success'=> true], 200);

    }


    public function getAllRating() {
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $api_key = $data["api_key"];
        $three_month_ago = new Carbon("Last Day of December 2018");
        $three_month_ago = $three_month_ago->format("Y-m-d");

        $link = $data["link"]. "/api/v2/surveys/satisfaction_ratings?per_page=100&created_since=".$three_month_ago;
        
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->eg_satisfactory_rating->truncateTable();
        $this->eg_satisfactory_response->truncateTable();

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
                        return response()->json(['success'=> false], 200);
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
                $ratings = $body;
                $x++;

                $final_response_data = array();
                $count = 0;
                $len = count($ratings);
                $date_created = new Carbon();
                foreach($ratings as $key => $value) {
                    $response = array(
                        "id" => $value->id,
                        "survey_id" => $value->survey_id,
                        "user_id" => $value->user_id,
                        "agent_id" => $value->agent_id,
                        "feedback" => $value->feedback,
                        "group_id" => $value->group_id,
                        "ticket_id" => $value->ticket_id,
                        "ratings" => json_encode($value->ratings),
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    $final_rating_data = array();
                    foreach($value->ratings as $inn_key => $rating) {
                        $r = array(
                            "id" => $inn_key,
                            "survey_id" => $value->survey_id,
                            "rating" => $rating,
                            "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                            "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                            "response_id" => $value->id
                        );

                        $final_rating_data[] = $r;
                    }
                    $this->eg_satisfactory_rating->bulkInsert($final_rating_data);

                    $final_response_data[] = $response;
                }

                $this->eg_satisfactory_response->bulkInsert($final_response_data);
            } 

        }

        $this->eg_satisfactory_rating->addUpdateAtID("eg_fd");

        return response()->json(['success'=> true], 200);

    }

    public function getLatestRating() {

        $client = new $this->guzzle();
        $data = config('constants.eg');
        //$now = Carbon::yesterday()->format('Y-m-d');

        $now = Carbon::now()->subDays(2)->format('Y-m-d');
    
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/surveys/satisfaction_ratings?per_page=100&created_since=".$now;
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
                        return response()->json(['success'=> false], 200);
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
            
                $ratings = $body;
                $x++;

                $final_response_data = array();
                $count = 0;
                $len = count($ratings);
                $date_created = new Carbon();
                $responses_to_delete = array();
                
                foreach($ratings as $key => $value) {

                    
                    $final_rating_data = array();
                    $ratings_to_delete = array();
                    
                    $id = $value->id;
                    $response = array(
                        "id" => $value->id,
                        "survey_id" => $value->survey_id,
                        "user_id" => $value->user_id,
                        "agent_id" => $value->agent_id,
                        "feedback" => $value->feedback,
                        "group_id" => $value->group_id,
                        "ticket_id" => $value->ticket_id,
                        "ratings" => json_encode($value->ratings),
                        "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                        "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                    );

                    foreach($value->ratings as $inn_key => $rating) {
                        $r = array(
                            "id" => $inn_key,
                            "survey_id" => $value->survey_id,
                            "rating" => $rating,
                            "created_at" => Carbon::parse($value->created_at)->setTimezone('Asia/Manila'),
                            "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila'),
                            "response_id" => $id
                        );
                        
                        $final_rating_data[] = $r;
                        
                    }

                    $ratings_to_delete[] = $value->id;
                    $responses_to_delete[] = $value->id;
                    
                    $this->eg_satisfactory_rating->bulkDelete($ratings_to_delete);
                    $this->eg_satisfactory_rating->bulkInsert($final_rating_data);

                    $final_response_data[] = $response;
                  
                }         

                $this->eg_satisfactory_response->bulkDelete($responses_to_delete);
                $this->eg_satisfactory_response->bulkInsert($final_response_data);
            }  

        }
        $this->eg_satisfactory_response->addUpdateAtID("eg_fd");
        return response()->json(['success'=> true], 200);

    }


    public function getAllGroups() {
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/groups?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->eg_fd_group->truncateTable();

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
                $groups = $body;
                $x++;

                $final_data = array();
                $count = 0;
                $len = count($groups);
                $date_created = new Carbon();
                foreach($groups as $key => $value) {
                    $group = array(
                        "id" => $value->id,
                        "name" => html_entity_decode($value->name),
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

                $this->eg_fd_group->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllCompanies() {
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/companies?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->eg_fd_company->truncateTable();

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

                $this->eg_fd_company->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200);
    }

    public function getAllAgents(){
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/agents?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        $this->eg_fd_agent->truncateTable();

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

                $this->eg_fd_agent->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200); 
    }

    public function getAllContacts(){
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/contacts?per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;
        $this->eg_fd_contact->truncateTable();

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

                $this->eg_fd_contact->bulkInsert($final_data);
            } 

        }
        
        return response()->json(['success'=> true], 200); 
    }

    public function getAllTicketsV2(){
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $api_key = $data["api_key"];
        //$three_month_ago = new Carbon("2019-07-22");
        $three_month_ago = new Carbon("Last Day of September 2018");
        $three_month_ago = $three_month_ago->format("Y-m-d");

        $link = $data["link"]. "/api/v2/tickets?updated_since=".$three_month_ago."&order_type=asc&include=stats&per_page=100";
        $ticket_export_data = array();
        $x = 1;
        $y = 3;
        
        $this->eg_fd_ticket->truncateTable();

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
                        $this->eg_fd_ticket->bulkInsert($final_data);
                        $final_data = [];
                    } 

                    if( ($len - 1) == $key) {
                        $this->eg_fd_ticket->bulkInsert($final_data);
                        $final_data = [];
                    }
                }
                
                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }
            }
        }

        $this->eg_fd_ticket->updateAllFdTickets("eg_fd");
        return response()->json(['success'=> true], 200);
    }

    public function getLatestTicketExportV2() {
        $client = new $this->guzzle();
        $data = config('constants.eg');
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
   
                    $fr_due_by = Carbon::parse($value->fr_due_by)->setTimezone('Asia/Manila');
                    $first_responded_at = $value->stats->first_responded_at;

                    if($first_responded_at == NULL || $first_responded_at == "") {
                        $fr_resolution_status = "";
                    } else {
                        $first_responded_at = Carbon::parse($first_responded_at)->setTimezone('Asia/Manila');

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
                        
                        $this->eg_fd_ticket->bulkDeleteByTicketExportId($ids);
                        $this->eg_fd_ticket->bulkInsert($final_data);
                        $final_data = [];
                        $ids = [];
                    } 

                    if( ($len - 1) == $key) {
                        
                        $this->eg_fd_ticket->bulkDeleteByTicketExportId($ids);
                        $this->eg_fd_ticket->bulkInsert($final_data);
                        $final_data = [];
                        $ids = [];
                    }
                }
               
                if(count($not_found) > 0) {
                    $this->bp_not_found->bulkInsert($not_found);
                }
               
            } 

        }

        $this->eg_fd_ticket->updateLatestFdTickets("eg_fd");
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
        $this->eg_fd_contact->addAgentsToContacts("eg_fd");
        $this->eg_fd_agent->deleteDuplicates("eg_fdd_agents");
        $this->eg_fd_contact->deleteDuplicates("eg_fd_contacts");
        $this->eg_fd_company->deleteDuplicates("eg_fd_companies");
        $this->eg_fd_group->deleteDuplicates("eg_fd_groups");

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
