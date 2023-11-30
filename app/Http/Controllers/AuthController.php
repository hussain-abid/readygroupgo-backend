<?php

namespace App\Http\Controllers;

use App\Models\PromptsData;
use App\Models\UserClass;
use App\Models\UserClassStudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;



class AuthController extends Controller
{
    //

    public function __construct() {
//        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {

            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];

            return response()->json($errors, 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {

            $errors=[
                'errors'=>['Email 0r Password is incorrect']
            ];

            return response()->json($errors, 401);
        }

        $user=auth()->user();
        $response=[
            'access_token'=>$token,
            "token_type"=> "bearer",
            "expires_in"=> 0,
            'user' => $user,
        ];
        return response()->json($response, 200);

    }

    public function register(Request $request) {


        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6'
        ]);
        if($validator->fails()){
            $errors=[
                'errors'=>validationErrorMessagesToArray($validator->errors())
            ];
            return response()->json($errors, 400);
        }

        $user = User::create(
            [
                'password' => bcrypt($request->get('password')),
                'email' => $request->get('email'),
                'first_name'=>ucfirst($request->get('first_name')),
                'last_name'=>ucfirst($request->get('last_name')),
            ]
        );

        $default_names=config('readygroupgo.default_names');

        $user_class=UserClass::create([
            'user_id'=>$user->id,
            'shareable_id'=>generateUniqueKey(),
            'name'=>'Example Class',
            'attr1'=>'Attr 1',
            'attr2'=>'Attr 2',
            'attr3'=>'Attr 3',
        ]);

        foreach ($default_names as $each)
        {
            UserClassStudents::create([
                'person_name'=>$each,
                'class_id'=>$user_class->id,
            ]);
        }

        $token = JWTAuth::fromUser($user);

        $response=[
            'access_token'=>$token,
            "token_type"=> "bearer",
            "expires_in"=> 0,
            'user'=>$user,
        ];
        return response()->json($response, 200);

    }

    public function complete_profile(Request $request)
    {


        $user_id=get_user_id_from_token();

        $is_profile_complete=is_profile_complete($user_id);

        if($is_profile_complete)
        {

            $response=[
                'errors'=>["The Profile is Already Completed"]
            ];
            return response()->json($response, 404);
        }
        else{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'user_name' => 'required|string|unique:users|between:2,100|alpha_dash',
            ]);

            if($validator->fails()){
                $errors=[
                    'errors'=>validationErrorMessagesToArray($validator->errors())
                ];
                return response()->json($errors, 400);
            }

            $to_update=[
                'name'=>$request->get('name'),
                'user_name'=>$request->get('user_name'),
            ];

            if($request->hasFile('photo')){
                $file = $request->file('photo');

                $full_image_name=$file->getClientOriginalName();
                $real_image_name=$filename = pathinfo($full_image_name, PATHINFO_FILENAME);
                $new_image_name = time().'-'.$real_image_name.'.'.$file->extension();
                $image_path = public_path(). '/user_photo';

                $file->move($image_path, $new_image_name);
                $to_update['photo']=$new_image_name;
            }

            $success=User::where(['id'=>$user_id])->update($to_update);
            if($success)
            {
                return response()->json(['message'=>['Information Updated Successfully']], 200);
            }

            else{
                return response()->json(['message'=>['Some Error Happened! Contact Support']], 404);
            }

        }
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
