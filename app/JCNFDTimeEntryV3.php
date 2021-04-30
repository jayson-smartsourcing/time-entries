<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JCNFDTimeEntryV3 extends Model
{
    protected $table = 'jcn_fd_time_entries_v3';
    protected $fillable = [
        'billable',
        'note',
        'id',
        'timer_running',
        'agent_id',
        'ticket_id',
        'time_spent',
        'executed_at',
        'start_time',
        'created_at',
        'update_at',
        'is_latest'
    ];

    public function bulkInsert($data){
        return DB::table('jcn_fd_time_entries_v3')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }

    public function bulkDeletePreviousMonth($date_after,$date_on_or_before) {
        return DB::table('jcn_fd_time_entries_v3')->whereDate('executed_at', '>', $date_after)->whereDate('executed_at', '<=', $date_on_or_before)->Where('is_latest', '=', '0')->delete();//-
    }

    public function bulkUpdateByNewInsert() {
        return DB::table('jcn_fd_time_entries_v3')->where('is_latest', '=', '1')->update(array('is_latest' => 0));
    }
}
