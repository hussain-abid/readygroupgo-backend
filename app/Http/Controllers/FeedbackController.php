<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class FeedbackController extends Controller
{
    //


    private $user_id;

    public function __construct()
    {
        $this->user_id = get_user_id_from_token();
    }

    public function give_feedback(Request $request)
    {
        $response=(object)[];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'grade_level' => 'required',
            'message' => 'required',
        ]);
        if ($validator->fails()) {

            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];

            return response()->json($errors, 422);
        }

        $values=[
            'email'=>$request->get('email'),
            'name'=>$request->get('name'),
            'school'=>$request->get('school'),
            'message'=>$request->get('message'),
        ];

        if($request->has('receive_update'))
        {
            $values['receive_update']=$request->get('receive_update');
        }

        $response=(object)[
            'values'=>$values
        ];

        return response()->json($response, 200);
    }
}
