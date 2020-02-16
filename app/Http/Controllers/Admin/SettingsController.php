<?php

namespace App\Http\Controllers\admin;
use DB;
use Auth;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index(Request $request) 
    {
        if (permission::permitted('settings')=='fail'){ return redirect()->route('denied'); }
        $data = table::settings()->where('id', 1)->first();
        $ip = $request->ip();
        
        if ($data !== null) 
        {
            $ss = json_decode($data->opt);
            if(!isset($ss->activated_at)) 
            {
                $s = "1";
            } else {
                $s = $ss;
            }
        } else {
            $s = "1";
        }
        
    	return view('admin.settings', compact('data', 'ip', 's'));
    }

    public function update(Request $request) 
    {
        if (permission::permitted('settings-update')=='fail'){ return redirect()->route('denied'); }
        //if($request->sh == 2){return redirect()->route('settings');}
        
        $v = $request->validate([
            'country' => 'required|max:100',
            'timezone' => 'required|timezone|max:100',
            'clock_comment' => 'boolean|max:2',
            'iprestriction' => 'max:1600',
        ]);

        $country = $request->country;
        $timezone = $request->timezone;
        $clock_comment = $request->clock_comment;
        $iprestriction = $request->iprestriction;
        
        if ($timezone != null) 
        {
            $t = table::settings()->where('id', 1)->value('timezone');
            $path = base_path('.env');
            
            if(file_exists($path)) 
            {
                file_put_contents($path, str_replace('APP_TIMEZONE='.$t, 'APP_TIMEZONE='.$timezone, file_get_contents($path)));
            }
        }

        table::settings()
        ->where('id', 1)
        ->update([
                'country' => $country,
                'timezone' => $timezone,
                'clock_comment' => $clock_comment,
                'iprestriction' => $iprestriction,
        ]);
        
        return redirect('settings')->with('success', 'Settings has been updated. Please try re-login for the new settings to take effect.');
    }

    public function appInfo() 
    {
        goto SQb4Z; H0lCt: $vWikr = $UIs2f->UbEWU == null || strlen($UIs2f->UbEWU) != 60 ? 2 : 1; goto WrtUm; fP2rM: dcITV: goto H0lCt; y8Mcp: goto jPd24; goto fP2rM; HXlSH: if (isset($UIs2f->UbEWU)) { goto dcITV; } goto pdt8S; SQb4Z: $UIs2f = json_decode(table::settings()->where("\x69\144", 1)->value("\x6f\160\164")); goto HXlSH; pdt8S: $vWikr = 2; goto y8Mcp; WrtUm: jPd24: goto Z0DT0; Z0DT0: return response()->json(["\x73" => $vWikr]);
    }

    public function reverse(Request $request) 
    {
        goto rkU1i; EqygM: if ($jdB_h != $rfLH1) { goto oIiqe; } goto BGcNc; E3SG1: if (isset($jdB_h)) { goto V3yhU; } goto xVZCd; NFVjx: table::settings()->where("\x69\x64", 1)->update(["\x6f\160\164" => '']); goto JYsZd; JeqfA: IvEjv: goto bP6o0; LK3H8: if (!isset($jdB_h)) { goto zUGaS; } goto EqygM; xVZCd: return response()->json(["\145\x72\x72\x6f\x72" => "\x49\156\x76\x61\154\x69\x64\x20\162\x65\x71\165\x65\x73\x74\x2e"]); goto ufpFI; ufpFI: V3yhU: goto LK3H8; BGcNc: $klzxt = table::settings()->where("\x69\x64", 1)->value("\157\160\x74"); goto NFVjx; uUk1E: oIiqe: goto SLIaT; JYsZd: return response()->json(["\x73\165\x63\x63\145\x73\x73" => "\131\x6f\x75\x72\x20\x61\160\x70\40\151\163\40\x64\145\x61\x63\x74\151\166\x61\x74\x65\144\x2e", "\x64\141\x74\141" => $klzxt]); goto sMeOw; rkU1i: $jdB_h = $request->id; goto i3P3q; sMeOw: goto IvEjv; goto uUk1E; SLIaT: return response()->json(["\145\162\x72\x6f\162" => "\x49\x6e\166\141\x6c\x69\x64\40\x72\145\x71\165\145\x73\164\56\40\x57\162\157\156\x67\40\111\x44\x2e"]); goto JeqfA; i3P3q: $rfLH1 = \Auth::user()->id; goto E3SG1; bP6o0: zUGaS: ;
    }
}
