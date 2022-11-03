@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('seosetting.update', $seosetting->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO Settings') }}</h5>
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="name">{{ translate('Title English') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="title_en" name="title_en" value="{{ $seosetting->title_en }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="name">{{ translate('Title Arabic') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="title_ar" name="title_ar" value="{{ $seosetting->title_ar }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Keyword English') }}</label>
                            <div class="col-md-8">
                                <input type="text" value="{{ $seosetting->keyword_en }}"
                                    class="form-control aiz-tag-input" name="keyword_en[]"
                                    placeholder="{{ translate('Keyword English') }}" data-role="tagsinput">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Keyword Arabic') }}</label>
                            <div class="col-md-8">
                                <input type="text" value="{{ $seosetting->keyword_ar }}"
                                    class="form-control aiz-tag-input" name="keyword_ar[]"
                                    placeholder="{{ translate('Keyword Arabic') }}" data-role="tagsinput">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="name">{{ translate('Author English') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="author_en" name="author_en" value="{{ $seosetting->author_en }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="name">{{ translate('Author Arabic') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="author_ar" name="author_ar" value="{{ $seosetting->author_ar }}"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="revisit">{{ translate('Revisit After') }}</label>
                            <div class="col-md-7">
                                <input type="number" min="0" step="1" value="{{ $seosetting->revisit }}"
                                    placeholder="{{ translate('Revisit After') }}" name="revisit" class="form-control"
                                    required>

                            </div>
                            <label class="col-md-2 col-from-label" for="revisit">{{ translate('Days') }}</label>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label" for="sitemap">{{ translate('Sitemap Link') }}</label>
                            <div class="col-md-8">
                                <input type="text" id="sitemap" name="sitemap" value="{{ $seosetting->sitemap_link }}"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="name">{{ translate('Description English') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="description_en"
                                    required>{{ $seosetting->description_en }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"
                                for="name">{{ translate('Description Arabic') }}</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="4" name="description_ar"
                                    required>{{ $seosetting->description_ar }}</textarea>
                            </div>
                        </div>


                        <!--===================================================-->
                        <!--End Horizontal Form-->

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO All Products Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="products_meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="{{ $seosetting->products_meta_title_ar }}"
                                    name="products_meta_title_ar" placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="products_meta_title_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $seosetting->products_meta_title_en }}" class="form-control"
                                    name="products_meta_title_en" placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="products_meta_description_ar">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="products_meta_description_ar" rows="5"
                                    class="form-control">{{ $seosetting->products_meta_description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="products_meta_description_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="products_meta_description_en" rows="5"
                                    class="form-control">{{ $seosetting->products_meta_description_en }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO Blogs Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="blog_meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control"
                                    value="{{ $seosetting->blog_meta_title_ar }}"
                                    name="blog_meta_title_ar"
                                    placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="blog_meta_title_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $seosetting->blog_meta_title_en }}"
                                    class="form-control" name="blog_meta_title_en"
                                    placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="blog_meta_description_ar">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="blog_meta_description_ar" rows="5"
                                    class="form-control">{{ $seosetting->blog_meta_description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="blog_meta_description_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="blog_meta_description_en" rows="5"
                                    class="form-control">{{ $seosetting->blog_meta_description_en }}</textarea>
                            </div>
                        </div>
                       
                    </div>
                   
                </div>

            </div>
            <div class="col-lg-6">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO All Categories Settings') }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="categories_meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control"
                                    value="{{ $seosetting->categories_meta_title_ar }}" name="categories_meta_title_ar"
                                    placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="categories_meta_title_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $seosetting->categories_meta_title_en }}"
                                    class="form-control" name="categories_meta_title_en"
                                    placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="categories_meta_description_ar">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="categories_meta_description_ar" rows="5"
                                    class="form-control">{{ $seosetting->categories_meta_description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="categories_meta_description_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="categories_meta_description_en" rows="5"
                                    class="form-control">{{ $seosetting->categories_meta_description_en }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO All Brands Settings') }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="brands_meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="{{ $seosetting->brands_meta_title_ar }}"
                                    name="brands_meta_title_ar" placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="brands_meta_title_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $seosetting->brands_meta_title_en }}" class="form-control"
                                    name="brands_meta_title_en" placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="brands_meta_description_ar">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="brands_meta_description_ar" rows="5"
                                    class="form-control">{{ $seosetting->brands_meta_description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="brands_meta_description_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="brands_meta_description_en" rows="5"
                                    class="form-control">{{ $seosetting->brands_meta_description_en }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('SEO All Customer Products Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control"
                                    value="{{ $seosetting->customer_products_meta_title_ar }}"
                                    name="customer_products_meta_title_ar"
                                    placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_title_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $seosetting->customer_products_meta_title_en }}"
                                    class="form-control" name="customer_products_meta_title_en"
                                    placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_description_ar">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="customer_products_meta_description_ar" rows="5"
                                    class="form-control">{{ $seosetting->customer_products_meta_description_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_description_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="customer_products_meta_description_en" rows="5"
                                    class="form-control">{{ $seosetting->customer_products_meta_description_en }}</textarea>
                            </div>
                        </div>
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('SEO All Customer Products Settings Concate If There is Category') }}</h5>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_title_concat_ar">{{ translate('Meta Title Ar') }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control"
                                    value="{{ $seosetting->customer_products_meta_title_concat_ar }}"
                                    name="customer_products_meta_title_concat_ar"
                                    placeholder="{{ translate('Meta Title Ar') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_title_concat_en">{{ translate('Meta Title En') }}</label>
                            <div class="col-md-9">
                                <input type="text" value="{{ $seosetting->customer_products_meta_title_concat_en }}"
                                    class="form-control" name="customer_products_meta_title_concat_en"
                                    placeholder="{{ translate('Meta Title En') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_description_concat_ar">{{ translate('Meta Description Ar') }}</label>
                            <div class="col-md-9">
                                <textarea name="customer_products_meta_description_concat_ar" rows="5"
                                    class="form-control">{{ $seosetting->customer_products_meta_description_concat_ar }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="customer_products_meta_description_concat_en">{{ translate('Meta Description En') }}</label>
                            <div class="col-md-9">
                                <textarea name="customer_products_meta_description_concat_en" rows="5"
                                    class="form-control">{{ $seosetting->customer_products_meta_description_concat_en }}</textarea>
                            </div>
                        </div>
                    </div>
                   
                </div>

                
            </div>
        </div>
        <div class="mb-3 text-right">
            <button type="submit" name="button" class="btn btn-primary">{{ translate('Save') }}</button>
        </div>

    </form>

@endsection
