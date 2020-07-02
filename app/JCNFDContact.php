<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JCNFDContact extends Model
{
    protected $table = 'jcn_fd_contacts';
    protected $fillable = [
       'id',
       'name',
       'created_at',
       'updated_at'
       
    ];
    public function bulkInsert($data){
        return DB::table('jcn_fd_contacts')->insert($data);
    }

    public function insert($data) {
        return DB::table('jcn_fd_contacts')->create($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteAgentId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function getAgentById($id) {
        return static::where('id',$id)->first();
    }

    public function truncateTable() {
        return static::truncate();
    }

    public function addAgentsToContacts($table_name) {
        $values = [$table_name];
        DB::insert('EXEC insert_fd_agents_to_contacts ?', $values);
    }

    //$ids_to_delete must be array
    public function bulkDeleteByContactId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function deleteDuplicates($table_name) {
        $values = [$table_name];
        DB::insert('EXEC delete_duplicate_contacts ?', $values);
    }

}
