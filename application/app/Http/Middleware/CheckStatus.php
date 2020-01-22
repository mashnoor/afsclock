<?php
/*
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 4.2
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
*/
namespace App\Http\Middleware;

use Closure;
use View;
use App\Classes\table;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $s = \Auth::user()->status;
        $r = \Auth::user()->role_id;
        $o = table::settings()->where('id', 1)->value('opt');
        $d = json_decode($o);

        if ($s == null || $s == 0) 
        {
            \Auth::logout();
            return redirect()->route('disabled');
        } 
        
        if ($r == null || $r == 0) 
        {
            \Auth::logout();
            return redirect()->route('notfound');
        }

        if(isset($d->key)) {$sh = ($d->key == null || strlen($d->key) != 60) ? 2 : 1;} else {$sh = 2;} view()->share('var', $sh); $request->merge(compact('sh'));
        
        return $next($request);
    }
}
