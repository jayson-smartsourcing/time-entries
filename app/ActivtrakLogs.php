<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivtrakLogs extends Model
{
    protected $table = 'activtrak_logs';
    protected $fillable = [
       'id',
       'user',
       'current_date',
       'total_duration_per_day',
       'productive_per_day',
       'un_productive_per_day',
       'undefined_per_day',
       'created_at',
       'updated_at'
    ];

    public function bulkInsert($data){
        return DB::table('activtrak_logs')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteAgentId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function getDataBydate($current_date,$user) {
        return static::where('current_date',$current_date)->where("user",$user)->first();
    }

    public function truncateTable() {
        return static::truncate();
    }
}
