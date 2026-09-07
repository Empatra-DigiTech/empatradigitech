@extends('home.layouts.master')
@section("title","Home | EMPATRA DIGITECH")
@section('meta_description', 'Empatra Digitech menyediakan jasa pembuatan website, aplikasi, dan konsultasi digital untuk membantu bisnis Anda berkembang secara online.')

@section('content')
    @include('home.partials.hero')
    @include('home.partials.clients')
    @include('home.partials.carousel')
    
    @include('home.partials.about')
    @include('home.partials.services')
    @include('home.partials.portfolio')
    @include('home.partials.process')
    @include('home.partials.price')
    @include('home.partials.testimonial')
    @include('home.partials.faq')
@endsection