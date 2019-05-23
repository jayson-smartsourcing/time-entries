<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HarrisSalesFDTimeEntry extends Model
{
    protected $table = 'harris_sales_fd_time_entries';
    protected $fillable = [
        'id',
        'attendance_id',
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
        return DB::table('harris_sales_fd_time_entries')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTimeEntryId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
}
