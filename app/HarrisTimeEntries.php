<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HarrisTimeEntries extends Model
{
    protected $table = 'HarrisTimeEntries';
    protected $fillable = [
        'ID',
        'Agent',
        'Billable Non-Billable',
        'Created at',
        'Customer',
        'Date',
        'Group',
        'Hours',
        'Notes',
        'Priority',
        'Product',
        'Status',
        'Ticket',
        'Unique ID',
        'Type'
    ];


    public function insertTimeEntries($data) {
        return static::create($data);
    }
}
