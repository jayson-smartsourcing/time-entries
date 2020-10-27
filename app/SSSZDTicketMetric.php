<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSSZDTicketMetric extends Model
{
    protected $table = 'sss_zd_ticket_metrics';
    protected $fillable = [
       'url',
       'id',
       'ticket_id',
       'created_at',
       'updated_at',
       'group_stations',
       'assignee_stations',
       'reopens',
       'replies',
       'assignee_updated_at',
       'requester_updated_at',
       'status_updated_at',
       'initially_assigned_at',
       'assigned_at',
       'solved_at',
       'latest_comment_added_at',
       'reply_time_in_minutes_calendar',
       'reply_time_in_minutes_business',
       'first_resolution_time_in_minutes_calendar',
       'first_resolution_time_in_minutes_business',
       'full_resolution_time_in_minutes_calendar',
       'full_resolution_time_in_minutes_business',
       'agent_wait_time_in_minutes_calendar',
       'agent_wait_time_in_minutes_business',
       'requester_wait_time_in_minutes_calendar',
       'requester_wait_time_in_minutes_business',
       'on_hold_time_in_minutes_calendar',
       'on_hold_time_in_minutes_business'
    ];

    public function bulkInsert($data){
        return DB::table('sss_zd_ticket_metrics')->insert($data);
    }
   
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

}
