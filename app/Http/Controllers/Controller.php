<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

     public function saveImage(Request $request, $folderPath)
    {

        $file = $request->file('image');
        $extension =$file->getClientOriginalExtension();
        $fileName =  time().'_'.uniqid(). '.' .$extension;
        $request->file('image')->storeAs($folderPath, $fileName, 'public');
        return $fileName;

    }

}
