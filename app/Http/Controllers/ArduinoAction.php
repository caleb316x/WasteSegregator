<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Arduino;

class ArduinoAction extends Controller
{
    public function index()
    {
        // return "rrr";
        return view('dashboard.index');
    }
    public function set_drop()
    {
        $contents = Storage::get('status.txt');
        Storage::put( 'status.txt', "drop");
        return "drop";
    }

    public function get_prediction()
    {
        $contents = Storage::get('detect.txt');
        Storage::put( 'detect.txt', "");
        Storage::put( 'status.txt', "idle");
        return $contents;
    }

    public function prediction($request)
    {
        
        Storage::put( 'detect.txt', $request);
        return $request;
    }

    public function request_camera()
    {
        Storage::put( 'status.txt', "capture");
        return "sent";
    }

    public function check_drop() //done
    {
        $contents = Storage::get('status.txt');
        if($contents == "capture"){
            return "capture";
        }
        return "";
    }

}
