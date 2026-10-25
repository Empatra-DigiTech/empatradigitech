<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Fase 7.1: nama view yang dipakai controller harus cocok PERSIS (huruf besar/kecil) dengan folder di resources/views.
 * Di Windows `home.pages.so` dan folder `SO` dianggap sama; di Linux tidak, dan halaman /SO + /VM jadi error 500.
 */
class ViewNamingTest extends TestCase
{
    private function root(): string
    {
        return dirname(__DIR__, 2);
    }

    /** @return array<string,true> semua nama view yang ada, mis. "home.pages.SO.index" */
    private function existingViews(): array
    {
        $base = $this->root() . '/resources/views';
        $views = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            if (str_ends_with($f->getFilename(), '.blade.php')) {
                $rel = str_replace('\\', '/', substr($f->getPathname(), strlen($base) + 1));
                $views[str_replace('/', '.', substr($rel, 0, -10))] = true;
            }
        }

        return $views;
    }

    public function test_prefix_view_controller_menunjuk_ke_folder_yang_ada_dengan_huruf_yang_sama(): void
    {
        $views = $this->existingViews();
        $dirs = [];
        foreach (array_keys($views) as $v) {
            $parts = explode('.', $v);
            array_pop($parts);
            for ($i = 1; $i <= count($parts); $i++) {
                $dirs[implode('.', array_slice($parts, 0, $i))] = true;
            }
        }

        $bad = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root() . '/app/Http/Controllers', \FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            if ($f->getExtension() !== 'php') {
                continue;
            }
            if (preg_match_all('/\$this->view\s*=\s*["\']([\w.\-]+)\.["\']/', file_get_contents($f->getPathname()), $m)) {
                foreach ($m[1] as $prefix) {
                    if (! isset($dirs[$prefix])) {
                        $bad[] = basename($f->getPathname()) . ": {$prefix}";
                    }
                }
            }
        }

        $this->assertSame([], $bad, "Prefix view tanpa folder yang cocok persis:\n" . implode("\n", $bad));
    }

    public function test_view_literal_dan_include_menunjuk_ke_berkas_yang_ada(): void
    {
        $views = $this->existingViews();
        $bad = [];
        $scan = ['app', 'routes', 'resources/views'];
        foreach ($scan as $dir) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root() . '/' . $dir, \FilesystemIterator::SKIP_DOTS));
            foreach ($it as $f) {
                if ($f->getExtension() !== 'php') {
                    continue;
                }
                $src = file_get_contents($f->getPathname());
                $src = preg_replace(['/\{\{--.*?--\}\}/s', '/<!--.*?-->/s', '#^\s*//.*$#m'], '', $src);
                preg_match_all('/(?:@(?:include|extends|includeIf)\(|\bview\()\s*[\'"]([A-Za-z0-9_.\-]+)[\'"]/', $src, $m);
                foreach ($m[1] as $name) {
                    if (! isset($views[$name]) && ! str_ends_with($name, '.') && ! in_array($name, ['sweetalert::alert'], true)) {
                        $bad[] = str_replace('\\', '/', substr($f->getPathname(), strlen($this->root()) + 1)) . ": {$name}";
                    }
                }
            }
        }

        $this->assertSame([], $bad, "View tidak ditemukan:\n" . implode("\n", array_unique($bad)));
    }
}
