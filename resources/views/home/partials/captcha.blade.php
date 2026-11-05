{{-- Widget reCAPTCHA untuk form publik. Tampil hanya bila NOCAPTCHA_SECRET & NOCAPTCHA_SITEKEY terisi.
     Dipakai form kontak dan halaman login. Pakai di dalam <form>:  @include('home.partials.captcha')  --}}
@if (\App\Support\Captcha::enabled())
    {!! NoCaptcha::renderJs('id') !!}
    {!! NoCaptcha::display() !!}
@endif
