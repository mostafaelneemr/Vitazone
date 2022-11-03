@extends('frontend.layouts.app')
@php
$meta_title = \App\SeoSetting::find(1)->{'blog_meta_title_' . locale()};
$meta_description = \App\SeoSetting::find(1)->{'blog_meta_description_' . locale()};
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
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul class="breadcrumb">
                        <li><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                        <li><a href="{{ route('blog') }}">{{ translate('Blog') }}</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <section class="pb-4">
        <div class="container">
            <div class="card-columns">
                @foreach ($blogs as $blog)
                    <div class="card mb-3 overflow-hidden shadow-sm">
                        <a href="{{ url('blog') . '/' . $blog->{'slug_' . locale()} }}" class="text-reset d-block">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($blog->banner) }}" alt="{{ $blog->title }}"
                                class="img-fluid lazyload ">
                        </a>
                        <div class="p-4">
                            <h2 class="fs-18 fw-600 mb-1">
                                <a href="{{ url('blog') . '/' . $blog->{'slug_' . locale()} }}" class="text-reset">
                                    {{ $blog->{'title_' . locale()} }}
                                </a>
                            </h2>
                            @if ($blog->category != null)
                                <div class="mb-2 opacity-50">
                                    <i>{{ $blog->category->{'category_name_' . locale()} }}</i>
                                </div>
                            @endif
                            <p class="opacity-70 mb-4">
                                {{ $blog->{'short_description_' . locale()} }}
                            </p>
                            <a href="{{ url('blog') . '/' . $blog->{'slug_' . locale()} }}"
                                class="btn btn-base-1 btn-icon-left">
                                {{ translate('View More') }}
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="aiz-pagination aiz-pagination-center mt-4">
                {{ $blogs->links() }}
            </div>
        </div>
    </section>
@endsection
