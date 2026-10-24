<?php

namespace Tests\Feature;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FooterMapTest extends TestCase
{
    private function setMaps(string $value): string
    {
        Pengaturan::first()->update(['website_maps' => $value]);
        Cache::flush();

        return $this->get('/')->getContent();
    }

    public function test_tag_iframe_yang_ditempel_admin_diambil_src_nya(): void
    {
        $html = $this->setMaps('<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12" width="600" height="450"></iframe>');

        $this->assertStringContainsString('id="maps_mini"', $html);
        $this->assertStringContainsString('src="https://www.google.com/maps/embed?pb=!1m18!1m12"', $html);
        $this->assertStringNotContainsString('src="&lt;iframe', $html);
    }

    public function test_url_embed_biasa_tetap_dipakai(): void
    {
        $html = $this->setMaps('https://www.google.com/maps/embed?pb=abc');

        $this->assertStringContainsString('src="https://www.google.com/maps/embed?pb=abc"', $html);
    }

    public function test_link_bukan_embed_atau_bukan_https_tidak_dijadikan_iframe(): void
    {
        $this->assertStringNotContainsString('id="maps_mini"', $this->setMaps('https://maps.app.goo.gl/xyz'));
        $this->assertStringNotContainsString('id="maps_mini"', $this->setMaps('javascript:alert(1)//maps/embed'));
        $this->assertStringNotContainsString('id="maps_mini"', $this->setMaps('http://www.google.com/maps/embed?pb=1'));
    }
}
