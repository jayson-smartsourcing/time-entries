<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketExportStatus extends Model
{
    protected $table = 'AllTicketExportStatus';
    protected $fillable = [
       'link',
       'status',
       'account',
       'created_at',
       'updated_at'
    ];

    public function insert($data){
        return DB::table('AllTicketExportStatus')->insert($data);
    }
   
}
