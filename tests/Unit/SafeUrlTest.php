<?php

namespace Tests\Unit;

use App\Rules\SafeUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** C2: kolom URL dirender ke href, jadi skema berbahaya tidak boleh tersimpan. */
class SafeUrlTest extends TestCase
{
    public static function urlAman(): array
    {
        return [
            ['https://contoh.test'], ['http://contoh.test/a?b=1&c=2#x'], ['HTTPS://CONTOH.TEST'],
            ['https://wa.me/6281234567890'], ['mailto:halo@contoh.test'], ['tel:+6281234567890'],
            ['/kontak'], ['/blog/judul-artikel'], ['#bagian'],
        ];
    }

    public static function urlBerbahaya(): array
    {
        return [
            'javascript'          => ['javascript:alert(1)'],
            'javascript besar'    => ['JaVaScRiPt:alert(1)'],
            'javascript + tab'    => ["java\tscript:alert(1)"],
            'javascript + spasi'  => [' javascript:alert(1)'],
            'data'                => ['data:text/html;base64,PHNjcmlwdD4='],
            'vbscript'            => ['vbscript:msgbox(1)'],
            'file'                => ['file:///etc/passwd'],
            'ftp'                 => ['ftp://contoh.test'],
            'tanpa skema'         => ['www.contoh.test'],
            'protocol-relative'   => ['//jahat.test'],
            'backslash'           => ['/\\jahat.test'],
            'http tanpa host'     => ['http://'],
            'mailto kosong'       => ['mailto:'],
            'spasi di dalam'      => ['https://contoh.test/a b'],
            'baris baru'          => ["https://contoh.test\nonerror=1"],
            'path relatif'        => ['halaman/lain'],
        ];
    }

    #[DataProvider('urlAman')]
    public function test_url_aman_lolos(string $url): void
    {
        $this->assertTrue(SafeUrl::isSafe($url), $url);
    }

    #[DataProvider('urlBerbahaya')]
    public function test_url_berbahaya_ditolak(string $url): void
    {
        $this->assertFalse(SafeUrl::isSafe($url), $url);
    }

    public function test_nilai_kosong_dilewati_agar_nullable_tetap_berfungsi(): void
    {
        $gagal = false;
        (new SafeUrl)->validate('url', '', function () use (&$gagal) { $gagal = true; });
        (new SafeUrl)->validate('url', null, function () use (&$gagal) { $gagal = true; });

        $this->assertFalse($gagal);
    }
}
