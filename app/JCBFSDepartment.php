<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JCBFSDepartment extends Model
{
    protected $table = 'jcb_fs_departments';
    protected $fillable = [
       'id',
       'name',
       'description',
       'head_user_id',
       'prime_user_id',
       'domains',
       'custom_fields',
       'created_at',
       'updated_at',
    ];

    public function bulkInsert($data){
        return DB::table('jcb_fs_departments')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTimeEntryId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function getDataById($id) {
        return static::where('id',$id)->first();
    }

    public function truncateTable() {
        return static::truncate();
    }
}
