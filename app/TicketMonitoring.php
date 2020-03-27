<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketMonitoring extends Model
{
    protected $table = 'enps_ticket_monitoring';
    protected $fillable = [
       'account_name',
       'execution_type',
       'created_at'
    ];

    public function insert($data) {
        return static::create($data);
    }



}
