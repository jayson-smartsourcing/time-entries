<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FailedTimeEntries extends Model
{
    protected $table = 'APIFailedTimeEntries';
    protected $fillable = [
        'link',
        'status'
    ];

    public function addData($data) {
        return static::create($data);
    }
}
