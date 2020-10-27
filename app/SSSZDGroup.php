<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSSZDGroup extends Model
{
    protected $table = 'sss_zd_groups';
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
        return DB::table('sss_zd_groups')->insert($data);
    }
   
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }


    public function truncateTable() {
        return static::truncate();
    }

}
