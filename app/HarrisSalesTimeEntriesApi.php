<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HarrisSalesTimeEntriesApi extends Model
{
    
    protected $table = 'HarrisSalesTimeEntriesApi';
    protected $fillable = [
        'attendance_id',
        'time_entry_id',
        'billable',
        'note',
        'timer_running',
        'agent_id',
        'ticket_id',
        'company_id',
        'time_spent',
        'executed_at',
        'start_time',
        'entry_created_at',
        'entry_updated_at',
    ];

    public function insert($data) {
        return static::create($data);
    }

    public function bulkInsert($data){
        return DB::table('HarrisSalesTimeEntriesApi')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTimeEntryId($ids_to_delete){
        return static::whereIn('time_entry_id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
}
