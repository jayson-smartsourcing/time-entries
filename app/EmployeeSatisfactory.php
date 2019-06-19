<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeSatisfactory extends Model
{
    protected $table = 'employee_satisfactory';
    protected $fillable = [
       'employee_id',
       'email',
       'rate',
       'reason',
       'month',
       'year',
       'created_at',
       'updated_at',
       'deletaed_at'
    ];
}
