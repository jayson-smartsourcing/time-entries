<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TagflixFDCompany extends Model
{
    protected $table = 'tagflix_fd_companies';
    protected $fillable = [
       'id',
       'name',
       'description',
       'unassigned_for',
       'business_hours_id',
       'escalate_to',
       'agent_ids',
       'auto_ticket_assign',
       'created_at',
       'updated_at'
    ];

    public function bulkInsert($data){
        return DB::table('tagflix_fd_companies')->insert($data);
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
