<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \GuzzleHttp\Client as Guzzle;

class ZendeskTicketController extends Controller
{
    private $guzzle;
    private $page_limit = 10;
    private $retry_count_limit = 3;
    private $all_tickets = [];
    private $current_page = 1;

    public function __construct() {
        $this->guzzle = new Guzzle();
    }

    public function getTickets() {
        $response = $this->getListTickets();
        $status_code = $response->getStatusCode();
        $retry_counter = 0;

        if ($status_code !== 200) {
            $retry_counter++;
            while ($retry_counter < $this->retry_count_limit) {
                $response = $this->getListTickets();
                $this->parseSuccessResponse($response);
            }
        } else {
            return $this->parseSuccessResponse($response);
        }
    }

    private function parseSuccessResponse($response) {
        $body = json_decode($response->getBody());
        $has_next_page = !is_null($body->next_page);

        foreach ($body->tickets as $ticket) {
            array_push($this->all_tickets, $ticket);
        }

        if ($has_next_page) {
            $this->current_page++;
            return $this->getTickets();
        } else {
            return response()->json($this->all_tickets, 200);
        }
    }

    private function getListTickets() {
        $data = config('constants.ssh');
        $link = $data['link'] . 'api/v2/tickets.json?page=' . $this->current_page . '&per_page=' . $this->page_limit;
        $response = $this->guzzle->request('GET', $link, [
            'headers' => [
                'Authorization' => 'Basic ' . $data['api_key']
            ]
        ]);

        return $response;
    }
}
