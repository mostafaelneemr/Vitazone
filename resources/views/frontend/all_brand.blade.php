@extends('frontend.layouts.app')
@php
$meta_title = \App\SeoSetting::find(1)->{'brands_meta_title_' . locale()};
$meta_description = \App\SeoSetting::find(1)->{'brands_meta_description_' . locale()};
@endphp
@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection
@section('content')

<div class="py-4 gry-bg">
    <div class="mt-4">
        <div class="container">
            <div class="bg-white px-3 pt-3">
                <div class="row gutters-10">
                    @foreach (\App\Brand::all(['*','name_'.locale().' as name','slug_'.locale().' as slug']) as $brand)
                        <div class="col-xxl-2 col-lg-4 col-sm-6 text-center">
                            <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-3 mb-3 border rounded">
                                <img src="{{ uploaded_asset($brand->logo) }}" class="lazy img-fit" height="50" alt="{{ $brand->name }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
