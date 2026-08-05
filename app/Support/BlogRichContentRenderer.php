<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class BlogRichContentRenderer
{
    public static function render(mixed $content): HtmlString
    {
        return SiteRichContentRenderer::render($content);
    }
}
