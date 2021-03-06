<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DSFDTimeEntry extends Model
{
    protected $table = 'ds_fd_time_entries_v2';
    protected $fillable = [
        'ticket_id',
        'agent' ,
        'charge_type',
        'customer' ,
        'executed_at' ,
        'hours',
        'notes',
        'product',
        'subject',
        'created_at',
        'updated_at',
        'closed_at_id',
        'executed_at_id'
    ];

    public function insert($data) {
        return static::create($data);
    }

    public function bulkInsert($data){
        return DB::table('ds_fd_time_entries_v2')->insert($data);
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

    public function updateAllAttendanceID($table_name) {
        $values = [$table_name];
        DB::insert('EXEC update_all_timeentries_v2 ?', $values);
    }

    public function bulkDeleteByLimitDate($start,$end){
        return static::whereBetween('executed_at', [$start,$end])->delete();
    }
}
