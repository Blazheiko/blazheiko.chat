<?php


namespace App\Services;


use Google\Cloud\Translate\TranslateClient;

class TranslateServise
{

    public function translate( $text,$target,$source)
    {
//$projectId = env('GOOGLE_PROJECT_ID');

        $translate = new TranslateClient([
            'projectId' => env('GOOGLE_PROJECT_ID'),
            'key' => env('GOOGLE_KEY')
        ]);

//        $target = 'en';
        // Translates some text into Russian
        $translation = $translate->translate($text, [
            'target' => $target,
            'source' => $source
        ]);
        return  $translation['text'];
    }

    public function localizedLanguages($language){
        $translate = new TranslateClient([
            'projectId' => env('GOOGLE_PROJECT_ID'),
            'key' => env('GOOGLE_KEY')
        ]);

        return $translate->localizedLanguages([
            'target' => $language,
        ]);
    }

}
