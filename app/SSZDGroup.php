<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSZDGroup extends Model
{
    protected $table = 'ss_zd_groups';
    protected $fillable = [
       'url',
       'id',
       'name',
       'description',
       'default',
       'deleted',
       'created_at',
       'updated_at'
    ];

    public function bulkInsert($data){
        return DB::table('ss_zd_groups')->insert($data);
    }
   
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    // public function deleteById($id_to_delete){
    //     return DB::delete('delete from ss_zd_ticket_metrics where id = '.$id_to_delete);
    // }

    public function truncateTable() {
        return static::truncate();
    }
    
    // public function updateLatestFdTickets($table_name) {
    //     $values = [$table_name];
    //     DB::insert('EXEC update_zd_latest_ticket_metrics ?', $values);
    // }

    // public function updateAllFdTickets($table_name) {
    //     $values = [$table_name];
    //     DB::insert('EXEC update_zd_all_ticket_metrics ?', $values);
    // }
}
