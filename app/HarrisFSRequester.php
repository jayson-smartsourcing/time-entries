<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HarrisFSRequester extends Model
{
    protected $table = 'harris_fs_requesters';
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
        return DB::table('harris_fs_requesters')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTimeEntryId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }

    public function addAgentsToContacts($table_name) {
        $values = [$table_name];
        DB::insert('EXEC insert_fs_agents_to_requesters ?', $values);
    }

    //$ids_to_delete must be array
    public function bulkDeleteByContactId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }
}
