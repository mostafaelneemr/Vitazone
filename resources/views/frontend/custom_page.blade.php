@extends('frontend.layouts.app')

@section('meta_title'){{ $page->{'meta_title_'.locale()} }}@stop

@section('meta_description'){{ $page->{'meta_description_'.locale()} }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->{'meta_title_'.locale()} }}">
    <meta itemprop="description" content="{{ $page->{'meta_description_'.locale()} }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->{'meta_title_'.locale()} }}">
    <meta name="twitter:description" content="{{ $page->{'meta_description_'.locale()} }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">
    <meta name="twitter:data1" content="{{ single_price($page->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('product', $page->{'slug_'.locale()}) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
    <meta property="og:description" content="{{ $page->{'meta_description_'.locale()} }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:price:amount" content="{{ single_price($page->unit_price) }}" />
@endsection

@section('content')
<style>
    .slice--offset{
        background-image: url({{ uploaded_asset($page->cover_photo) }});
    }
</style>
<section class="slice--offset parallax-section parallax-section-lg b-xs-bottom gry-bg">
    <div class="container">
        <div class="row py-3">
            <div class="col-lg-6 col-md-8">
                <h1 class="heading heading-1 strong-400 text-normal">
                    {{ $page->{'title_'.locale()} }}
                </h1>
            </div>
        </div>
    </div>
</section>
<section class="bg-white py-5">
	<div class="container">
        <div class="aiz-custom-page">
		    @php echo $page->{'content_'.locale()}; @endphp
        </div>
	</div>
</section>
@endsection
