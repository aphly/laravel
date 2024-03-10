<?php

namespace Aphly\Laravel\Controllers\Admin;

use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\UploadFile;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{

    public function download(Request $request)
    {
        $info = UploadFile::where('id',$request->query('id',0))->dataPerm(Manager::_uuid(),$this->roleLevelIds)->first();
        $file_url = storage_path('app/'.$info->path);
        return response()->download($file_url,'download.'.$info->file_type);
    }


}
