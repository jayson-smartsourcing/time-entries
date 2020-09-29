<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSZDOrganization extends Model
{
    protected $table = 'ss_zd_organizations';
    protected $fillable = [
       'url',
       'id',
       'name',
       'shared_tickets',
       'shared_comments',
       'external_id',
       'created_at',
       'updated_at',
       'domain_names',
       'details',
       'notes',
       'group_id',
       'tags',
       'organization_fields_company_name',
       'organization_fields_domain_name'
    ];

    public function bulkInsert($data){
        return DB::table('ss_zd_organizations')->insert($data);
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
