<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class flights extends Model
{
    protected $table = 'flights';
    protected $fillable = [
       'id',
       'name',
       'airline',
       'created_at',
       'updated_at'
       
    ];

    public function getDates()
    {
        return [];
    }

    public function insert($data) {
        return static::create($data);
    }

    public function getAll() {
        return static::all();
    }

}
