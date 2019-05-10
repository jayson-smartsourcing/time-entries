<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BPNotFoundId extends Model
{
    protected $table = 'BPNotFoundIds';
    protected $fillable = [
       'id',
       'entity',
       'ticket_id',
       'created_at',
       'updated_at',
       'deleted_at'
    ];

    public function insert($data){
        return static::create($data);
    }
    public function bulkInsert($data){
        return DB::table('BPNotFoundIds')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteByTimeEntryId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }
}
