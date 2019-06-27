<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon as Carbon;
use App\Http\Requests\EmployeeRateRequest;
use Mail;
use App\Mail\MonthEndRating;
use Illuminate\Support\Facades\Config as Config;
use Maatwebsite\Excel\Facades\Excel;

use App\EmployeeSatisfactory as EmployeeSatisfactory;
use App\EmployeeRef as EmployeeRef;

class EmployeeSatisfactoryController extends Controller
{   
    public function __construct(
        EmployeeSatisfactory $emp_satisfactory_rate,
        EmployeeRef $employee_ref
    )
    {  
        $this->emp_satisfactory_rate = $emp_satisfactory_rate;
        $this->employee_ref = $employee_ref;
    }

    public function addEmployeeRatings(EmployeeRateRequest $request) {
        
       $data = Input::only("email","rate","reason","employee_id","id");
       $data["employee_id"] = $this->en_de_id($data["id"],'d');
       $year = Carbon::now()->format("Y");
       $month = Carbon::now()->format("m");
       $data["year"] = $year;
       $data["month"] =$month;
       $return  = $this->emp_satisfactory_rate::create($data);
       return response()->json(['success'=> true,'data'=>$return], 200);
    }

    public function emailToEmployee() {
        //$employees = $this->employee_ref->getAllEmployee();
        $employees = array(array("first_name" => "Jayson", "id" => 1, "email" => "jayson@startsmartsourcing.com"));
        
        // foreach($employees as $employee) {
        //     $id = $this->en_de_id($employee["id"]);
        //     $url = Config::get("app.url_live");
        //     $data["url"] = $url."/poll/view/".$id;
        //     $data["first_name"] = $employee["FIRST NAME"];
        //     $data["email"] = $employee["EMAIL ADDRESS"];
        //     Mail::to($employee["email"])
        //     ->send(new MonthEndRating($data));
        // }

        foreach($employees as $employee) {
            $id = $this->en_de_id($employee["id"]);
            $url = Config::get("app.url_live");
            $employee["url"] = $url."/poll/view/".$id;
            Mail::to($employee["email"])
                ->send(new MonthEndRating($employee));
        }
        
        return response()->json(['success'=> true], 200);
        
    }
    
    public function checkCurrentMonthRate($id) {
        $id = $this->en_de_id($id,'d');
        $year = Carbon::now()->format("Y");
        $month = Carbon::now()->format("m");
        $data["employee_id"] = 122;
        $data["year"] = $year;
        $data["month"] = $month;

        $return = $this->emp_satisfactory_rate::where($data)->get();
        $count = count($return);
        
        if($count) {
            return response()->json(['success'=> true,'message'=> "done rating"], 200);
        } else {
            return response()->json(['success'=> true,'message'=>'not done rating' ], 200);
        }       
        
    }

    public function importFromCSV() {
        $path = storage_path("rating-may-2019.xls");
        $excel = new Excel();
        $file = Excel::load($path,'UTF-8')->get();
        $key = 0;

        foreach($file as $key => $value) {
            $employee = $this->employee_ref->getEmployeeByEmail($value->email);
            $rating["employee_id"] = $employee["id"];
            $rating["email"] = $value->email;
            $rating["rate"] = $value->rate;
            $rating["reason"] = $value->reason;
            $rating["month"] = "05";
            $rating["year"] = "2019";

            $this->emp_satisfactory_rate::create($rating);
        }

      
        return response()->json(['success'=> true,'count' => $key], 200);
    }



  
}
