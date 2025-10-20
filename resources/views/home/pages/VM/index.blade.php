@extends('home.layouts.master')
@section("title","Struktur Organisasi | BALAI UJI KIR MALANG KOTA")
@section("content")
<section id="so" class="so section" style="margin-top: 2cm;">
  <div class="so-bg" >
    <img src="{{URL::to('/')}}/assets/img/VM_New.png" alt="">
  </div>
</section>

{{-- team --}}
<section id="team" class="mt-5">
  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up" style="padding-top: 60px;">
    <h2>Visi Misi</h2>
    <img src="{{ asset('storage/'.$result->image) }}" style="width:100%;height:auto;">
  </div><!-- End Section Title -->
</section>
@endsection
