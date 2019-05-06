<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HarrisTimeEntriesTemp extends Model
{
    protected $table = 'HarrisSalesTimeEntriesTemp1';
    protected $fillable = [
        'Agent',
        'Billable Non-Billable',
        'Created at',
        'Updated at',
        'Customer',
        'Date',
        'Group',
        'Hours',
        'Notes',
        'Priority',
        'Product',
        'Process',
        'Sub Process',
        'Task',
        'Status',
        'Ticket'
    ];

    public function insertTimeEntriesTemp($data) {
        return static::create($data);
    }

    public function truncateTable() {
        return static::truncate();
    }

    
}
