<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Sanitizer HTML berbasis allowlist untuk konten rich text (Trix).
 *
 * Hanya tag dan atribut yang dikenal aman yang dipertahankan. Tag berbahaya
 * (script, style, iframe, dll) dibuang beserta isinya, tag lain yang tidak
 * dikenal dibuka (isinya dipertahankan, tag-nya dibuang). Semua atribut
 * event (onclick, onerror, ...) dan URL dengan skema berbahaya
 * (javascript:, data:, vbscript:, ...) dibuang.
 */
class HtmlSanitizer
{
    /** Tag yang boleh tampil. */
    private const ALLOWED_TAGS = [
        'a', 'abbr', 'b', 'blockquote', 'br', 'code', 'del', 'div', 'em',
        'figcaption', 'figure', 'h1', 'h2', 'h3', 'h4', 'hr', 'i', 'img', 'li',
        'ol', 'p', 'pre', 's', 'span', 'strong', 'sub', 'sup', 'u', 'ul',
    ];

    /** Tag yang dibuang beserta seluruh isinya. */
    private const DROP_WITH_CONTENT = [
        'script', 'style', 'iframe', 'frame', 'frameset', 'object', 'embed',
        'applet', 'svg', 'math', 'template', 'noscript', 'form', 'textarea',
        'select', 'option', 'button', 'input', 'link', 'meta', 'base', 'title',
        'head', 'audio', 'video', 'source', 'track', 'canvas', 'xml',
    ];

    /** Atribut yang boleh per tag. Atribut global ada di GLOBAL_ATTRS. */
    private const TAG_ATTRS = [
        'a'   => ['href', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height'],
    ];

    private const GLOBAL_ATTRS = ['class', 'title'];

    private const URL_ATTRS = ['href', 'src'];

    private const SAFE_SCHEMES = ['http', 'https', 'mailto', 'tel'];

    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $previous = libxml_use_internal_errors(true);

        $doc = new DOMDocument('1.0', 'UTF-8');
        // Prefix <?xml> memaksa libxml membaca string sebagai UTF-8.
        $doc->loadHTML(
            '<?xml encoding="UTF-8"><body><div id="__root">' . $html . '</div></body>',
            LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('__root');
        if (! $root) {
            return e(strip_tags($html));
        }

        self::sanitizeChildren($root);

        $output = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $output .= $doc->saveHTML($child);
        }

        return $output;
    }

    private static function sanitizeChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node instanceof DOMElement) {
                self::sanitizeElement($node);
            } elseif ($node->nodeType !== XML_TEXT_NODE) {
                // Komentar, CDATA, processing instruction, dll.
                $parent->removeChild($node);
            }
        }
    }

    private static function sanitizeElement(DOMElement $el): void
    {
        $tag = strtolower($el->tagName);

        if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
            $el->parentNode->removeChild($el);

            return;
        }

        if (! in_array($tag, self::ALLOWED_TAGS, true)) {
            // Buka tag: pertahankan isinya yang sudah disanitasi.
            self::sanitizeChildren($el);
            $parent = $el->parentNode;
            while ($el->firstChild) {
                $parent->insertBefore($el->firstChild, $el);
            }
            $parent->removeChild($el);

            return;
        }

        self::sanitizeAttributes($el, $tag);
        self::sanitizeChildren($el);
    }

    private static function sanitizeAttributes(DOMElement $el, string $tag): void
    {
        $allowed = array_merge(self::GLOBAL_ATTRS, self::TAG_ATTRS[$tag] ?? []);

        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);

            if (! in_array($name, $allowed, true)) {
                $el->removeAttribute($attr->name);

                continue;
            }

            if (in_array($name, self::URL_ATTRS, true) && ! self::isSafeUrl($attr->value)) {
                $el->removeAttribute($attr->name);
            }
        }

        if ($tag === 'a') {
            if ($el->getAttribute('target') !== '' && $el->getAttribute('target') !== '_blank') {
                $el->removeAttribute('target');
            }
            if ($el->getAttribute('target') === '_blank') {
                $el->setAttribute('rel', 'noopener noreferrer');
            } else {
                $el->removeAttribute('rel');
            }
        }

        if ($tag === 'img' && ! $el->hasAttribute('src')) {
            $el->parentNode->removeChild($el);
        }
    }

    private static function isSafeUrl(string $url): bool
    {
        // Buang spasi dan karakter kontrol yang dipakai untuk menyamarkan skema
        // (mis. "java\tscript:").
        $clean = preg_replace('/[\x00-\x20\x7F-\x9F]+/u', '', $url) ?? '';
        $clean = html_entity_decode($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $clean = preg_replace('/[\x00-\x20\x7F-\x9F]+/u', '', $clean) ?? '';

        if ($clean === '') {
            return false;
        }

        // URL relatif / anchor / protocol-relative.
        if (preg_match('/^(?:[#\/?]|\.{1,2}\/)/', $clean) === 1 && ! str_starts_with($clean, '//')) {
            return true;
        }

        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $clean, $m) === 1) {
            return in_array(strtolower($m[1]), self::SAFE_SCHEMES, true);
        }

        // Tanpa skema dan tanpa ':' (mis. "gambar/foto.jpg") dianggap relatif.
        return ! str_contains($clean, ':');
    }
}
