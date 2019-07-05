<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\BossJobResume as BossJobResume;

class ImportBossJobToDBController extends Controller
{
    public function __construct(
        BossJobResume $boss_job_resume
    )
    {  
        $this->boss_job_resume = $boss_job_resume;
    }

    public function insertData() {
    
            for ($i=1; $i < 4; $i++) { 
                $file = "soggen".$i;
                $path = storage_path() . "/json/${file}.json";
                $files = json_decode(file_get_contents($path), true); 
                foreach($files["data"]["job_seekers"] as $key => $value) {
    
                    $final_data["boss_id"] = $value["id"];
                    $final_data["first_name"] = $value["first_name"];
                    $final_data["last_name"] = $value["last_name"];
                    $final_data["phone_num"] = $value["phone_num"];
                    $final_data["email"] = $value["email"];
                    $final_data["year_experience"] = $value["xp_lvl"];
                    $final_data["location"] = $value["location"];
                    $final_data["resume_link"] = count($value["resumes"]) > 0 ? $value["resumes"][0]["url"] : " ";
                    $final_data["gender"] = $value["gender"];
                    $final_data["birthdate"] = $value["birthdate"];
                    $final_data["others_data"] = json_encode($value);
                    $final_data["job_type"] = $value["latest_preference"]["job_type"];
                    $final_data["salary_range_to"] = $value["latest_preference"]["salary_range_to"];
                    $final_data["salary_range_from"] = $value["latest_preference"]["salary_range_from"];
                    $final_data["industry"] = $value["latest_preference"]["industry"];
                    $final_data["job_title"] = $value["latest_preference"]["job_title"];
                    $final_data["created_at"] = $value["created_at"];
                    $final_data["updated_at"] = $value["updated_at"];
                    $this->boss_job_resume->insert($final_data);
                }
            }
           
        
        return response()->json(['success'=> true], 200);
    }
    
}
