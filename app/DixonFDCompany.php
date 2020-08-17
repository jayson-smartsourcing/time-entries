<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DixonFDCompany extends Model
{
    protected $table = 'dixon_fd_companies';

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];
 
    public function bulkInsert($data){
        return DB::table('dixon_fd_companies')->insert($data);
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

    public function deleteDuplicates($table_name) {
        $values = [$table_name];
        DB::insert('EXEC delete_duplicate ?', $values);
    }
}

