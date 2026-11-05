@extends('patra.layouts.master')
@section("title","Dashboard ~ EMPATRA DIGITECH")
@section("title_breadcumb","Dashboard")
@section('css')
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/patra/dashboard.css">
@endsection
@section("breadcumb","Dashboard")

@php
    $hour = (int) now()->format('G');
    $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));
    $roleLabel = $isSuper ? 'Super Admin' : ($isAdminUp ? 'Admin' : 'Moderator');
    $firstName = \Illuminate\Support\Str::of(Auth::user()->name)->trim()->explode(' ')->first();

    $trendBadge = function ($trend) {
        if ($trend === null) { return ['pd-trend--up', 'bx-trending-up', 'Baru']; }
        if ($trend > 0)      { return ['pd-trend--up', 'bx-trending-up', '+' . $trend . '%']; }
        if ($trend < 0)      { return ['pd-trend--down', 'bx-trending-down', $trend . '%']; }
        return ['pd-trend--flat', 'bx-minus', '0%'];
    };
    [$tvClass, $tvIcon, $tvText] = $trendBadge($traffic['trend']);
    $ringCirc = 2 * M_PI * 52;
@endphp

@section("content")
<div class="pd">

    {{-- ============ HERO ============ --}}
    <section class="pd-hero">
        <div class="pd-hero__main">
            <span class="pd-chip"><i class="bx bx-shield-quarter"></i> {{ $roleLabel }}</span>
            <h2 class="pd-hero__title">{{ $greeting }}, {{ $firstName }}</h2>
            <p class="pd-hero__sub">
                <span id="pd-date">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</span>
                <span class="pd-hero__dot" aria-hidden="true"></span>
                <span id="pd-clock" class="pd-hero__clock">{{ now()->format('H:i:s') }}</span>
            </p>

            <div class="pd-actions" role="group" aria-label="Aksi cepat">
                @foreach($quickActions as $qa)
                    <a href="{{ $qa['url'] }}" class="pd-action"><i class="bx {{ $qa['icon'] }}"></i>{{ $qa['label'] }}</a>
                @endforeach
                <a href="{{ route('home.home.index') }}" target="_blank" rel="noopener" class="pd-action pd-action--ghost">
                    <i class="bx bx-link-external"></i>Lihat website
                </a>
            </div>
        </div>

        <div class="pd-hero__ringwrap" title="Kesiapan website">
            <svg viewBox="0 0 120 120" width="132" height="132" role="img" aria-label="Kesiapan website {{ $checklist['percent'] }} persen">
                <circle cx="60" cy="60" r="52" class="pd-ring__track"/>
                <circle cx="60" cy="60" r="52" class="pd-ring__bar"
                        stroke-dasharray="{{ round($ringCirc, 2) }}"
                        stroke-dashoffset="{{ round($ringCirc * (1 - $checklist['percent'] / 100), 2) }}"
                        transform="rotate(-90 60 60)"/>
            </svg>
            <div class="pd-ring__label">
                <strong>{{ $checklist['percent'] }}%</strong>
                <span>Siap tayang</span>
            </div>
        </div>
    </section>

    {{-- ============ FILTER PERIODE ============ --}}
    <div class="pd-toolbar">
        <h3 class="pd-h3">Ringkasan</h3>
        <div class="pd-seg" role="group" aria-label="Periode data">
            @foreach($ranges as $r)
                <a href="{{ route('patra.patra.index', ['range' => $r]) }}"
                   class="pd-seg__item @if($range === $r) is-active @endif"
                   @if($range === $r) aria-current="true" @endif>{{ $r }} hari</a>
            @endforeach
        </div>
    </div>

    {{-- ============ KPI ============ --}}
    <section class="pd-kpis">
        <article class="pd-kpi">
            <div class="pd-kpi__top">
                <span class="pd-kpi__icon pd-bg-navy"><i class="bx bx-show"></i></span>
                <span class="pd-trend {{ $tvClass }}"><i class="bx {{ $tvIcon }}"></i>{{ $tvText }}</span>
            </div>
            <div class="pd-kpi__value" data-count="{{ $traffic['total'] }}">{{ number_format($traffic['total'], 0, ',', '.') }}</div>
            <div class="pd-kpi__label">Tampilan konten · {{ $range }} hari</div>
            <div class="pd-kpi__foot">{{ number_format($traffic['visitors'], 0, ',', '.') }} pengunjung unik · hari ini {{ $traffic['today'] }}</div>
            <div class="pd-spark-wrap"><canvas data-spark="traffic" aria-hidden="true"></canvas></div>
        </article>

        @if($messages)
            @php [$mClass, $mIcon, $mText] = $trendBadge($messages['trend']); @endphp
            <article class="pd-kpi">
                <div class="pd-kpi__top">
                    <span class="pd-kpi__icon pd-bg-red"><i class="bx bx-envelope"></i></span>
                    <span class="pd-trend {{ $mClass }}"><i class="bx {{ $mIcon }}"></i>{{ $mText }}</span>
                </div>
                <div class="pd-kpi__value" data-count="{{ $messages['total'] }}">{{ number_format($messages['total'], 0, ',', '.') }}</div>
                <div class="pd-kpi__label">Pesan masuk · {{ $range }} hari</div>
                <div class="pd-kpi__foot">{{ $messages['all_time'] }} total · hari ini {{ $messages['today'] }}</div>
                <div class="pd-spark-wrap"><canvas data-spark="messages" aria-hidden="true"></canvas></div>
            </article>
        @endif

        @if($invoices)
            <article class="pd-kpi">
                <div class="pd-kpi__top">
                    <span class="pd-kpi__icon pd-bg-amber"><i class="bx bx-receipt"></i></span>
                    @if($invoices['overdue'] > 0)
                        <span class="pd-trend pd-trend--down"><i class="bx bx-error-circle"></i>{{ $invoices['overdue'] }} jatuh tempo</span>
                    @else
                        <span class="pd-trend pd-trend--flat">{{ $invoices['count'] }} invoice</span>
                    @endif
                </div>
                @if(count($invoices['outstanding']))
                    <div class="pd-kpi__value pd-kpi__value--money">{{ $invoices['outstanding'][0]['formatted'] }}</div>
                    <div class="pd-kpi__label">Tagihan belum lunas</div>
                    <div class="pd-kpi__foot">
                        {{ $invoices['outstanding'][0]['count'] }} invoice
                        @if(count($invoices['outstanding']) > 1)
                            · +{{ count($invoices['outstanding']) - 1 }} mata uang lain
                        @endif
                    </div>
                @else
                    <div class="pd-kpi__value">0</div>
                    <div class="pd-kpi__label">Tagihan belum lunas</div>
                    <div class="pd-kpi__foot">{{ $invoices['paid'] }} invoice lunas</div>
                @endif
            </article>
        @endif

        @if($finance)
            <article class="pd-kpi">
                <div class="pd-kpi__top">
                    <span class="pd-kpi__icon pd-bg-green"><i class="bx bx-wallet"></i></span>
                    <span class="pd-trend {{ $finance['saldo_negative'] ? 'pd-trend--down' : 'pd-trend--up' }}">
                        <i class="bx {{ $finance['saldo_negative'] ? 'bx-trending-down' : 'bx-trending-up' }}"></i>Saldo kas
                    </span>
                </div>
                <div class="pd-kpi__value pd-kpi__value--money">{{ $finance['saldo'] }}</div>
                <div class="pd-kpi__label">Saldo seluruh transaksi</div>
                <div class="pd-kpi__foot">Bulan ini: +{{ $finance['masuk_bulan_ini'] }} / −{{ $finance['keluar_bulan_ini'] }}</div>
            </article>
        @endif

        @unless($isAdminUp)
            {{-- Moderator: tampilkan jumlah konten miliknya sebagai KPI --}}
            @foreach(array_slice($modules, 0, 3) as $m)
                <article class="pd-kpi">
                    <div class="pd-kpi__top">
                        <span class="pd-kpi__icon" style="background: {{ $m['hex'] }}"><i class="bx {{ $m['icon'] }}"></i></span>
                    </div>
                    <div class="pd-kpi__value" data-count="{{ $m['count'] }}">{{ $m['count'] }}</div>
                    <div class="pd-kpi__label">{{ $m['label'] }}</div>
                    <a class="pd-kpi__link" href="{{ $m['url'] }}">Kelola <i class="bx bx-right-arrow-alt"></i></a>
                </article>
            @endforeach
        @endunless
    </section>

    {{-- ============ GRAFIK UTAMA ============ --}}
    <section class="pd-grid pd-grid--charts">
        <article class="pd-card">
            <header class="pd-card__head">
                <div>
                    <h4>Tren aktivitas</h4>
                    <p>Tampilan konten @if($messages) &amp; pesan masuk @endif per hari ({{ $range }} hari terakhir)</p>
                </div>
                @if($traffic['peak'])
                    <span class="pd-pill">Puncak {{ $traffic['peak']['label'] }} · {{ $traffic['peak']['value'] }}</span>
                @endif
            </header>
            <div class="pd-chart pd-chart--lg">
                @if($traffic['total'] === 0 && (!$messages || $messages['total'] === 0))
                    <div class="pd-empty">
                        <i class="bx bx-line-chart"></i>
                        <strong>Belum ada aktivitas pada periode ini</strong>
                        <span>Grafik akan terisi saat pengunjung membuka portofolio / artikel{{ $messages ? ' atau mengirim pesan' : '' }}.</span>
                    </div>
                @else
                    <canvas id="pd-trend-chart" aria-label="Grafik tren aktivitas" role="img"></canvas>
                @endif
            </div>
        </article>

        <article class="pd-card">
            <header class="pd-card__head">
                <div>
                    <h4>Komposisi konten</h4>
                    <p>Jumlah data per modul</p>
                </div>
            </header>
            <div class="pd-chart pd-chart--lg">
                @if(count($composition))
                    <canvas id="pd-comp-chart" aria-label="Komposisi konten" role="img"></canvas>
                @else
                    <div class="pd-empty">
                        <i class="bx bx-pie-chart-alt-2"></i>
                        <strong>Belum ada konten</strong>
                        <span>Mulai dari banner, layanan, atau portofolio.</span>
                    </div>
                @endif
            </div>
        </article>
    </section>

    {{-- ============ KONTEN POPULER / PESAN / KESIAPAN ============ --}}
    <section class="pd-grid pd-grid--three">
        <article class="pd-card">
            <header class="pd-card__head">
                <div><h4>Konten terpopuler</h4><p>Berdasarkan tampilan · {{ $range }} hari</p></div>
            </header>
            @if(count($topContent))
                <ol class="pd-top">
                    @foreach($topContent as $i => $t)
                        <li>
                            <a href="{{ $t['admin_url'] }}" class="pd-top__row">
                                <span class="pd-top__rank">{{ $i + 1 }}</span>
                                <span class="pd-top__body">
                                    <span class="pd-top__title">{{ $t['title'] }}</span>
                                    <span class="pd-bar"><i style="width: {{ $t['percent'] }}%"></i></span>
                                </span>
                                <span class="pd-top__meta"><b>{{ number_format($t['views'], 0, ',', '.') }}</b><small>{{ $t['type'] }}</small></span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            @else
                <div class="pd-empty pd-empty--sm">
                    <i class="bx bx-trophy"></i>
                    <strong>Belum ada data tampilan</strong>
                    <span>Konten terlaris akan muncul di sini.</span>
                </div>
            @endif
        </article>

        @if($messages)
            <article class="pd-card">
                <header class="pd-card__head">
                    <div><h4>Pesan terbaru</h4><p>Dari formulir kontak</p></div>
                    <a class="pd-link" href="{{ route('patra.kontak.index') }}">Semua</a>
                </header>
                @if(count($messages['latest']))
                    <ul class="pd-msgs">
                        @foreach($messages['latest'] as $m)
                            <li>
                                <a href="{{ $m['url'] }}" class="pd-msg">
                                    <span class="pd-avatar">{{ $m['initial'] }}</span>
                                    <span class="pd-msg__body">
                                        <span class="pd-msg__name">{{ $m['name'] }} @if($m['is_new'])<em class="pd-new">baru</em>@endif</span>
                                        <span class="pd-msg__subject">{{ $m['subject'] }}</span>
                                        <span class="pd-msg__excerpt">{{ $m['excerpt'] }}</span>
                                    </span>
                                    <span class="pd-msg__time">{{ $m['ago'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="pd-empty pd-empty--sm">
                        <i class="bx bx-envelope-open"></i>
                        <strong>Kotak pesan kosong</strong>
                        <span>Pesan dari pengunjung akan tampil di sini.</span>
                    </div>
                @endif
            </article>
        @endif

        <article class="pd-card">
            <header class="pd-card__head">
                <div><h4>Kesiapan website</h4><p>{{ $checklist['done'] }} dari {{ $checklist['total'] }} item selesai</p></div>
            </header>
            <ul class="pd-check">
                @foreach($checklist['items'] as $c)
                    <li class="@if($c['done']) is-done @endif">
                        <span class="pd-check__icon"><i class="bx {{ $c['done'] ? 'bx-check' : 'bx-plus' }}"></i></span>
                        <span class="pd-check__body">
                            @if($c['url'] && !$c['done'])
                                <a href="{{ $c['url'] }}">{{ $c['label'] }}</a>
                            @else
                                <span>{{ $c['label'] }}</span>
                            @endif
                            <small>{{ $c['done'] ? 'Selesai' : $c['hint'] }}</small>
                        </span>
                        <span class="pd-check__count">{{ min($c['current'], $c['target']) }}/{{ $c['target'] }}</span>
                    </li>
                @endforeach
            </ul>
        </article>
    </section>

    {{-- ============ KEUANGAN & INVOICE ============ --}}
    @if($finance || $invoices)
        <section class="pd-grid pd-grid--two">
            @if($finance)
                <article class="pd-card">
                    <header class="pd-card__head">
                        <div><h4>Arus kas 6 bulan</h4><p>Pemasukan vs pengeluaran</p></div>
                        <a class="pd-link" href="{{ route('patra.finance.index') }}">Buku kas</a>
                    </header>
                    <div class="pd-chart pd-chart--md">
                        @if($finance['has_data'])
                            <canvas id="pd-finance-chart" aria-label="Grafik arus kas" role="img"></canvas>
                        @else
                            <div class="pd-empty pd-empty--sm">
                                <i class="bx bx-wallet-alt"></i>
                                <strong>Belum ada transaksi</strong>
                                <span>Catat atau impor transaksi kas untuk melihat grafik.</span>
                            </div>
                        @endif
                    </div>
                </article>
            @endif

            @if($invoices)
                <article class="pd-card">
                    <header class="pd-card__head">
                        <div><h4>Invoice terbaru</h4><p>{{ $invoices['count'] }} invoice · {{ $invoices['paid'] }} lunas</p></div>
                        <a class="pd-link" href="{{ route('patra.invoice.index') }}">Semua</a>
                    </header>
                    @if(count($invoices['latest']))
                        <ul class="pd-inv">
                            @foreach($invoices['latest'] as $inv)
                                <li>
                                    <a href="{{ $inv['url'] }}" class="pd-inv__row">
                                        <span class="pd-inv__main">
                                            <b>{{ $inv['number'] }}</b>
                                            <small>{{ $inv['client'] }}</small>
                                        </span>
                                        <span class="pd-inv__total">{{ $inv['total'] }}</span>
                                        <span class="pd-badge pd-badge--{{ $inv['badge'] }}">{{ $inv['status'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="pd-empty pd-empty--sm">
                            <i class="bx bx-receipt"></i>
                            <strong>Belum ada invoice</strong>
                            <span>Buat invoice pertama Anda.</span>
                        </div>
                    @endif
                </article>
            @endif
        </section>
    @endif

    {{-- ============ MODUL ============ --}}
    <section>
        <div class="pd-toolbar">
            <h3 class="pd-h3">Modul</h3>
        </div>
        <div class="pd-modules">
            @foreach($modules as $m)
                <a href="{{ $m['url'] }}" class="pd-module" style="--c: {{ $m['hex'] }}">
                    <span class="pd-module__icon"><i class="bx {{ $m['icon'] }}"></i></span>
                    <span class="pd-module__body">
                        <b>{{ number_format($m['count'], 0, ',', '.') }}</b>
                        <small>{{ $m['label'] }}</small>
                    </span>
                    <i class="bx bx-chevron-right pd-module__go"></i>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ============ AKTIVITAS ADMIN ============ --}}
    @if($activity !== null)
        <section class="pd-card pd-card--flush">
            <header class="pd-card__head">
                <div><h4>Aktivitas admin terbaru</h4><p>Jejak perubahan data di panel</p></div>
            </header>
            @if(count($activity))
                <ul class="pd-activity">
                    @foreach($activity as $a)
                        <li>
                            <span class="pd-activity__icon pd-activity__icon--{{ $a['color'] }}"><i class="bx {{ $a['icon'] }}"></i></span>
                            <span class="pd-activity__text">
                                <b>{{ $a['user'] }}</b> {{ $a['verb'] }}@if($a['table']) data <em>{{ $a['table'] }}</em>@endif
                            </span>
                            <time class="pd-activity__time">{{ $a['ago'] }}</time>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="pd-empty pd-empty--sm">
                    <i class="bx bx-history"></i>
                    <strong>Belum ada aktivitas tercatat</strong>
                </div>
            @endif
        </section>
    @endif

</div>
@endsection

@section('script')
    @php
        $dashPayload = [
            'traffic'     => ['labels' => $traffic['labels'], 'series' => $traffic['series']],
            'messages'    => $messages ? ['series' => $messages['series']] : null,
            'composition' => $composition,
            'finance'     => $finance ? [
                'labels' => $finance['labels'],
                'masuk'  => $finance['masuk'],
                'keluar' => $finance['keluar'],
                'show'   => $finance['has_data'],
            ] : null,
        ];
    @endphp
    <script>window.PATRA_DASH = @json($dashPayload);</script>
    <script src="{{ URL::to('/') }}/assets/js/patra/dashboard.js"></script>
@endsection
