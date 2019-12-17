<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EGSatisfactoryRating extends Model
{
    protected $table = 'eg_fd_satisfactory_rating';
    protected $fillable = [
       'id',
       'survey_id',
       'rating',
       'created_at',
       'updated_at',
       'response_id'
    ]; 

    public function bulkInsert($data){
        return DB::table('eg_fd_satisfactory_rating')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDelete($ids_to_delete){
        return static::whereIn('response_id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
}
