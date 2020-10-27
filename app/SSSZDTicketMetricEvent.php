<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSSZDTicketMetricEvent extends Model
{
    protected $table = 'sss_zd_ticket_metric_events';
    protected $fillable = [
       'id',
       'ticket_id',
       'metric',
       'instance_id',
       'type',
       'time',
       'deleted',
       'status_calendar',
       'status_business',
       'sla_target_min',
       'sla_business_hours',
       'sla_policy_id',
       'sla_policy_title',
       'sla_policy_description'
    ];

    public function bulkInsert($data){
        return DB::table('sss_zd_ticket_metric_events')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

}
