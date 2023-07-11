<?php

namespace App\Http\Controllers;

use App\Models\Photos;
use Illuminate\Http\Request;
use File;

class Photo extends Controller
{
    //

    public function delete_image($id){
        $media = Photos::find($id);
        $file_name = $media->file_name;

        $media->delete();
        $type = $_GET['type'];

        File::delete( public_path('/uploads/'.$type.'-gallery/'.$file_name));
    }
}
