<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DinglesFDTimeEntry extends Model
{
    protected $table = 'dingles_fd_time_entries_v2';
    protected $fillable = [
        'ticket_id',
        'agent' ,
        'charge_type',
        'customer' ,
        'date' ,
        'hours',
        'notes',
        'product',
        'subject',
        'attendance_id',
        'created_at',
        'update_at'
    ];

    public function insert($data) {
        return static::create($data);
    }

    public function bulkInsert($data){
        return DB::table('dingles_fd_time_entries_v2')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTimeEntryId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
    
    public function bulkDeleteByCreatedAtDate($date){
        return static::whereDate('created_at', '>=', $date)->delete();
    }
}
