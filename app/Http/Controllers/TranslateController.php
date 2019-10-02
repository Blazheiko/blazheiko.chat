<?php

namespace App\Http\Controllers;

use App\Services\TranslateServise;
use Illuminate\Http\Request;
use Google\Cloud\Translate\TranslateClient;
use Illuminate\Support\Facades\Auth;

class TranslateController extends Controller
{
//'keyFilename' => env('GOOGLE_PRIVET_KEY')
//            'projectId' => env('GOOGLE_PROJECT_ID'),
    public function translate(Request $request,$target,$source)
    {
        $translation = new TranslateServise();

        return response( ['translate'=>$translation->translate($request->text,$target,$source)]);
    }
}
