<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JCSFDTicket extends Model
{
    protected $table = 'jcs_fd_tickets';
    protected $fillable = [
       'id',
       'unique_id',
       'resolution_status',
       'task',
       'process',
       'subprocess',
       'bill',
       'resolved_at',
       'closed_at',
       'cc_emails',
       'fwd_emails',
       'reply_cc_emails',
       'fr_escalated',
       'spam',
       'priority',
       'requester_id',
       'source',
       'status',
       'subject',
       'to_emails',
       'department_id',
       'group_id',
       'agent_id',
       'type',
       'due_by',
       'fr_due_by',
       'is_escalated',
       'channel',
       'created_at',
       'updated_at',
       'attendance_id'
    ];

    public function getDates()
    {
        return [];
    }

    public function bulkInsert($data){
        return DB::table('jcs_fd_tickets')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTicketExportId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }

    public function getAllMissingTicket() {
        return DB::table('jcn_fd_missing_tickets')->get();
    }

    public function deleteTicket($id) {
        return static::where("id",$id)->delete();
    }

    public function deleteMissingTickets($id) {
        return DB::table('jcn_fd_missing_tickets')->where("id",$id)->delete();
    }

    public function bulkDeleteMissingTicket($ids_to_delete) {
        return DB::table('jcn_fd_missing_tickets')->whereIn('id',$ids_to_delete)->delete();
    }

    public function getById($id) {
        return static::where('id',$id)->first()->toArray();
    }
}
