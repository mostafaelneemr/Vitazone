@extends('frontend.layouts.app')

@php
$meta_title = \App\SeoSetting::find(1)->{'categories_meta_title_' . locale()};
$meta_description = \App\SeoSetting::find(1)->{'categories_meta_description_' . locale()};
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

    <div class="all-category-wrap py-4 gry-bg">
        <div class="sticky-top">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="bg-white all-category-menu">
                            <ul class="d-flex flex-wrap no-scrollbar">
                                @if (count($categories) > 12)
                                    @for ($i = 0; $i < 11; $i++)
                                        <li class="@php
                                            if ($i == 0) {
                                                echo 'active';
                                            }
                                        @endphp">
                                            <a href="#{{ $i }}" class="row no-gutters align-items-center">
                                                <div class="col-md-3">
                                                    <img loading="lazy" class="cat-image"
                                                        src="{{ uploaded_asset($categories[$i]->icon) }}">
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="cat-name">{{ $categories[$i]->name }}</div>
                                                </div>
                                            </a>
                                        </li>
                                    @endfor
                                    <li class="">
                                        <a href="#more" class="row no-gutters align-items-center">
                                            <div class="col-md-3">
                                                <i class="fa fa-ellipsis-h cat-icon"></i>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="cat-name">{{ translate('More Categories') }}</div>
                                            </div>
                                        </a>
                                    </li>
                                @else
                                    @foreach ($categories as $key => $category)
                                        <li class="@php
                                            if ($key == 0) {
                                                echo 'active';
                                            }
                                        @endphp">
                                            <a href="#{{ $key }}" class="row no-gutters align-items-center">
                                                <div class="col-md-3">
                                                    <img loading="lazy" class="cat-image"
                                                        src="{{ uploaded_asset($category->icon) }}">
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="cat-name">{{ $category->name }}</div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="container">
                @foreach ($categories as $key => $category)
                    @if (count($categories) > 12 && $key == 11)
                        <div class="mb-3 bg-white">
                            <div class="sub-category-menu active" id="more">
                                <h3 class="category-name border-bottom pb-2"><img loading="lazy"
                                        style="margin: 0 7px 10px 7px" class="cat-image"
                                        src="{{ uploaded_asset($category->icon) }}"><a
                                        href="{{ route('products.category', $category->{'slug_' . locale()}) }}">{{ $category->{'name_' . locale()} }}</a>
                                </h3>
                                <div class="row">
                                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
                                        <div class="col-lg-4 col-6">
                                            <h6 class="mb-3"><img loading="lazy" style="margin: 0 6px 5px 6px;width: 8%;"
                                                    class="cat-image"
                                                    src="{{ uploaded_asset(\App\Category::find($first_level_id)->icon) }}"><a
                                                    href="{{ route('products.category', \App\Category::find($first_level_id)->{'slug_' . locale()}) }}">{{ \App\Category::find($first_level_id)->{'name_' . locale()} }}</a>
                                            </h6>
                                            <ul class="mb-3">
                                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)

                                                    <li class="w-100"><a
                                                            href="{{ route('products.category', \App\Category::find($second_level_id)->{'slug_' . locale()}) }}"><img
                                                                loading="lazy" style="margin: 0 6px 5px 6px;width: 20px;"
                                                                class="cat-image"
                                                                src="{{ uploaded_asset(\App\Category::find($second_level_id)->icon) }}">
                                                            {{ \App\Category::find($second_level_id)->{'name_' . locale()} }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-3 bg-white">
                            <div class="sub-category-menu @php
                                if ($key < 12) {
                                    echo 'active';
                                }
                            @endphp" id="{{ $key }}">
                                <h3 class="category-name border-bottom pb-2"><img loading="lazy"
                                        style="margin: 0 7px 10px 7px" class="cat-image"
                                        src="{{ uploaded_asset($category->icon) }}"><a
                                        href="{{ route('products.category', $category->{'slug_' . locale()}) }}">{{ $category->name }}</a>
                                </h3>
                                <div class="row">
                                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
                                        <div class="col-lg-4 col-6">
                                            <h6 class="mb-3"><img loading="lazy" style="margin: 0 6px 5px 6px;width: 8%;"
                                                    class="cat-image"
                                                    src="{{ uploaded_asset(\App\Category::find($first_level_id)->icon) }}"><a
                                                    href="{{ route('products.category', \App\Category::find($first_level_id)->{'slug_' . locale()}) }}">{{ \App\Category::find($first_level_id)->{'name_' . locale()} }}</a>
                                            </h6>
                                            <ul class="mb-3">
                                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)
                                                    <li class="w-100">
                                                        <a
                                                            href="{{ route('products.category', \App\Category::find($second_level_id)->{'slug_' . locale()}) }}"><img
                                                                loading="lazy" style="margin: 0 6px 5px 6px;width: 20px;"
                                                                class="cat-image"
                                                                src="{{ uploaded_asset(\App\Category::find($second_level_id)->icon) }}">
                                                            {{ \App\Category::find($second_level_id)->{'name_' . locale()} }}</a>

                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

@endsection
