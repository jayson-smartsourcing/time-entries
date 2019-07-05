<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BossJobResume extends Model
{
    protected $table = 'boss_jobs_resume';
    protected $fillable = [
       'id',
       'boss_id',
       'first_name',
       'last_name',
       'phone_num',
       'email',
       'year_experience',
       'location',
       'resume_link',
        'gender',
        'birthdate',
        'job_type',
        'salary_range_to',
        'salary_range_from',
        'job_title',
        'industry',
        'others_data',
        'created_at',
        'updated_at'
    ];

    public function bulkInsert($data){
        return DB::table('boss_jobs_resume')->insert($data);
    }
    
}
