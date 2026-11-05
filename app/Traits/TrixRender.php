<?php

namespace App\Traits;

use App\Support\HtmlSanitizer;

trait TrixRender
{
    public function renderTrix($field)
    {
        $content = $this->trixRichText->where('field', $field)->first()?->content;
        unset($this->trixRichText);

        // Konten Trix disanitasi di sini karena hampir semua view memakai {!! !!}.
        return HtmlSanitizer::clean($content);
    }
}