<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeRefNew extends Model
{
    protected $table = 'employee_ref';
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
        'SYSTEM ID',
        'sprout_name',
        'sprout_id'
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

    public function getAllEmployee() {
        return static::all();
    }

    public function getEmployeeByEmail($email) {
        return static::where("EMAIL ADDRESS",$email)->first();
    }

    public function updateSproutName($data) {
        return static::where("sprout_id",$data["sprout_id"])->update($data);
    }

    public function findSprountID($sprout_id) {
        return static::where("sprout_id",$sprout_id)->first();
    }

}
