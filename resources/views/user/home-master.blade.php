@extends('layouts.user')

@section('content')

    @include('user.home-partials.hero')

    @include('user.home-partials.categories')

    @include('user.home-partials.landing-products')

    @include('user.home-partials.services')

    @include('user.home-partials.testimonials')

@endsection
