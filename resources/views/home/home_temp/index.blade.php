@extends('home.home_temp.layouts.master')

@section('content')
    @include('home.home_temp.partials.hero')
    @include('home.home_temp.partials.carousel')
    
    @include('home.home_temp.partials.about')
    @include('home.home_temp.partials.services')
    @include('home.home_temp.partials.portfolio')
    @include('home.home_temp.partials.process')
    @include('home.home_temp.partials.price')
    @include('home.home_temp.partials.testimonial')
    @include('home.home_temp.partials.faq')
@endsection