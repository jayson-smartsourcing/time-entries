<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSZDUser extends Model
{
    protected $table = 'ss_zd_users';
    protected $fillable = [
       'id',
       'url',
       'name',
       'email',
       'created_at',
       'updated_at',
       'time_zone',
       'iana_time_zone',
       'phone',
       'shared_phone_number',
       'locale_id',
       'locale',
       'organization_id',
       'role',
       'verified',
       'external_id',
       'tags',
       'alias',
       'active',
       'shared',
       'shared_agent',
       'last_login_at',
       'two_factor_auth_enabled',
       'signature',
       'details',
       'notes',
       'role_type',
       'custom_role_id',
       'moderator',
       'ticket_restriction',
       'only_private_comments',
       'restricted_agent',
       'suspended',
       'chat_only',
       'default_group_id',
       'report_csv',
       'user_fields_email_address',
       'user_fields_full_name',
       'user_fields_phone_number'
    ];

    public function bulkInsert($data){
        return DB::table('ss_zd_users')->insert($data);
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
