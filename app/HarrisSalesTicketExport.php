<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HarrisSalesTicketExport extends Model
{
    protected $table = 'HarrisSalesTicketExport';
    protected $fillable = [
        'Ticket ID',
        'Subject',
        'Status',
        'Priority',
        'Source',
        'Type',
        'Agent',
        'Group',
        'Created Time',
        'Resolved time',
        'Closed Time',
        'Last updated time',
        'Time tracked',
        'Agent interactions',
        'Customer interactions',
        'Tags',
        'Survey results',
        'Due by Time',
        'Initial response time',
        'First response time (in hrs)',
        'Resolution time (in hrs)',
        'Resolution status',
        'First response status',
        'Association type',
        'Product',
        'Channel',
        'Process',
        'Sub Process',
        'Task',
        'Workflow Status',
        'AB Listing Agent',
        'AB Listing ID',
        'Office',
        'Team',
        'Tracker Id',
        'Unit of measure',
        'Service Type',
        'Request Type',
        'Full name',
        'Title',
        'Email',
        'Requester Team',
        'Requester Office',
        'Company Name',
        'Tracker Status',
        'Category',
        'Total Turnaround',
        'Unique ID',
        'ID'
    ];

    public function insertTickets($data) {
        return static::create($data);
    }

    public function getTicketData($ticket_id) {
        return static::where('Ticket ID',$ticket_id)->first();
    } 
}
