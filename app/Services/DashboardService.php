<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\ClientLogo;
use App\Models\Faq;
use App\Models\FinanceTransaction;
use App\Models\Galeri;
use App\Models\Informasi;
use App\Models\Inovasi;
use App\Models\Invoice;
use App\Models\Kontak;
use App\Models\Layanan;
use App\Models\Menu;
use App\Models\Paket;
use App\Models\Portofolio;
use App\Models\Tautan;
use App\Models\Team;
use App\Models\Testimoni;
use App\Models\User;
use App\Support\PublicCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyusun semua data dashboard /patra.
 *
 * Semua query memakai agregasi di database dan SQL yang portabel (MySQL & SQLite),
 * dan setiap blok dibatasi sesuai role: Moderator hanya melihat modul kontennya.
 */
class DashboardService
{
    public const RANGES = [7, 30, 90];

    private User $user;
    private bool $isSuper;
    private bool $isAdminUp;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->isSuper = $user->hasRole([RoleEnum::SuperAdmin]);
        $this->isAdminUp = $user->hasRole([RoleEnum::SuperAdmin, RoleEnum::Admin]);
    }

    public static function normalizeRange($range): int
    {
        $range = (int) $range;

        return in_array($range, self::RANGES, true) ? $range : 30;
    }

    public function build(int $range): array
    {
        $data = [
            'range' => $range,
            'ranges' => self::RANGES,
            'isSuper' => $this->isSuper,
            'isAdminUp' => $this->isAdminUp,
            'modules' => $this->modules(),
            'traffic' => $this->traffic($range),
            'topContent' => $this->topContent($range),
            'checklist' => $this->checklist(),
            'quickActions' => $this->quickActions(),
            'messages' => null,
            'invoices' => null,
            'finance' => null,
            'activity' => null,
        ];

        if ($this->isAdminUp) {
            $data['messages'] = $this->messages($range);
            $data['invoices'] = $this->invoices();
        }

        if ($this->isSuper) {
            $data['finance'] = $this->finance();
            $data['activity'] = $this->activity();
        }

        // Doughnut komposisi konten: hanya modul yang berisi data.
        $data['composition'] = collect($data['modules'])
            ->filter(fn ($m) => $m['count'] > 0 && $m['chart'])
            ->map(fn ($m) => ['label' => $m['label'], 'value' => $m['count'], 'color' => $m['hex']])
            ->values()
            ->all();

        return $data;
    }

    /* ------------------------------------------------------------------ */
    /* Modul & jumlah data                                                */
    /* ------------------------------------------------------------------ */

    private function moduleDefinitions(): array
    {
        $all = [RoleEnum::SuperAdmin, RoleEnum::Admin, RoleEnum::Moderator];
        $adm = [RoleEnum::SuperAdmin, RoleEnum::Admin];

        // key => [label, model, route, icon, hex, roles, ikut doughnut?]
        return [
            'portofolio' => ['Portofolio', Portofolio::class, 'patra.portofolio.index', 'bx-briefcase-alt-2', '#0f3c5d', $all, true],
            'blog' => ['Blog / Insight', Blog::class, 'patra.blog.index', 'bx-news', '#2e86ab', $all, true],
            'faq' => ['FAQ', Faq::class, 'patra.faq.index', 'bx-help-circle', '#6f42c1', $all, true],
            'testimoni' => ['Testimoni', Testimoni::class, 'patra.testimoni.index', 'bx-comment-detail', '#e0a526', $all, true],
            'banner' => ['Banner', Banner::class, 'patra.banner.index', 'bx-images', '#b02e36', $adm, true],
            'galeri' => ['Galeri', Galeri::class, 'patra.galeri.index', 'bx-camera', '#d9534f', $adm, true],
            'layanan' => ['Layanan', Layanan::class, 'patra.layanan.index', 'bx-wrench', '#2e9e5b', $adm, true],
            'paket' => ['Paket Digital', Paket::class, 'patra.paket.index', 'bx-package', '#17a2b8', $adm, true],
            'inovasi' => ['Inovasi', Inovasi::class, 'patra.inovasi.index', 'bx-bulb', '#fd7e14', $adm, true],
            'client' => ['Client / Partner', ClientLogo::class, 'patra.client-logo.index', 'bx-buildings', '#20c997', $adm, true],
            'informasi' => ['Informasi Publik', Informasi::class, 'patra.informasi.index', 'bx-info-circle', '#6c757d', $adm, true],
            'team' => ['Team', Team::class, 'patra.team.index', 'bx-group', '#795548', $adm, true],
            'tautan' => ['Tautan', Tautan::class, 'patra.tautan.index', 'bx-link', '#3f51b5', $adm, false],
            'menu' => ['Menu', Menu::class, 'patra.menu.index', 'bx-menu', '#607d8b', $adm, false],
        ];
    }

    private function modules(): array
    {
        $out = [];

        foreach ($this->moduleDefinitions() as $key => [$label, $model, $route, $icon, $hex, $roles, $chart]) {
            if (! $this->user->hasRole($roles)) {
                continue;
            }

            $out[$key] = [
                'key' => $key,
                'label' => $label,
                'count' => (int) $model::count(),
                'url' => route($route),
                'icon' => $icon,
                'hex' => $hex,
                'chart' => $chart,
            ];
        }

        return $out;
    }

    /* ------------------------------------------------------------------ */
    /* Tampilan konten (tabel `views`, hanya portofolio & blog dicatat)   */
    /* ------------------------------------------------------------------ */

    private function dayLabels(int $range): array
    {
        $days = [];
        $start = now()->subDays($range - 1)->startOfDay();

        for ($i = 0; $i < $range; $i++) {
            $days[] = $start->copy()->addDays($i);
        }

        return $days;
    }

    private function trend(int $current, int $previous): ?int
    {
        if ($previous === 0) {
            return $current === 0 ? 0 : null; // null = "baru", tidak bisa dihitung persen
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }

    private function traffic(int $range): array
    {
        $days = $this->dayLabels($range);
        $from = $days[0];
        $prevFrom = $from->copy()->subDays($range);

        $daily = DB::table('views')
            ->selectRaw('DATE(viewed_at) as d, COUNT(*) as c')
            ->where('viewed_at', '>=', $from)
            ->groupBy('d')
            ->pluck('c', 'd');

        $series = [];
        foreach ($days as $day) {
            $series[] = (int) ($daily[$day->toDateString()] ?? 0);
        }

        $total = array_sum($series);
        $previous = (int) DB::table('views')
            ->where('viewed_at', '>=', $prevFrom)
            ->where('viewed_at', '<', $from)
            ->count();

        $visitors = (int) DB::table('views')
            ->where('viewed_at', '>=', $from)
            ->whereNotNull('visitor')
            ->distinct()
            ->count('visitor');

        $today = (int) end($series);
        $peakIndex = $total > 0 ? array_search(max($series), $series, true) : null;

        return [
            'labels' => array_map(fn (Carbon $d) => $d->locale('id')->translatedFormat('d M'), $days),
            'series' => $series,
            'total' => $total,
            'previous' => $previous,
            'trend' => $this->trend($total, $previous),
            'visitors' => $visitors,
            'today' => $today,
            'average' => $range > 0 ? round($total / $range, 1) : 0,
            'peak' => $peakIndex === null ? null : [
                'label' => $days[$peakIndex]->locale('id')->translatedFormat('d M Y'),
                'value' => $series[$peakIndex],
            ],
        ];
    }

    private function topContent(int $range): array
    {
        $from = now()->subDays($range - 1)->startOfDay();

        $rows = DB::table('views')
            ->select('viewable_type', 'viewable_id', DB::raw('COUNT(*) as total'))
            ->whereIn('viewable_type', [Portofolio::class, Blog::class])
            ->where('viewed_at', '>=', $from)
            ->groupBy('viewable_type', 'viewable_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        $portofolio = Portofolio::whereIn('id', $rows->where('viewable_type', Portofolio::class)->pluck('viewable_id'))->get()->keyBy('id');
        $blog = Blog::whereIn('id', $rows->where('viewable_type', Blog::class)->pluck('viewable_id'))->get()->keyBy('id');

        $max = max(1, (int) $rows->max('total'));
        $items = [];

        foreach ($rows as $row) {
            $isBlog = $row->viewable_type === Blog::class;
            $model = $isBlog ? $blog->get($row->viewable_id) : $portofolio->get($row->viewable_id);

            if (! $model) {
                continue; // konten sudah dihapus
            }

            $items[] = [
                'title' => $model->title,
                'type' => $isBlog ? 'Blog' : 'Portofolio',
                'views' => (int) $row->total,
                'percent' => (int) round(($row->total / $max) * 100),
                'image' => $model->image ? asset('storage/' . $model->image) : null,
                'admin_url' => route($isBlog ? 'patra.blog.show' : 'patra.portofolio.show', $model->id),
            ];
        }

        return $items;
    }

    /* ------------------------------------------------------------------ */
    /* Pesan masuk (Admin ke atas)                                        */
    /* ------------------------------------------------------------------ */

    private function messages(int $range): array
    {
        $days = $this->dayLabels($range);
        $from = $days[0];
        $prevFrom = $from->copy()->subDays($range);

        $daily = Kontak::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $from)
            ->groupBy('d')
            ->pluck('c', 'd');

        $series = [];
        foreach ($days as $day) {
            $series[] = (int) ($daily[$day->toDateString()] ?? 0);
        }

        $total = array_sum($series);
        $previous = Kontak::where('created_at', '>=', $prevFrom)->where('created_at', '<', $from)->count();

        $latest = Kontak::orderByDesc('created_at')->limit(5)->get()->map(function ($k) {
            return [
                'name' => $k->name,
                'email' => $k->email,
                'subject' => $k->subject,
                'excerpt' => \Illuminate\Support\Str::limit(strip_tags((string) $k->message), 70),
                'ago' => $k->created_at ? $k->created_at->locale('id')->diffForHumans() : '',
                'is_new' => $k->created_at ? $k->created_at->greaterThanOrEqualTo(now()->subDay()) : false,
                'initial' => mb_strtoupper(mb_substr(trim((string) $k->name) ?: '?', 0, 1)),
                'url' => route('patra.kontak.show', $k->id),
            ];
        })->all();

        return [
            'all_time' => Kontak::count(),
            'total' => $total,
            'previous' => $previous,
            'trend' => $this->trend($total, $previous),
            'today' => Kontak::where('created_at', '>=', now()->startOfDay())->count(),
            'series' => $series,
            'latest' => $latest,
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Invoice (Admin ke atas)                                            */
    /* ------------------------------------------------------------------ */

    private function money($amount, ?string $currency): string
    {
        $amount = (float) $amount;
        $currency = $currency ?: 'IDR';

        if ($currency === 'IDR') {
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }

        $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£'];

        return ($symbols[$currency] ?? $currency . ' ') . number_format($amount, 2, '.', ',');
    }

    private function invoices(): array
    {
        $outstanding = Invoice::query()
            ->selectRaw('currency, COUNT(*) as n, SUM(balance_due) as total')
            ->where('balance_due', '>', 0)
            ->groupBy('currency')
            ->get()
            ->map(fn ($r) => [
                'currency' => $r->currency ?: 'IDR',
                'count' => (int) $r->n,
                'formatted' => $this->money($r->total, $r->currency),
            ])->all();

        $overdue = Invoice::where('balance_due', '>', 0)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();

        $paid = Invoice::where('balance_due', '<=', 0)->where('total', '>', 0)->count();

        $latest = Invoice::orderByDesc('created_at')->limit(4)->get()->map(function ($i) {
            $balance = (float) $i->balance_due;
            $isOverdue = $balance > 0 && $i->due_date && $i->due_date->lt(now()->startOfDay());

            return [
                'number' => $i->invoice_number,
                'client' => $i->bill_to_name ?: '-',
                'total' => $this->money($i->total, $i->currency),
                'status' => $balance <= 0 && (float) $i->total > 0 ? 'Lunas' : ($isOverdue ? 'Jatuh tempo' : 'Belum lunas'),
                'badge' => $balance <= 0 && (float) $i->total > 0 ? 'success' : ($isOverdue ? 'danger' : 'warning'),
                'url' => route('patra.invoice.show', $i->id),
            ];
        })->all();

        return [
            'count' => Invoice::count(),
            'outstanding' => $outstanding,
            'overdue' => $overdue,
            'paid' => $paid,
            'latest' => $latest,
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Keuangan (SuperAdmin)                                              */
    /* ------------------------------------------------------------------ */

    private function finance(): array
    {
        $months = [];
        $start = now()->startOfMonth()->subMonths(5);

        for ($i = 0; $i < 6; $i++) {
            $m = $start->copy()->addMonths($i);
            $months[$m->format('Y-m')] = ['label' => $m->locale('id')->translatedFormat('M Y'), 'masuk' => 0.0, 'keluar' => 0.0];
        }

        $rows = FinanceTransaction::query()
            ->selectRaw('date, type, SUM(amount) as total')
            ->where('date', '>=', $start->toDateString())
            ->groupBy('date', 'type')
            ->get();

        foreach ($rows as $row) {
            $key = substr((string) $row->getRawOriginal('date'), 0, 7);

            if (isset($months[$key]) && in_array($row->type, ['masuk', 'keluar'], true)) {
                $months[$key][$row->type] += (float) $row->total;
            }
        }

        $saldo = FinanceTransaction::query()->toBase()
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'masuk' THEN amount ELSE -amount END), 0) AS saldo")
            ->first();

        $thisMonth = $months[now()->format('Y-m')] ?? ['masuk' => 0.0, 'keluar' => 0.0];

        return [
            'labels' => array_values(array_column($months, 'label')),
            'masuk' => array_map('floatval', array_values(array_column($months, 'masuk'))),
            'keluar' => array_map('floatval', array_values(array_column($months, 'keluar'))),
            'saldo' => $this->money($saldo->saldo ?? 0, 'IDR'),
            'saldo_negative' => ((float) ($saldo->saldo ?? 0)) < 0,
            'masuk_bulan_ini' => $this->money($thisMonth['masuk'], 'IDR'),
            'keluar_bulan_ini' => $this->money($thisMonth['keluar'], 'IDR'),
            'has_data' => FinanceTransaction::query()->exists(),
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Aktivitas admin (SuperAdmin) - tabel `logs` dari user-activity     */
    /* ------------------------------------------------------------------ */

    private function activity(): array
    {
        if (! Schema::hasTable('logs')) {
            return [];
        }

        $verbs = ['create' => 'menambah', 'edit' => 'mengubah', 'delete' => 'menghapus', 'login' => 'login', 'lockout' => 'terkunci'];
        $icons = ['create' => 'bx-plus-circle', 'edit' => 'bx-edit', 'delete' => 'bx-trash', 'login' => 'bx-log-in', 'lockout' => 'bx-lock'];
        $colors = ['create' => 'success', 'edit' => 'info', 'delete' => 'danger', 'login' => 'secondary', 'lockout' => 'warning'];

        $rows = DB::table('logs')
            ->leftJoin('users', 'users.id', '=', 'logs.user_id')
            ->select('logs.log_type', 'logs.table_name', 'logs.log_date', 'users.name as user_name')
            ->orderByDesc('logs.id')
            ->limit(8)
            ->get();

        return $rows->map(function ($r) use ($verbs, $icons, $colors) {
            $type = strtolower((string) $r->log_type);
            $table = $r->table_name ? str_replace('_', ' ', $r->table_name) : null;

            return [
                'user' => $r->user_name ?: 'Sistem',
                'verb' => $verbs[$type] ?? $type,
                'table' => $table,
                'icon' => $icons[$type] ?? 'bx-pulse',
                'color' => $colors[$type] ?? 'secondary',
                'ago' => $r->log_date ? Carbon::parse($r->log_date)->locale('id')->diffForHumans() : '',
            ];
        })->all();
    }

    /* ------------------------------------------------------------------ */
    /* Kesiapan website                                                   */
    /* ------------------------------------------------------------------ */

    private function checklist(): array
    {
        $items = [];
        $add = function (string $label, int $current, int $target, ?string $routeName, string $hint) use (&$items) {
            $items[] = [
                'label' => $label,
                'current' => $current,
                'target' => $target,
                'done' => $current >= $target,
                'url' => $routeName ? route($routeName) : null,
                'hint' => $hint,
            ];
        };

        if ($this->isAdminUp) {
            $p = PublicCache::pengaturan();
            $identity = $p ? collect([$p->website_logo, $p->website_name, $p->website_phone, $p->website_email, $p->website_address])->filter()->count() : 0;

            $add('Identitas website lengkap', $identity, 5, $this->isSuper ? 'patra.pengaturan.index' : null, 'Logo, nama, telepon, email, dan alamat');
            $add('Lokasi peta (Google Maps)', ($p && $p->website_maps) ? 1 : 0, 1, $this->isSuper ? 'patra.pengaturan.index' : null, 'Tampil di footer & halaman kontak');
            $add('Banner beranda', Banner::count(), 1, 'patra.banner.index', 'Minimal 1 banner');
            $add('Layanan', Layanan::count(), 1, 'patra.layanan.index', 'Minimal 1 layanan');
            $add('Paket digital aktif', Paket::where('is_active', true)->count(), 1, 'patra.paket.index', 'Untuk bagian harga');
            $add('Logo client / partner', ClientLogo::where('is_active', true)->count(), 1, 'patra.client-logo.index', 'Membangun kepercayaan');
        }

        $add('Portofolio', Portofolio::count(), 3, 'patra.portofolio.index', 'Minimal 3 proyek');
        $add('Artikel blog terbit', Blog::where('is_published', true)->count(), 1, 'patra.blog.index', 'Baik untuk SEO');
        $add('Testimoni aktif', Testimoni::where('is_active', true)->count(), 1, 'patra.testimoni.index', 'Bukti sosial');
        $add('FAQ aktif', Faq::where('is_active', true)->count(), 3, 'patra.faq.index', 'Minimal 3 pertanyaan');

        $done = collect($items)->where('done', true)->count();

        return [
            'items' => $items,
            'done' => $done,
            'total' => count($items),
            'percent' => count($items) ? (int) round($done / count($items) * 100) : 0,
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Aksi cepat                                                         */
    /* ------------------------------------------------------------------ */

    private function quickActions(): array
    {
        $actions = [
            ['Portofolio baru', 'patra.portofolio.create', 'bx-briefcase-alt-2'],
            ['Tulis artikel', 'patra.blog.create', 'bx-edit-alt'],
        ];

        if ($this->isAdminUp) {
            $actions[] = ['Buat invoice', 'patra.invoice.create', 'bx-receipt'];
            $actions[] = ['Lihat pesan', 'patra.kontak.index', 'bx-envelope'];
        }

        if ($this->isSuper) {
            $actions[] = ['Catat kas', 'patra.finance.create', 'bx-wallet'];
        }

        return array_map(fn ($a) => ['label' => $a[0], 'url' => route($a[1]), 'icon' => $a[2]], $actions);
    }
}
