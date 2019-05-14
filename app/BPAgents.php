<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BPAgents extends Model
{
    protected $table = 'BPAgents';
    protected $fillable = [
       'id',
       'first_name',
       'last_name',
       'created_at',
       'updated_at'
       
    ];

    public function bulkInsert($data){
        return DB::table('BPAgents')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteAgentId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function getAgentById($id) {
        return static::where('id',$id)->first();
    }
}
