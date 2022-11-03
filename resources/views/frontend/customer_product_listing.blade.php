@extends('frontend.layouts.app')

@if (isset($subsubcategory_id))
    @php
        $meta_title = \App\SeoSetting::find(1)->{'customer_products_meta_title_concat_' . locale()} .  ' ' . \App\Category::find($subsubcategory_id)->{'meta_title_' . locale()};
        $meta_description = \App\SeoSetting::find(1)->{'customer_products_meta_description_concat_' . locale()} .  ' ' .\App\Category::find($subsubcategory_id)->{'meta_description_' . locale()};
    @endphp
@elseif (isset($subcategory_id))
    @php
        $meta_title = \App\SeoSetting::find(1)->{'customer_products_meta_title_concat_' . locale()} .  ' ' . \App\Category::find($subcategory_id)->{'meta_title_' . locale()};
        $meta_description = \App\SeoSetting::find(1)->{'customer_products_meta_description_concat_' . locale()} .  ' ' .\App\Category::find($subcategory_id)->{'meta_description_' . locale()};
    @endphp
@elseif (isset($category_id))
    @php
        $meta_title = \App\SeoSetting::find(1)->{'customer_products_meta_title_concat_' . locale()} .  ' ' . \App\Category::find($category_id)->{'meta_title_' . locale()};
        $meta_description = \App\SeoSetting::find(1)->{'customer_products_meta_description_concat_' . locale()} .  ' ' .\App\Category::find($category_id)->{'meta_description_' . locale()};
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Brand::find($brand_id)->{'meta_title_' . locale()};
        $meta_description = \App\Brand::find($brand_id)->{'meta_description_' . locale()};
    @endphp
@else
    @php
        $meta_title = \App\SeoSetting::find(1)->{'customer_products_meta_title_' . locale()};
        $meta_description = \App\SeoSetting::find(1)->{'customer_products_meta_description_' . locale()};
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

    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        @if (!isset($category_id))
                            <li class="breadcrumb-item fw-600  text-dark">
                                <a class="text-reset"
                                    href="{{ route('customer.products') }}">"{{ translate('All Categories') }}"</a>
                            </li>
                        @else
                            <li class="breadcrumb-item opacity-50">
                                <a class="text-reset"
                                    href="{{ route('customer.products') }}">{{ translate('All Categories') }}</a>
                            </li>
                        @endif
                        @if (isset($category_id))
                            <li class="text-dark fw-600 breadcrumb-item">
                                <a class="text-reset"
                                    href="{{ route('customer_products.category', \App\Category::find($category_id)->{'slug_' . locale()}) }}">"{{ \App\Category::find($category_id)->{'slug_' . locale()} }}"</a>
                            </li>
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
                                <h3 class="h6">{{ translate('Filters') }}</h3>
                                <button type="button" class="close filter-close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="bg-white sidebar-box mb-3">
                                <div class="box-title text-center">
                                    {{ translate('Categories') }}
                                </div>
                                <div class="box-content">
                                    <div class="category-filter">
                                        <ul class="list-unstyled">
                                            @if (!isset($category_id))
                                                @foreach (\App\Category::where('level', 0)->where('published',true)->get() as $category)
                                                    <li class="mb-2 ml-2">
                                                        <a class="text-reset fs-14"
                                                            href="{{ route('customer_products.category', $category->{'slug_' . locale()}) }}">{{ $category->{'name_' . locale()} }}</a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="mb-2">
                                                    <a class="text-reset fs-14 fw-600"
                                                        href="{{ route('customer.products') }}">
                                                        <i class="fa fa-angle-left"></i>
                                                        {{ translate('All Categories') }}
                                                    </a>
                                                </li>
                                                @if (\App\Category::find($category_id)->parent_id != 0)
                                                    <li class="mb-2">
                                                        <a class="text-reset fs-14 fw-600"
                                                            href="{{ route('customer_products.category', \App\Category::find(\App\Category::find($category_id)->parent_id)->{'slug_' . locale()}) }}">
                                                            <i class="fa fa-angle-left"></i>
                                                            {{ \App\Category::find(\App\Category::find($category_id)->parent_id)->{'name_' . locale()} }}
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="mb-2">
                                                    <a class="text-reset fs-14 fw-600"
                                                        href="{{ route('customer_products.category', \App\Category::find($category_id)->{'slug_' . locale()}) }}">
                                                        <i class="fa fa-angle-left"></i>
                                                        {{ \App\Category::find($category_id)->{'name_' . locale()} }}
                                                    </a>
                                                </li>
                                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category_id) as $key => $id)
                                                    <li class="ml-4 mb-2">
                                                        <a class="text-reset fs-14"
                                                            href="{{ route('customer_products.category', \App\Category::find($id)->{'slug_' . locale()}) }}">{{ \App\Category::find($id)->{'name_' . locale()} }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="sort-by-bar row no-gutters bg-white mb-3 px-3 pt-2">
                            <div class="col-xl-4 d-flex d-xl-block justify-content-between align-items-end ">
                                <div class="sort-by-box flex-grow-1">
                                    <div class="form-group">
                                        <label>{{ translate('Search') }}</label>
                                        <div class="search-widget">
                                            <input class="form-control input-lg" type="text" name="q"
                                                placeholder="{{ translate('Search products') }}" @isset($query)
                                                value="{{ $query }}" @endisset>
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
                                                <label>{{ translate('Sort by') }}</label>
                                                <select class="form-control sortSelect"
                                                    data-minimum-results-for-search="Infinity" name="sort_by"
                                                    onchange="filter()">
                                                    <option value="1" @isset($sort_by) @if ($sort_by == '1') selected @endif
                                                    @endisset>{{ translate('Newest') }}</option>
                                                <option value="2" @isset($sort_by) @if ($sort_by == '2') selected @endif
                                                @endisset>{{ translate('Oldest') }}</option>
                                            <option value="3" @isset($sort_by) @if ($sort_by == '3') selected @endif
                                            @endisset>{{ translate('Price low to high') }}</option>
                                        <option value="4" @isset($sort_by) @if ($sort_by == '4') selected @endif
                                        @endisset>{{ translate('Price high to low') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="sort-by-box px-1">
                            <div class="form-group">
                                <label>{{ translate('Condition') }}</label>
                                <select class="form-control sortSelect"
                                    data-minimum-results-for-search="Infinity" name="condition"
                                    onchange="filter()">
                                    <option value="">{{ translate('All Type') }}</option>
                                    <option value="new" @isset($condition) @if ($condition == 'new') selected @endif @endisset>{{ translate('New') }}</option>
                                    <option value="used" @isset($condition) @if ($condition == 'used') selected @endif @endisset>{{ translate('Used') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="sort-by-box px-1">
                            <div class="form-group">
                                <label>{{ translate('Brands') }}</label>
                                <select class="form-control sortSelect"
                                    data-placeholder="{{ translate('All Brands') }}" name="brand"
                                    onchange="filter()">
                                    <option value="">{{ translate('All Brands') }}</option>
                                    @foreach (\App\Brand::all() as $brand)
                                        <option value="{{ $brand->{'slug_' . locale()} }}"
                                            @isset($brand_id) @if ($brand_id == $brand->id) selected @endif @endisset>
                                            {{ $brand->{'name_' . locale()} }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <hr class=""> -->
        <div class="products-box-bar p-3 bg-white">
            <div class="row sm-no-gutters gutters-5">
                @foreach ($customer_products as $key => $product)
                    <div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6">
                        <div class="product-box-2 bg-white alt-box my-md-2">
                            <div class="position-relative overflow-hidden">
                                <a href="{{ route('customer.product', $product->{'slug_' . locale()}) }}"
                                    class="d-block product-image h-100 text-center" tabindex="0">
                                    <img class="img-fit lazyload"
                                        src="{{ my_asset('frontend/images/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->{'name_' . locale()} }}">
                                </a>
                            </div>
                            <div class="p-md-3 p-2">
                                <div class="price-box">
                                    <span
                                        class="product-price strong-600">{{ single_price($product->unit_price) }}</span>
                                </div>
                                <h2 class="product-title p-0">
                                    <a href="{{ route('customer.product', $product->{'slug_' . locale()}) }}"
                                        class=" text-truncate">{{ $product->{'name_' . locale()} }}</a>
                                </h2>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="products-pagination bg-white p-3">
            <nav aria-label="Center aligned pagination">
                <ul class="pagination justify-content-center">
                    {{ $customer_products->links() }}
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
function filter() {
$('#search-form').submit();
}

</script>
@endsection
