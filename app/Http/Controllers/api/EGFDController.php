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





class EGFDController extends Controller
{
    public function __construct(
        Guzzle $guzzle,
        EGSurvey $eg_survey,
        EGSurveyQuestion $eg_survey_question,
        EGSatisfactoryRating $eg_satisfactory_rating,
        EGSatisfactoryResponse $eg_satisfactory_response
    )
    {  
        $this->guzzle = $guzzle;
        $this->eg_survey = $eg_survey;
        $this->eg_survey_question = $eg_survey_question;
        $this->eg_satisfactory_rating = $eg_satisfactory_rating;
        $this->eg_satisfactory_response = $eg_satisfactory_response;
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
        $link = $data["link"]. "/api/v2/surveys/satisfaction_ratings?per_page=100";
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
                            "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                        );

                        $final_rating_data[] = $r;
                    }
                    $this->eg_satisfactory_rating->bulkInsert($final_rating_data);

                    $final_response_data[] = $response;
                }

                $this->eg_satisfactory_response->bulkInsert($final_response_data);
            } 

        }

        return response()->json(['success'=> true], 200);

    }

    public function getLatestRating() {
        $client = new $this->guzzle();
        $data = config('constants.eg');
        $two_days_ago = Carbon::now()->format('Y-m-d');
        $api_key = $data["api_key"];
        $link = $data["link"]. "/api/v2/surveys/satisfaction_ratings?per_page=100&created_since=".$two_days_ago;
        $ticket_export_data = array();
        $x = 1;
        $y = 3;

        //$this->eg_satisfactory_rating->truncateTable();
        //$this->eg_satisfactory_response->truncateTable();

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
                            "updated_at" => Carbon::parse($value->updated_at)->setTimezone('Asia/Manila')
                        );

                        $final_rating_data[] = $r;
                    }
                    $this->eg_satisfactory_rating->bulkInsert($final_rating_data);

                    $final_response_data[] = $response;
                }

                $this->eg_satisfactory_response->bulkInsert($final_response_data);
            } 

        }

        return response()->json(['success'=> true], 200);

    }
}
