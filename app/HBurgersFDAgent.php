<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HBurgersFDAgent extends Model
{
    protected $table = 'hburgers_fd_agents';
    protected $fillable = [
       'id',
       'first_name',
       'last_name',
       'created_at',
       'updated_at'
       
    ];

    public function bulkInsert($data){
        return DB::table('hburgers_fd_agents')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteAgentId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function getAgentById($id) {
        return static::where('id',$id)->first();
    }

    public function truncateTable() {
        return static::truncate();
     }
}
