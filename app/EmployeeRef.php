<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeRef extends Model
{   protected $table = 'EmployeeRef';
    protected $fillable = [
        'STATUS',
        'FIRST NAME',
        'LAST NAME',
        'SYSTEM NAME',
        'MSA NO',
        'ACCOUNT',
        'TEAM',
        'SAL EMP ID',
        'EMAIL ADDRESS',
        'JOB TITLE',
        'SKYPE EMAIL',
        'HIRE DATE',
        'DATE OF BIRTH',
        'ADDRESS',
        'CONTACT NUMBER',
        'Effective StartDate',
        'SYSTEM ID'
    ];

    public function insert($data) {
        return static::create($data);
    }

    public function getEmployeeData($agent_id) {
        return static::where("SYSTEM ID",$agent_id)->first();
    }

    public function updateSystemIdByName($data) {
        return static::where("SYSTEM NAME",$data["SYSTEM NAME"])->update($data);
    }


}
