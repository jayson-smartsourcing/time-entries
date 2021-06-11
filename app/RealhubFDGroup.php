<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RealhubFDGroup extends Model
{
    protected $table = 'realhub_fd_groups';
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
        return DB::table('realhub_fd_groups')->insert($data);
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

    public function updateLatestFdReportRef($table_name) {
        $values = [$table_name];
        DB::insert('EXEC update_latest_fd_reporting_ref ?', $values);
    }

    public function deleteDuplicates($table_name) {
        $values = [$table_name];
        DB::insert('EXEC delete_duplicate ?', $values);
    }
}
