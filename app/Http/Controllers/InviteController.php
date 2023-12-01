<?php

namespace App\Http\Controllers;

use App\Models\InviteClass;
use App\Models\InviteClassStudent;
use Illuminate\Http\Request;
use Validator;

class InviteController extends Controller
{
    //

    private $user_id;

    public function __construct()
    {
        $this->user_id = get_user_id_from_token();
    }

    public function create_invite_class(Request $request){

        $code=generateRandomNumber();

        $invite=InviteClass::create([
            'join_code'=>$code,
            'user_id'=>$this->user_id,
        ]);

        $response=(object)[
            'invite_id'=>$invite->id,
            'join_code'=>$code,
        ];

        return response()->json($response, 200);


    }
    public function get_invite_class_students($join_id){


        $response=(object)[];

        $invite_class=InviteClass::where('join_code',$join_id)
            ->where('user_id',$this->user_id)->first();

        if($invite_class)
        {
            $joined_students=InviteClassStudent::where('invite_class_id',$invite_class->id)
                ->select('person_name')
                ->get();
            $response->joined_students=$joined_students;
            return response()->json($response, 200);
        }
        else{
            $errors=[
                'errors'=>['This Join ID  Doesn\'t belongs to this user']
            ];
            $response->code=400;
            $response->errors=$errors;

            return response()->json($response, $response->code);
        }
    }

    public function join_invite($join_id, Request $request){

        $response=(object)[];


        $validator = Validator::make(['join_id'=>$join_id], [
            'join_id' => 'required|exists:invite_classes,join_code',
        ]);

        if ($validator->fails()) {

            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];

            return response()->json($errors, 422);
        }


        $validator = Validator::make($request->all(), [
            'person_name' => 'required',
        ]);

        if ($validator->fails()) {

            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];

            return response()->json($errors, 422);
        }


        $invite_class=InviteClass::where('join_code',$join_id)->first();

        if($invite_class){
            InviteClassStudent::create([
                'invite_class_id'=>$invite_class->id,
                'person_name'=>$request->get('person_name'),
            ]);
        }

        return response()->json($response, 200);

    }
}
