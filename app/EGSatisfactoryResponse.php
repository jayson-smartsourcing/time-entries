<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EGSatisfactoryResponse extends Model
{
    protected $table = 'eg_fd_satisfactory_response';
    protected $fillable = [
       'id',
       'survey_id',
       'user_id',
       'agent_id',
       'feedback',
       'group_id',
       'ratings',
       'ticket_id',
       'created_at',
       'updated_at',
       'updated_at_id'
    ]; 

    public function bulkInsert($data){
        return DB::table('eg_fd_satisfactory_response')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDelete($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }

    public function addUpdateAtID($table_name) {
        $values = [$table_name];
        DB::insert('EXEC update_fd_latest_satisfactory_response ?', $values);
    }
}
