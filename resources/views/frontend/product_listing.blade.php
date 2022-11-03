@extends('frontend.layouts.app')
@php
    // dd($tag_query);
@endphp
@if (isset($category_id))
    @php
        $meta_title = \App\Category::find($category_id)->{'meta_title_'.locale()};
        $meta_description = \App\Category::find($category_id)->{'meta_description_'.locale()};
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Brand::find($brand_id)->{'meta_title_'.locale()};
        $meta_description = \App\Brand::find($brand_id)->{'meta_description_'.locale()};
    @endphp
@elseif($tag_query != null)
@php
    $meta_title = $tag_query->{'meta_title_'.locale()};
    $meta_description = $tag_query->{'meta_description_'.locale()};
@endphp
@else
    @php
        $meta_title = \App\SeoSetting::find(1)->{'products_meta_title_' . locale()};
        $meta_description = \App\SeoSetting::find(1)->{'products_meta_description_' . locale()};
    @endphp
@endif

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

<style>
    #messageeeee {
        text-align: center;
        background-color: #333;
        color: #fff;
        width: 90%;
        z-index: 100;
        margin: auto;
        overflow: auto
    }

</style>
@php
$deep_to_open = null;
if($brand_id != null){
$deep_to_open = route('products.brand', [$brand_id]);
}
if($category_id != null){
    $deep_to_open = route('products.category', [$category_id]);
}
@endphp
@if ($open_app == true && $deep_to_open != null)
    <div id="messageeeee">
        <div style="padding: 5px;">
            <div id="inner-message" class="alert alert-error">
                @if (locale() == 'en')
                    <div class="container">
                        <div class="float-left">
                            <h6>{{ translate('Shop in the app') }}</h6>
                            <p>{{ translate('Available on Google Play') }}</p>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow"
                                href="{{ $deep_to_open }}" target="blank">{{ translate('To App') }}</a>
                        </div>
                    </div>
                @else
                    <div class="container">
                        <div class="float-right">
                            <h6>{{ translate('Shop in the app') }}</h6>
                            <p>{{ translate('Available on Google Play') }}</p>
                        </div>
                        <div class="float-left">
                            <a class="btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow"
                                href="{{ $deep_to_open }}"
                                target="blank">{{ translate('To App') }}</a>
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
@endif
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul class="breadcrumb">
                        <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                        @if ($brand_id != null)
                            <li><a href="{{ route('brands.all') }}">{{ translate('All Brands')}}</a></li>
                            <li ><a href="{{ route('products.brand', \App\Brand::find($brand_id)->{'slug_'.locale()}) }}">{{ \App\Brand::find($brand_id)->{'name_'.locale()} }}</a></li>
                        @elseif($category_id != null)
                            <li><a href="{{ route('products') }}">{{ translate('All Categories')}}</a></li>
                        @endif
                        @if ($crumbArr['category_id'] != null)
                    <li ><a href="{{ route('products.category', \App\Category::find($crumbArr["category_id"])->{'slug_' . locale()}) }}">{{ \App\Category::find($crumbArr["category_id"])->{'name_'.locale()} }}</a></li>
                    @endif
                    @if ($crumbArr['sub_category_id'] != null)
                    <li ><a href="{{ route('products.category', \App\Category::find($crumbArr["sub_category_id"])->{'slug_' . locale()}) }}">{{ \App\Category::find($crumbArr["sub_category_id"])->{'name_'.locale()} }}</a></li>
                    @endif
                    @if ($crumbArr['sub_sub_category_id'] != null)
                    <li ><a href="{{ route('products.category', \App\Category::find($crumbArr["sub_sub_category_id"])->{'slug_' . locale()}) }}">{{ \App\Category::find($crumbArr["sub_sub_category_id"])->{'name_'.locale()} }}</a></li>

                    @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="gry-bg py-4">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="" method="GET">
                <div class="row">
                <div class="col-xl-3 side-filter d-xl-block">
                    <div class="filter-overlay filter-close"></div>
                    <div class="filter-wrapper c-scrollbar">
                        <div class="filter-title d-flex d-xl-none justify-content-between pb-3 align-items-center">
                            <h3 class="h6">Filters</h3>
                            <button type="button" class="close filter-close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                {{ translate('Categories')}}
                            </div>
                            <div class="box-content">
                                <div class="category-filter">
                                    <ul>
                                        @if(!isset($category_id))
                                            @foreach(\App\Category::where('level', 0)->where('published',true)->select(['*','name_'.locale().' as name'])->get() as $category)
                                                <li class=""><a href="{{ route('products.category', $category->{'slug_' . locale()}) }}"><img loading="lazy" style="margin: 0 6px 5px 6px;width: 8%;"  class="cat-image" src="{{ uploaded_asset($category->icon) }}"> {{  $category->name }}</a></li>
                                            @endforeach
                                            @else
                                            <li class="active"><a href="{{ route('products') }}">{{ translate('All Categories')}}</a></li>
                                            @if (\App\Category::find($category_id)->parent_id != 0)
                                                 <li class="active"><a href="{{ route('products.category', \App\Category::find(\App\Category::find($category_id)->parent_id)->{'slug_' . locale()}) }}"><img loading="lazy" style="margin: 0 6px 5px 6px;width: 8%;"  class="cat-image" src="{{ uploaded_asset(\App\Category::find(\App\Category::find($category_id)->parent_id)->icon) }}">{{  \App\Category::find(\App\Category::find($category_id)->parent_id)->{'name_'.locale()} }}</a></li>

                                            @endif
                                            <li class="active"><a href="{{ route('products.category', \App\Category::find($category_id)->{'slug_' . locale()}) }}"><img loading="lazy" style="margin: 0 6px 5px 6px;width: 8%;"  class="cat-image" src="{{ uploaded_asset(\App\Category::find($category_id)->icon) }}">{{  \App\Category::find($category_id)->{'name_'.locale()} }}</a></li>
                                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category_id) as $key => $id)
                                                <li class="child"><a href="{{ route('products.category', \App\Category::find($id)->{'slug_' . locale()}) }}"><img loading="lazy" style="margin: 0 6px 5px 6px;width: 6%;"  class="cat-image" src="{{ uploaded_asset(\App\Category::find($id)->icon) }}"> {{  \App\Category::find($id)->{'name_'.locale()} }}</a></li>
                                                @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                {{ translate('Price range')}}
                            </div>
                            <div class="box-content">
                                <div class="range-slider-wrapper mt-3">
                                    <!-- Range slider container -->
                                    <div
                                        id="input-slider-range"
                                        data-range-value-min="@if(count(\App\Product::query()->get()) < 1) 0 @else {{ filter_products(\App\Product::query())->get()->min('unit_price') }} @endif"

                                        data-range-value-max="@if(count(\App\Product::query()->get()) < 1) 0 @else {{ filter_products(\App\Product::query())->get()->max('unit_price') }} @endif"></div>

                                    <!-- Range slider values -->
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="range-slider-value value-low"
                                                @if (isset($min_price))
                                                    data-range-value-low="{{ $min_price }}"
                                                @elseif($products->min('unit_price') > 0)
                                                    data-range-value-low="{{ $products->min('unit_price') }}"
                                                @else
                                                    data-range-value-low="0"
                                                @endif
                                                id="input-slider-range-value-low">
                                        </div>

                                        <div class="col-6 text-right">
                                            <span class="range-slider-value value-high"
                                                @if (isset($max_price))
                                                    data-range-value-high="{{ $max_price }}"
                                                @elseif($products->max('unit_price') > 0)
                                                    data-range-value-high="{{ $products->max('unit_price') }}"
                                                @else
                                                    data-range-value-high="0"
                                                @endif
                                                id="input-slider-range-value-high">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                {{ translate('Filter by color')}}
                            </div>
                            <div class="box-content">
                                <!-- Filter by color -->
                                <ul class="list-inline checkbox-color checkbox-color-circle mb-0">
                                    @foreach ($all_colors as $key => $color)
                                        <li>
                                            <input type="radio" id="color-{{ $key }}" name="color" value="{{ $color }}" @if(isset($selected_color) && $selected_color == $color) checked @endif onchange="filter()">
                                            <label style="background: {{ $color }};" for="color-{{ $key }}" data-toggle="tooltip" data-original-title="{{ \App\Color::where('code', $color)->first()->name }}"></label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="bg-white sidebar-box mb-3">
                            <div class="box-title text-center">
                                {{ translate('Tags')}}
                            </div>
                            <div class="box-content">
                                <?php
                                        $tags = array();
                                        if(isset($category_id)){
                                            $tags = explode(',', \App\Category::find($category_id)->tag_ids);
                                        }
                                    
                                    ?>
                                    @if(count($tags) > 0)
                                        @foreach($tags as $tag) 
                                            <?php
                                            $language = locale();

                                                $hashtag = \App\Tags::select(['*','name_'.$language.' as name'])->where('id', $tag)->first();
                                               
                                            ?>
                                            @isset($hashtag->name)
                                            <a href="{{route('suggestion.search', $hashtag->name)}}"><span> {{ $hashtag->name }}, </span></a>
                                            @endisset
                                          
                                        @endforeach
                                    @endif
                            </div>
                        </div>

                        @foreach ($attributes as $key => $attribute)
                            @if (\App\Attribute::select(['*','name_'.locale().' as name'])->find($attribute['id']) != null)
                                <div class="bg-white sidebar-box mb-3">
                                    <div class="box-title text-center">
                                        Filter by {{ \App\Attribute::select(['*','name_'.locale().' as name'])->find($attribute['id'])->name }}
                                    </div>
                                    <div class="box-content">
                                        <!-- Filter by others -->
                                        <div class="filter-checkbox">
                                            @if(array_key_exists('values', $attribute))
                                                @foreach ($attribute['values'] as $key => $value)
                                                    @php
                                                        $flag = false;
                                                        if(isset($selected_attributes)){
                                                            foreach ($selected_attributes as $key => $selected_attribute) {
                                                                if($selected_attribute['id'] == $attribute['id']){
                                                                    if(in_array($value, $selected_attribute['values'])){
                                                                        $flag = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="attribute_{{ $attribute['id'] }}_value_{{ $value }}" name="attribute_{{ $attribute['id'] }}[]" value="{{ $value }}" @if ($flag) checked @endif onchange="filter()">
                                                        <label for="attribute_{{ $attribute['id'] }}_value_{{ $value }}">{{ $value }}</label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        {{-- <button type="submit" class="btn btn-styled btn-block btn-base-4">Apply filter</button> --}}
                    </div>
                </div>
                <div class="col-xl-9">
                    @if ($category_front != null)
                    <div class="title-des-section">
                        <h3 style="color: #12CBC4">
                            {{ $category_front->title }}
                        </h3>
                        <p>
                            {{ $category_front->description }}
                        </p>
                    </div>
                    @endif
                   
                    <!-- <div class="bg-white"> -->
                        @isset($category_id)
                            <input type="hidden" name="category" value="{{ \App\Category::find($category_id)->slug }}">
                        @endisset
                        

                        <div class="sort-by-bar row no-gutters bg-white mb-3 px-3 pt-2">
                            <div class="col-xl-4 d-flex d-xl-block justify-content-between align-items-end ">
                                <div class="sort-by-box flex-grow-1">
                                    <div class="form-group">
                                        <label>{{ translate('Search')}}</label>
                                        <div class="search-widget">
                                            <input class="form-control input-lg" type="text" name="q" placeholder="{{ translate('Search products')}}" @isset($query) value="{{ $query }}" @endisset>
                                            <button type="submit" class="btn-inner">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-xl-none ml-3 form-group">
                                    <button type="button" class="btn p-1 btn-sm" id="side-filter">
                                        <i class="la la-filter la-2x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xl-7 offset-xl-1">
                                <div class="row no-gutters">
                                    <div class="col-4">
                                        <div class="sort-by-box px-1">
                                            <div class="form-group">
                                                <label>{{ translate('Sort by')}}</label>
                                                <select class="form-control sortSelect" data-minimum-results-for-search="Infinity" name="sort_by" onchange="filter()">
                                                    <option value="1" @isset($sort_by) @if ($sort_by == '1') selected @endif @endisset>{{ translate('Newest')}}</option>
                                                    <option value="2" @isset($sort_by) @if ($sort_by == '2') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                                    <option value="3" @isset($sort_by) @if ($sort_by == '3') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                                    <option value="4" @isset($sort_by) @if ($sort_by == '4') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="sort-by-box px-1">
                                            <div class="form-group">
                                                <label>{{ translate('Brands')}}</label>
                                                <select class="form-control sortSelect" data-placeholder="{{ translate('All Brands')}}" name="brand" onchange="filter()">
                                                    <option value="">{{ translate('All Brands')}}</option>
                                                    @foreach (\App\Brand::all(['*','name_'.locale() .' as name']) as $brand)
                                                        <option value="{{ $brand->{'slug_' . locale()} }}" @isset($brand_id) @if ($brand_id == $brand->id) selected @endif @endisset>{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="sort-by-box px-1">
                                            <div class="form-group">
                                                <label>{{ translate('Sellers')}}</label>
                                                <select class="form-control sortSelect" data-placeholder="{{ translate('All Sellers')}}" name="seller_id" onchange="filter()">
                                                    <option value="">{{ translate('All Sellers')}}</option>
                                                    @foreach (\App\Seller::all() as $key => $seller)
                                                        @if ($seller->user != null && $seller->user->shop != null)
                                                            <option value="{{ $seller->id }}" @isset($seller_id) @if ($seller_id == $seller->id) selected @endif @endisset>{{ $seller->user->shop->{'name_'.locale()} }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="min_price" value="">
                        <input type="hidden" name="max_price" value="">
                        <!-- <hr class=""> -->
                        <div class="products-box-bar p-3 bg-white">
                            <div class="row sm-no-gutters gutters-5">
                                @foreach ($products as $key => $product)
                                    <div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6">
                                        <div class="product-box-2 bg-white alt-box my-md-2">
                                            <div class="position-relative overflow-hidden">
                                                <a href="{{ route('product', $product->{'slug_'.locale()}) }}" class="d-block product-image h-100 text-center" tabindex="0">
                                                    <img class="img-fit lazy" src="{{ my_asset('frontend/images/placeholder.jpg') }}" data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{  $product->{'name_'.locale()} }}">
                                                </a>
                                                <div class="product-btns clearfix">
                                                    <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" type="button">
                                                        <i class="la la-heart-o"></i>
                                                    </button>
                                                    <button class="btn add-compare" title="Add to Compare" onclick="addToCompare({{ $product->id }})" type="button">
                                                        <i class="la la-refresh"></i>
                                                    </button>
                                                    <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal({{ $product->id }})" type="button">
                                                        <i class="la la-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="p-md-3 p-2">
                                                <div class="price-box">
                                                    @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                        <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                                    @endif
                                                    <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                                </div>
                                                <div class="star-rating star-rating-sm mt-1">
                                                    {{ renderStarRating($product->rating) }}
                                                </div>
                                                <h2 class="product-title p-0">
                                                    <a href="{{ route('product', $product->{'slug_'.locale()}) }}" class=" text-truncate">{{  $product->{'name_'.locale()} }}</a>
                                                </h2>
                                                {{-- @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated)
                                                    <div class="club-point mt-2 bg-soft-base-1 border-light-base-1 border">
                                                        {{  translate('Club Point') }}:
                                                        <span class="strong-700 float-right">{{ $product->earn_point }}</span>
                                                    </div>
                                                @endif --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="products-pagination bg-white p-3">
                            <nav aria-label="Center aligned pagination">
                                <ul class="pagination justify-content-center">
                                    {{ $products->links() }}
                                </ul>
                            </nav>
                        </div>

                    <!-- </div> -->
                </div>
            </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
