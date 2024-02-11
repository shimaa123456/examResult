<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Login;

use App\Http\Controllers\LoginController;

use Illuminate\Support\Facades\Session;
class ResultController extends Controller
{
    //
    public function index() {
        // where teacher logged id
      //  $teacherId = 1;

      $userFullname = Session::get('userFullName');

      $teacher= DB::select("SELECT id FROM teachers WHERE fullName = '$userFullname'");
      $teacherId=$teacher[0]->id;


        $materialsOFteacher = DB::select("SELECT `teachers`.`id`, `teachers`.`fullName`, `materials`.`id` AS `materialID`, `materials`.`materialName` FROM `materialtoteachers`, `materials`, `teachers` WHERE `teachers`.`id` = `materialtoteachers`.`teacherId` AND `materials`.`id` = `materialtoteachers`.`materialId` AND `materials`.`status` = 0 AND `teachers`.`id` = 1;");

        return view("result.index", compact('materialsOFteacher'));

    }

    public function degree($id) {
        // validation for authorization

        $userFullname = Session::get('userFullName');

        $teacher= DB::select("SELECT id FROM teachers WHERE fullName = '$userFullname'");
        $teacherId=$teacher[0]->id;
       $assignedto = DB::select("SELECT * FROM materialtoteachers WHERE teacherId = $teacherId AND materialId = $id");
       if(count($assignedto) == 0) {

        return redirect('/')->with('message','you must assign this material !!!');;
       }
       else{

        $students = DB::select("SELECT `students`.`fullName`, `students`.`id` AS `studentID`, `materials`.`id` AS `materialID`, `materials`.`materialName` FROM `students`, `materials`, `grades` WHERE `students`.`gradeId` = `grades`.`id` AND `materials`.`gradeId` = `grades`.`id` AND `materials`.`id` = $id ORDER BY `students`.`fullName`;");

        foreach($students as $onStudent){
            $studentId = $onStudent->studentID;
            $oneResult = DB::select("SELECT * FROM results WHERE studentId = $studentId AND materialId = $id");
            if(count($oneResult) == 0) {
                DB::insert("INSERT INTO `results` (`studentId`, `materialId`) VALUES ($studentId, $id)");
            }
        }

        $results = DB::select("SELECT `students`.`fullName`, `results`.`degree` FROM `students`, `results` WHERE `students`.`id`=`results`.`studentId` AND `results`.`materialId`=$id ORDER BY `students`.`fullName`");

        return view("result.degree", compact('students', 'results', 'id'));
    }
    }

    public function storeResult(Request $request)
    {

          // validation for authorization

        $request->validate([
            'studentId' => 'required',
            'degree' => 'required',
            'materialId' => 'required',
        ]);

        // validation for authorization

        ///////////////////////////////
        // teacher id

        $userFullname = Session::get('userFullName');

        $teacher= DB::select("SELECT id FROM teachers WHERE fullName = '$userFullname'");
        $teacherId=$teacher[0]->id;

        $materialId = $request->materialId;
        $assignedto = DB::select("SELECT * FROM materialtoteachers WHERE teacherId = $teacherId AND materialId = $materialId");
        if(count($assignedto) == 0) {

         return redirect('/')->with('message','you must assign this material !!!');;
        }
        else{
        $studentId = $request->studentId;
        $degree = $request->degree;
        $materialId = $request->materialId;

        DB::update("UPDATE results SET degree=$degree WHERE studentId=$studentId AND materialId=$materialId");

        return redirect()->back();
        }
    }
}