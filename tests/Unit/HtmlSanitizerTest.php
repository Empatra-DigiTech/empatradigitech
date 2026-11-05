<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Konten Trix dicetak dengan {!! !!}, jadi renderTrix() harus membuang
 * script dan atribut/URL berbahaya tanpa merusak format yang dipakai Trix.
 */
class HtmlSanitizerTest extends TestCase
{
    public static function payloadBerbahaya(): array
    {
        return [
            'script tag'        => ['<p>halo</p><script>alert(1)</script>', ['<script', 'alert(1)']],
            'onerror pada img'  => ['<img src="x" onerror="alert(1)">', ['onerror', 'alert']],
            'onclick'           => ['<a href="https://a.test" onclick="alert(1)">klik</a>', ['onclick']],
            'javascript: href'  => ['<a href="javascript:alert(1)">klik</a>', ['javascript']],
            'javascript: + tab' => ["<a href=\"java\tscript:alert(1)\">klik</a>", ['script:']],
            'javascript: entity' => ['<a href="&#106;avascript:alert(1)">klik</a>', ['avascript']],
            'data: href'        => ['<a href="data:text/html;base64,PHNjcmlwdD4=">klik</a>', ['data:']],
            'vbscript'          => ['<a href="vbscript:msgbox(1)">klik</a>', ['vbscript']],
            'iframe'            => ['<iframe src="https://evil.test"></iframe>', ['<iframe', 'evil.test']],
            'svg onload'        => ['<svg onload="alert(1)"><circle/></svg>', ['<svg', 'onload']],
            'style tag'         => ['<style>body{display:none}</style><p>x</p>', ['<style', 'display']],
            'atribut style'     => ['<p style="background:url(javascript:alert(1))">x</p>', ['style=', 'javascript']],
            'form'              => ['<form action="https://evil.test"><input name="p"></form>', ['<form', '<input']],
            'komentar'          => ['<p>a</p><!--[if IE]><script>alert(1)</script><![endif]-->', ['<!--', 'alert']],
            'object/embed'      => ['<object data="x.swf"></object><embed src="x.swf">', ['<object', '<embed']],
            'tag bersarang'     => ['<scr<script>ipt>alert(1)</scr</script>ipt>', ['<script']],
            'img src javascript' => ['<img src="javascript:alert(1)">', ['javascript']],
        ];
    }

    #[DataProvider('payloadBerbahaya')]
    public function test_payload_berbahaya_dibuang(string $input, array $tidakBolehAda): void
    {
        $out = HtmlSanitizer::clean($input);

        foreach ($tidakBolehAda as $needle) {
            $this->assertStringNotContainsStringIgnoringCase($needle, $out, "Output masih memuat '{$needle}': {$out}");
        }
    }

    public function test_format_trix_dipertahankan(): void
    {
        $html = '<div><strong>tebal</strong> <em>miring</em> <del>coret</del><br>baris</div>'
            . '<ul><li>satu</li></ul><ol><li>dua</li></ol>'
            . '<blockquote>kutipan</blockquote><pre>kode</pre><h1>Judul</h1>'
            . '<a href="https://contoh.test/x?a=1&amp;b=2">tautan</a>';

        $out = HtmlSanitizer::clean($html);

        foreach (['<strong>tebal</strong>', '<em>miring</em>', '<del>coret</del>', '<li>satu</li>',
            '<blockquote>kutipan</blockquote>', '<pre>kode</pre>', '<h1>Judul</h1>',
            'href="https://contoh.test/x?a=1&amp;b=2"'] as $expected) {
            $this->assertStringContainsString($expected, $out);
        }
    }

    public function test_lampiran_gambar_trix_dipertahankan(): void
    {
        $html = '<figure data-trix-attachment="{&quot;url&quot;:&quot;/storage/a.jpg&quot;}" class="attachment attachment--preview">'
            . '<img src="/storage/a.jpg" width="100" height="50"><figcaption class="attachment__caption">foto</figcaption></figure>';

        $out = HtmlSanitizer::clean($html);

        $this->assertStringContainsString('<img src="/storage/a.jpg"', $out);
        $this->assertStringContainsString('<figcaption', $out);
        $this->assertStringNotContainsString('data-trix-attachment', $out);
    }

    public function test_url_aman_dan_relatif_lolos(): void
    {
        foreach (['https://a.test', 'http://a.test', 'mailto:a@b.test', 'tel:+62812', '/halaman', '#bagian', 'foto/a.jpg'] as $url) {
            $out = HtmlSanitizer::clean('<a href="' . $url . '">x</a>');
            $this->assertStringContainsString('href="' . $url . '"', $out, $url);
        }
    }

    public function test_target_blank_mendapat_rel_noopener(): void
    {
        $out = HtmlSanitizer::clean('<a href="https://a.test" target="_blank">x</a>');

        $this->assertStringContainsString('rel="noopener noreferrer"', $out);
    }

    public function test_tag_tidak_dikenal_dibuka_tapi_isi_dipertahankan(): void
    {
        $out = HtmlSanitizer::clean('<custom-tag>isi <b>aman</b></custom-tag>');

        $this->assertStringNotContainsString('custom-tag', $out);
        $this->assertStringContainsString('isi', $out);
        $this->assertStringContainsString('<b>aman</b>', $out);
    }

    public function test_teks_unicode_dan_entitas_tidak_rusak(): void
    {
        $out = HtmlSanitizer::clean('<div>Pelatihan K3 – Ahli “Umum” &amp; Madya é 日本</div>');

        $this->assertStringContainsString('Pelatihan K3 – Ahli “Umum” &amp; Madya é 日本', $out);
    }

    public function test_null_dan_kosong_menghasilkan_string_kosong(): void
    {
        $this->assertSame('', HtmlSanitizer::clean(null));
        $this->assertSame('', HtmlSanitizer::clean(''));
        $this->assertSame('', HtmlSanitizer::clean("  \n "));
    }
}
