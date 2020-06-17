<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon as Carbon;
use Validator;

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
            $insert["groups"] = $value->groups;

            $return_data = $this->ref_new->getSproutIdByName($insert["user"]);
            $data["user"] = $value->user;
            $data["groups"] = $value->groups;
           // $update = $this->act_logs->updateData($data);
    
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
            
       // return response()->json(['success'=> true,'missing' => $missing], 200);
        return back()->with('message', 'Activtrak CSV File Imported Successfully')->with(['name' => 'activtrak-logs']);

        
    }

    
    /*
    ** Updates attendance ID of missing logs
    */
    public function updateAttendanceID(Request $request){
        
        $validator = Validator::make($request->all(), [
            'sprout_name_id' => 'required',
            'current_date' => 'required',
            'user' => 'required'
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors()->toArray();
          
            foreach($errors as $key => $value) {
                $errors[$key] = $value[0];
            }
            return response()->json(['error'=>$errors], 200);            
        }

        $final_data=[];

        //get input
        $input = $request->only('sprout_name_id', 'user', 'current_date');
        $user = $input['user'];
       
        //generate attendance_id.
        $sprout_name_id = $input['sprout_name_id'];

        //remove "-"  from sprout_name_id
        $re = '/\w+(?:[- ]\w+)*/'; 
        preg_match_all($re, $sprout_name_id, $result);

        $sprout_name = $result[0][0];
        $id = $result[0][1];

        $current_date = $input['current_date'];

        //combine current_date and id
        $attendance_id =  Carbon::parse($current_date)->format("Ymd").$id;

        //get latest group
        $groups =  $this->act_logs->getLatestGroup($sprout_name);
        $groups = $groups->groups;

         //data to be updated
        $old_user = $input['user'];
        $data['user'] = $sprout_name;
        $data['attendance_id'] = $attendance_id;
        $data['groups'] = $groups;
        $data['current_date'] = $current_date;

        $final_data = $data;

        $this->act_logs->updateAttendanceID($final_data, $old_user);

        return back()->with('message', 'Attendance ID Updated');
        // return response()->json(['success'=> true,'data' => $final_data], 200);
    }


    /*
    ** Displays missing logs at the bottom
    */
    public function displayAllMissingLogs(){

        //get all logs, display from latest current_date
        $all_logs = ActivtrakLogs::orderBy('current_date', 'DESC')->get();

        //get all employee sprout_name and id
        $all_emp = EmployeeRef::orderBy('sprout_name', 'ASC')->get();

        $missing = [];
        $emp_sprout = array();

        //remove blank sprout_name and blank sprout_id
        foreach($all_emp as $emp){
            if( (!is_null($emp->sprout_name) || !empty($emp->sprout_name)) && (!is_null($emp->sprout_id) || !empty($emp->sprout_id)) ){

                //displays "Employee_Name - Sprout ID" eg. "Sam Smith - 2256"
                $name_id = $emp->sprout_name." - ".$emp->sprout_id;
                $emp_sprout[] = $name_id;
            }
        }

        //check all logs if no attendance_id
        foreach($all_logs as $logs){
            if( is_null($logs->attendance_id) || empty($logs->attendance_id) ){
                $log = array();

                $log['user'] = $logs->user;
                $log['current_date'] = $logs->current_date;
                $log['groups'] = $logs->groups;

                //all missing logs
                $missing[] = $log;
            }
        }

        //pass to view missing logs and employee sprout name and id
        return view('activtrak-working-hours', compact('missing', 'emp_sprout'));

    }

    public function deleteLog($user, $currdate){     

        $this->act_logs->deleteLog($user, $currdate);
        // return response()->json(['success'=> true], 200);

        return response()->json(['success'=> true, 'message'=>"Log Deleted Successfully"], 200);

    }
 
}
