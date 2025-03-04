<?php

namespace App\Handlers;

use Overtrue\Pinyin\Pinyin;
use Illuminate\Support\Str;

class SlugTranslateHandler
{
    public function translate($text)
    {
        return $this->pinyin($text);
    }

    public function pinyin($text)
    {
        return Str::slug(app(Pinyin::class)->permalink($text));
    }
}
