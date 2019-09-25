<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Translate\TranslateClient;
use Illuminate\Support\Facades\Auth;

class TranslateController extends Controller
{
//'keyFilename' => env('GOOGLE_PRIVET_KEY')
//            'projectId' => env('GOOGLE_PROJECT_ID'),
    public function translate(Request $request,$target,$source)
    {
        $projectId = env('GOOGLE_PROJECT_ID');

        $translate = new TranslateClient([
            'projectId' => $projectId,
            'key'=> env('GOOGLE_KEY')
        ]);

        $user = Auth::user();
//        $target = 'en';
        $text = $request->text;
        // Translates some text into Russian
        $translation = $translate->translate($text, [
            'target' => $target,
            'source' => $source
        ]);
        return response( ['translate'=>$translation['text']]);
    }
}
