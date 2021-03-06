<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BeerCartelFDTimeEntryV2 extends Model
{
    protected $table = 'beer_cartel_fd_time_entry_v2';
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
        'update_at'
    ];

    public function bulkInsert($data){
        return DB::table('beer_cartel_fd_time_entry_v2')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
    
    // public function updateLatestFdTickets($table_name) {
    //     $values = [$table_name];
    //     DB::insert('EXEC update_fd_latest_tickets ?', $values);
    // }

    // public function updateAllFdTickets($table_name) {
    //     $values = [$table_name];
    //     DB::insert('EXEC update_fd_all_tickets ?', $values);
    // }
}
