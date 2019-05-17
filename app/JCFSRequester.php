<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JCFSRequester extends Model
{
    protected $table = 'jc_fs_requesters';
    protected $fillable = [
       'id',
       'first_name',
       'last_name',
       'job_title',
       'primary_email',
       'secondary_emails',
       'work_phone_number',
       'mobile_phone_number',
       'department_ids',
       'created_at',
       'updated_at',
       'deleted_at'
    ];

    public function bulkInsert($data){
        return DB::table('jc_fs_requesters')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTimeEntryId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }
    
}
