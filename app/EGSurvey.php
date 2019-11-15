<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EGSurvey extends Model
{
    protected $table = 'eg_fd_survey';
    protected $fillable = [
       'id',
       'title',
       'active',
       'created_at',
       'updated_at'
       
    ]; 

    public function bulkInsert($data){
        return DB::table('eg_fd_survey')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDelete($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
}
