<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EGSurveyQuestion extends Model
{
    protected $table = 'eg_fd_survey_question';
    protected $fillable = [
       'id',
       'survey_id',
       'label',
       'accepted_ratings',
       'created_at',
       'updated_at'
       
    ]; 

    public function bulkInsert($data){
        return DB::table('eg_fd_survey_question')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDelete($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function truncateTable() {
        return static::truncate();
    }
}
