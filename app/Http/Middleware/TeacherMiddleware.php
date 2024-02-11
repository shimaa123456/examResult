<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Login;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionToken = Session::get('loginToken');
        $loginUser = Login::where("token", "=", $sessionToken)->first();
        if($loginUser) {
            if($loginUser->role == "teachers"){
                return $next($request);
            }
            else{
                $loginUser->token = "0";
                $loginUser->save();
                Session::flush();
                return redirect('/')->with('mustTeacher','you arenot teacher !!!');;

            }
        }
        else{
            Session::flush();
            return redirect('/')->with('mustTeacher','you arenot teacher !!!');
        }
    }

    }
