<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslatedWord extends Model
{
    use HasFactory;

    //boot
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            //exhisting word 
            $word = TranslatedWord::where('word', $model->word)->first();
            if ($word != null) {
                return false;
            }
        });
    }

    //translate
    public static function translate($word, $lang)
    {
        $data = TranslatedWord::where([
            'word' => $word
        ])->first();
        if ($data == null) {
            return $word;
        }
        if (!(isset($data->$lang))) {
            return $word;
        }
        if ($data->$lang == null) {
            return $word;
        }
        if (strlen($data->$lang) < 1) {
            return $word;
        }
        return $data->$lang;
    }
}
