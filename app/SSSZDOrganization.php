<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SSSZDOrganization extends Model
{
    protected $table = 'sss_zd_organizations';
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
       'tags'
    //    'organization_fields_company_name',
    //    'organization_fields_domain_name'
    ];

    public function bulkInsert($data){
        return DB::table('sss_zd_organizations')->insert($data);
    }
   
    //$ids_to_delete must be array
    public function bulkDeleteById($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
    

}
