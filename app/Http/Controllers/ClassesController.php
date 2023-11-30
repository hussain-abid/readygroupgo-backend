<?php

namespace App\Http\Controllers;

use App\Models\UserClass;
use App\Models\UserClassStudents;
use App\Models\UserSharedClasses;
use Illuminate\Http\Request;
use Validator;

class ClassesController extends Controller
{
    //

    private $user_id;

    public function __construct()
    {
        $this->user_id = get_user_id_from_token();
    }

    private function get_userClasses(){
        $classes=UserClass::where('user_id',$this->user_id)->get();
        return $classes;
    }

    private function is_this_user_class($class_id){

        $result=UserClass::where('id',$class_id)
            ->where('user_id',$this->user_id)
            ->first();

        return $result;
    }


    public function get_classes(){


        $response=(object)[];

        $response->classes=$this->get_userClasses();
        return response()->json($response, 200);

    }
    public function get_single_class($class_id){

        $response=(object)[];
        $valid_class=$this->is_this_user_class($class_id);


        if(!$valid_class)
        {

            $errors=[
                'errors'=>['This Class Doesn\'t belongs to this user']
            ];
            $response->code=400;
            $response->errors=$errors;
            return $response;
        }

        $class_details=UserClass::where('user_id',$this->user_id)
            ->where('id',$class_id)->first();

        $response->class=$class_details;
        return $response;

    }

    private function get_students_helper($class_id)
    {
        $response=(object)[];
        $response->code=200;

        $valid_class=$this->is_this_user_class($class_id);


        if(!$valid_class)
        {

            $errors=[
                'errors'=>['This Class Doesn\'t belongs to this user']
            ];
            $response->code=400;
            $response->errors=$errors;
            return $response;
        }


        $class_details=UserClass::where('user_id',$this->user_id)
            ->where('id',$class_id)->first();

        $students=UserClassStudents::where('class_id',$class_id)->get();


        $response->students=$students;
        $response->class_details=$class_details;

        return $response;
    }
    public function get_students($class_id){

        $response=$this->get_students_helper($class_id);

        if($response->code==400)
            return response()->json($response->errors, $response->code);
        else
            return response()->json($response, $response->code);
    }

    public function add_class(Request $request){

        $response=(object)[];


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if($validator->fails()){
            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];
            return response()->json($errors, 400);
        }


        if($request->has('students'))
        {
            $validator = Validator::make($request->all(), [
                "students"    => "required|array",
                "students.*"  => "required|string|distinct",
            ]);
            if($validator->fails()){
                $errors=[
                    'errors'=>validationErrorMessagesToArray($validator->errors())
                ];
                return response()->json($errors, 400);
            }

        }

        $class=UserClass::create([
            'user_id'=>$this->user_id,
            'name'=>$request->get('name'),
            'shareable_id'=>generateUniqueKey(),
        ]);

        if($request->has('students'))
        {

            $students=$request->get('students');

            foreach ($students as $each_student)
            {
                UserClassStudents::create([
                    'class_id'=>$class->id,
                    'person_name'=>$each_student
                ]);
            }
        }



        $response->message='Class Created';

//        $response->class=$class;
//        $response->class_id=$class->id;

        return response()->json($response, 200);

    }

    public function add_student_details($class_id,Request $request){

        $response=(object)[];

        $default_type='text';

        if($request->has('type')){
            if($request->get('type')=='array')
                $default_type='aray';
        }

        $valid_class=$this->is_this_user_class($class_id);

        if(!$valid_class)
        {
            $errors=[
                'errors'=>['This Class Doesn\'t belongs to this user']
            ];
            return response()->json($errors, 400);
        }

        if($default_type=='text')
        {

            $validator = Validator::make($request->all(), [
                'person_name' => 'required|string',
            ]);
            if($validator->fails()){
                $errors=[
                    'errors'=>validationErrorMessagesToArray($validator->errors())
                ];
                return response()->json($errors, 400);
            }

            $create=[
                'class_id'=>$class_id,
                'person_name'=>$request->get('person_name')
            ];

            if($request->has('attr_1')) $create['attr_1']= $request->get('attr_1');
            if($request->has('attr_2')) $create['attr_2']= $request->get('attr_2');
            if($request->has('attr_3')) $create['attr_3']= $request->get('attr_3');

            UserClassStudents::create($create);

            $response->message='Class Student Added';

        }

        else{
            $validator = Validator::make($request->all(), [
                "students"    => "required|array",
                "students.*"  => "required|string|distinct",
            ]);
            if($validator->fails()){
                $errors=[
                    'errors'=>validationErrorMessagesToArray($validator->errors())
                ];
                return response()->json($errors, 400);
            }
            $all_students=$request->get('students');
            foreach ($all_students as $each_student)
            {
                UserClassStudents::create([
                    'class_id'=>$class_id,
                    'person_name'=>$each_student
                ]);
            }
        }

        $response->class=$this->get_students_helper($class_id);
        return response()->json($response, 200);
    }

    public function delete_class($class_id){

        $response=(object)[];

        $valid_class=$this->is_this_user_class($class_id);

        if(!$valid_class)
        {
            $errors=[
                'errors'=>['This Class Doesn\'t belongs to this user']
            ];
            return response()->json($errors, 400);
        }


        UserClass::where('id',$class_id)->delete();
        UserClassStudents::where('class_id',$class_id)->delete();

        $response->message='Class Deleted';

        return response()->json($response, 200);

    }

    public function update_class($class_id,Request $request){

        $response=(object)[];

        $valid_class=$this->is_this_user_class($class_id);

        if(!$valid_class)
        {
            $errors=[
                'errors'=>['This Class Doesn\'t belongs to this user']
            ];
            return response()->json($errors, 400);
        }


//        $validator = Validator::make($request->all(), [
//            'name' => 'required|string',
//        ]);
//        if($validator->fails()){
//            $errors=[
//                'errors'=>validationErrorMessagesToArray($validator->errors())
//            ];
//            return response()->json($errors, 400);
//        }

        if($request->has('attr1')) $update['attr1']= $request->get('attr1');
        if($request->has('attr2')) $update['attr2']= $request->get('attr2');
        if($request->has('attr3')) $update['attr3']= $request->get('attr3');
        if($request->has('name')) $update['name']= $request->get('name');

        UserClass::where('id',$class_id)->update($update);

        $response->message='Class Updated';

        return response()->json($response, 200);

    }

    public function update_class_student($student_id,Request $request){

        $response=(object)[];

        $valid_class=UserClassStudents::where('user_class_students.id',$student_id)
            ->leftJoin('user_classes','class_id','user_classes.id')
            ->where('user_id',$this->user_id)
            ->first();

        if(!$valid_class)
        {
            $errors=[
                'errors'=>['This Class Doesn\'t belongs to this user']
            ];
            return response()->json($errors, 400);
        }

        $update=[];
        if($request->has('person_name')) $update['person_name']= $request->get('person_name');
        if($request->has('attr_1')) $update['attr_1']= $request->get('attr_1');
        if($request->has('attr_2')) $update['attr_2']= $request->get('attr_2');
        if($request->has('attr_3')) $update['attr_3']= $request->get('attr_3');

        UserClassStudents::where('id',$student_id)
            ->update($update);

        $response->message='Student Updated';

        return response()->json($response, 200);

    }

    public function delete_students(Request $request){

        $response=(object)[];

        $validator = Validator::make($request->all(), [
            "students"    => "required|array",
            "students.*"  => "required|numeric|distinct",
        ]);
        if($validator->fails()){
            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];
            return response()->json($errors, 400);
        }

        $students=$request->get('students');

        foreach ($students as $each_student)
        {
            $valid_class=UserClassStudents::where('user_class_students.id',$each_student)
                ->leftJoin('user_classes','class_id','user_classes.id')
                ->where('user_id',$this->user_id)
                ->first();

            if(!$valid_class) {
                $errors = [
                    'errors' => ['This Class Doesn\'t belongs to this user']
                ];
                return response()->json($errors, 400);
            }
        }

        foreach ($students as $each_student)
        {
            UserClassStudents::where('id',$each_student)->delete();
        }

        $response->message='Students Deleted';

        return response()->json($response, 200);

    }

    public function generate_sharable_group(Request $request){

        $response=(object)[];


        $validator = Validator::make($request->all(), [
            "shareable_id"    => "required",
            "students"    => "required|array",
            "students.*"  => "required|",
        ]);

        if($validator->fails()){
            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];
            return response()->json($errors, 400);
        }


        $valid_class=$this->is_this_user_class($request->get('class_id'));

        if(!$valid_class)
        {

            $errors=[
                'errors'=>['This Class Doesn\'t belongs to this user']
            ];
            $response->code=400;
            $response->errors=$errors;
            return response()->json($response, 400);
        }


        $created=UserSharedClasses::updateOrCreate([
            'shareable_id'=>$request->get('shareable_id'),
        ],[
            'students'=>$request->get('students'),
        ]);

        if($created)
        {
            $response->message='Successfully Saved';
            return response()->json($response, 200);
        }

        return response()->json($response, 400);
    }
}
