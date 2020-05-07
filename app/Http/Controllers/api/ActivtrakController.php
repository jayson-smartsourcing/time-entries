<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon as Carbon;

use App\ActivtrakLogs as ActivtrakLogs;
use Maatwebsite\Excel\Facades\Excel;
use App\EmployeeRef as EmployeeRef;


class ActivtrakController extends Controller
{   
    public function __construct(
        ActivtrakLogs $act_logs,
        EmployeeRef $ref_new
    )
    {  
        $this->act_logs = $act_logs;
        $this->ref_new = $ref_new;
    }  

    public function importATlogs() {
       $data = Input::only("file");
       $excel = new Excel();
       $file = Excel::load($data["file"],'UTF-8')->get();
       $i = 0;
       $final_data = [];
       $len = count($file);
       $missing = [];

     

       foreach($file as $key => $value) {
         $insert = [];
         $insert["user"] = $value->user;
         $insert["current_date"] = Carbon::parse($value->current_date)->format("Y-m-d");
         $insert["total_duration_per_day"] = $value->total_duration_per_day;
         $insert["productive_per_day"] = $value->productive_per_day;
         $insert["un_productive_per_day"] = $value->un_productive_per_day;
         $insert["undefined_per_day"] = $value->undefined_per_day;
        $return_data = $this->ref_new->getSproutIdByName($insert["user"]);

        if($return_data) {
            $insert["attendance_id"] = Carbon::parse($insert["current_date"])->format("Ymd").$return_data["sprout_id"];
        } else {
            $insert["attendance_id"] = "";
            $missing[] = $insert;
        }

        $check_date = $this->act_logs->getDataBydate($insert["current_date"],$insert["user"]);
        
        if(!$check_date) {
            $final_data[] = $insert; 
        }
                  
        if( ($len - 1) > $key && count($final_data) == 50) {
            $this->act_logs->bulkInsert($final_data);
            $final_data = [];
        } 

        if( ($len - 1) == $key) {
            $this->act_logs->bulkInsert($final_data);
            $final_data = [];
        }
        
       }

       return response()->json(['success'=> true,'missing' => $missing], 200);

    }

    public function updateEmpRef() {
        $data = Input::only("file");
        $excel = new Excel();
        $file = Excel::load($data["file"],'UTF-8')->get();
        $i = 0;
        $final_data = [];
        $len = count($file);
        $missing = [];

       foreach($file as $key => $value) {
        
        $update = [];
        $update["sprout_id"] = $value["employee_id"];
        $update["sprout_name"] = $value["employee_name"];

        $find = $this->ref_new->findSprountID($update["sprout_id"]);

        if($find) {
            $this->ref_new->updateSproutName($update);
        } else {
            $missing[] = $update;
        }

            
      }
      return response()->json(['success'=> true,'count' => $missing], 200);
    }


    public function importATlogsCSV() {
        $data = Input::only("file");
        $excel = new Excel();
        $file = Excel::load($data["file"],'UTF-8')->get();
        $i = 0;
        $final_data = [];
        $len = count($file);
        $missing = [];

      
        
 
        foreach($file as $key => $value) {     
            if($value->user == "") {
                continue;
            }
            $data = [];
            $insert["user"] = $value->user;
            $insert["current_date"] = Carbon::parse($value->date)->format("Y-m-d");
            $insert["total_duration_per_day"] = $value->total_time/3600;
            $insert["productive_per_day"] = $value->productive/3600;
            $insert["un_productive_per_day"] = $value->unproductive/3600;
            $insert["undefined_per_day"] = $value->undefined/3600;
            $insert["passive_per_day"] = $value->passive_time/3600;
            $insert["groups"] = $value->group;

            $return_data = $this->ref_new->getSproutIdByName($insert["user"]);
            $data["user"] = $value->user;
            $data["groups"] = $value->groups;
            $update = $this->act_logs->updateData($data);
    
            if($return_data) {
                $insert["attendance_id"] = Carbon::parse($insert["current_date"])->format("Ymd").$return_data["sprout_id"];
            } else {
                $insert["attendance_id"] = "";
                $missing[] = $insert;
            }
    
            $check_date = $this->act_logs->getDataBydate($insert["current_date"],$insert["user"]);
            
            if(!$check_date) {
                $final_data[] = $insert; 
            }
                    
            if( ($len - 1) > $key && count($final_data) == 50) {
                $this->act_logs->bulkInsert($final_data);
                $final_data = [];
            } 
    
            if( ($len - 1) == $key) {
                $this->act_logs->bulkInsert($final_data);
                $final_data = [];
            }
            
        }
 
        return response()->json(['success'=> true,'missing' => $missing], 200);
    }    
 
}
