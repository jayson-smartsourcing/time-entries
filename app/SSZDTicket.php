<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSZDTicket extends Model
{
    protected $table = 'ss_zd_tickets';
    protected $fillable = [
       'url',
       'id',
       'external_id',
       'channel_zendesk',
       'source_from_address',
       'source_from_name',
       'source_to_address',
       'source_to_name',
       'source_rel',
       'created_at',
       'updated_at',
       'type',
       'subject',
       'raw_subject',
       'description',
       'priority',
       'status',
       'recipient',
       'requester_id',
       'submitter_id',
       'assignee_id',
       'organization_id',
       'group_id',
       'collaborator_ids',
       //'follower_ids',
       'email_cc_ids',
       //'forum_topic_id',
       'problem_id',
       'has_incidents',
       //'is_public',
       'due_at',
       //'tags',
       'sub_process',
       'task',
       'total_time_spent_sec',
       'channel',
       'time_spent_last_update_sec',
       'turnaround_time',
       'task_count',
       'process',
       'satisfaction_rating_score',
       'satisfaction_rating_id',
       'satisfaction_rating_comment',
       'satisfaction_rating_reason',
       'satisfaction_rating_reason_id'
    ];

    public function bulkInsert($data){
        return DB::table('ss_zd_tickets')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
    
    public function updateLatestFdTickets($table_name) {
        $values = [$table_name];
        DB::insert('EXEC update_zd_latest_tickets ?', $values);
    }

    public function updateAllFdTickets($table_name) {
        $values = [$table_name];
        DB::insert('EXEC update_zd_all_tickets ?', $values);
    }
}
