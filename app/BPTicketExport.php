<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BPTicketExport extends Model
{
    protected $table = 'BPTicketExport_API';
    protected $fillable = [
       'id',
       'unique_id',
       'resolution_status',
       'category',
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
       'attendance_id',
       'deleted'
    ];

    public function bulkInsert($data){
        return DB::table('BPTicketExport_API')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTicketExportId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function runStoredProcedure() {
        return DB::select('EXEC Create_BPTicketExport');
    }
}
