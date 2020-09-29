<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \GuzzleHttp\Client as Guzzle;

class ZNJamesTicketController extends Controller
{
    private $guzzle;
    private $current_page = 1;
    private $page_limit = 10;
    private $array_tickets = [];
    private $retry_limit = 3;


    public function __construct(){
        $this->guzzle = new Guzzle();
    }

    public function getAllTickets(){
       $response = $this->getListAllTickets();
       $response_status = $response->getStatusCode();
       $retry = 0;

       if ($response_status !== 200){
           $retry++;
          
           while($retry < $this->retry_limit){
            $response = $this->getListAllTickets();
            $this->parseSuccessResponse($response);
           }
       }
       else {
        return $this->parseSuccessResponse($response);
       } 
    }

    private function parseSuccessResponse($response){
        //getBody() built in function laravel
        $body = json_decode($response->getBody());
        $has_next_page = !is_null($body->next_page);

        foreach($body->tickets as $ticket){
            array_push($this->array_tickets, $ticket);
        }

        if($has_next_page){
            $this->current_page++;
            return $this->getAllTickets();
        }
        else {
            return response()->json($this->array_tickets, 200);
        }
        
    }

    private function getListAllTickets(){
        //constants.php
        $data = config('constants.ssj');
        $link = $data['link'] . $data['ticket_link'] .'?page=' . $this->current_page . '&per_page=' . $this->page_limit;
        $response = $this->guzzle->request('GET', $link, [
            'headers' => [
                //'Authorization' => 'Basic ' . $data['api_key']
                'Authorization' => 'Basic ' . base64_encode('service@startsmartsourcing.com/token:' . $data['api_key'])
            ]
        ]);

       return $response;
    }

}
