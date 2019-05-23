<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HarrisSalesFDContact extends Model
{
    protected $table = 'harris_sales_fd_contacts';
    protected $fillable = [
       'id',
       'name',
       'created_at',
       'updated_at'
       
    ];

    public function bulkInsert($data){
        return DB::table('harris_sales_fd_contacts')->insert($data);
    }
    //$ids_to_delete must be array
    public function bulkDeleteAgentId($ids_to_delete){
        return static::whereIn('id',$ids_to_delete)->delete();
    }

    public function getAgentById($id) {
        return static::where('id',$id)->first();
    }
}
