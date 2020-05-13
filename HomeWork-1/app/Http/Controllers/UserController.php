<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    public function profile($nickname){
        $user = DB::select('select * from users where nickname = "'.$nickname.'"');
        
        return view('user.profile')
                ->with('user', $user[0]);
    }
    
    public function search(Request $request){
        $users = [];
        
        if($request->input('name')){
            $name = explode(' ', $request->input('name'));
        
            //dump($name);
            $fname = '';
            if(sizeof($name) > 1){
                $fname = ' and fname like "'.$name[1].'%"';
            }

            $sql = 'select SQL_NO_CACHE * from users where name like "'.$name[0].'%"'.$fname;
            //echo $sql;
            //wrk -t10 -c50 -d30s --timeout 3s http://165.22.29.236/user/search?name=ab+b
            //$users = DB::select($sql);
            $users = DB::connection('slave')->select($sql);
            //echo 1;
        }
        
        return view('user.search')->with('users', count($users));
    }
}
