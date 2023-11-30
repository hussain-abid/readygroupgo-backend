<?php
use Illuminate\Support\Str;


function assets(){
    return asset('/').'public/assets/';
}

function public_assets(){
    return asset('/').'public/';
}


function website_url(){
    return env('APP_URL');
}

function validationErrorMessagesToArray($errors){
    $result=[];
    if(!empty($errors)){
        foreach($errors->getMessages() as $error_key=>$error_value){
            foreach($error_value as $each_value_error){
                $result[]=$each_value_error;
            }
        }
    }
    return $result;
//        return "okay";
}


//---
function random_prefix_generator_for_image(){
    return Str::random(10);
}

function get_user_id_from_token()
{
    try {
        $user = JWTAuth::parseToken()->authenticate();
    } catch (Exception $e) {
        return "error";
    }
    return $user->id;
}


function generateUniqueKey($length=10)
{
    // Generate a random alphanumeric key of the specified length
    $uniqueKey = Str::random($length);

    return $uniqueKey;
}

