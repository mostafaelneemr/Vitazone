@extends('layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Brands') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Brands') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_brands" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search" name="search" @isset($sort_search)
                                    value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type name & Enter') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Logo') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $key => $brand)
                                <tr>
                                    <td>{{ $key + 1 + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        <img src="{{ uploaded_asset($brand->logo) }}" alt="{{ translate('Brand') }}"
                                            class="h-50px">
                                    </td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" title="{{ translate('Copy Deep Link') }}"
                                            class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="copyUrl(this)"
                                            data-url="{{ route('products.brand', [$brand->id]) }}">
                                            <i class="las la-clipboard mr-2"></i>
                                        </a>

                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('brands.edit', encrypt($brand->id)) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('brands.destroy', $brand->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $brands->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New Brand') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('brands.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name_ar">{{ translate('Name Ar') }}</label>
                            <input type="text" placeholder="{{ translate('Name Ar') }}" name="name_ar"
                                class="form-control" onkeyup="makeSlugar(this.value)" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ translate('Arabic Slug') }}
                                <span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{ translate('Arabic Slug') }}" name="slug_ar" id="slug_ar"
                                class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name_en">{{ translate('Name En') }}</label>
                            <input type="text" onkeyup="makeSlugen(this.value)" placeholder="{{ translate('Name En') }}"
                                name="name_en" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ translate('English Slug') }}
                                <span class="text-danger">*</span></label>
                            <input type="text" placeholder="{{ translate('English Slug') }}" name="slug_en" id="slug_en"
                                class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">{{ translate('Logo') }}
                                <small>({{ translate('120x80') }})</small></label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="logo" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="meta_title_ar">{{ translate('Meta Title Ar') }}</label>
                            <input type="text" class="form-control" name="meta_title_ar"
                                placeholder="{{ translate('Meta Title Ar') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="meta_title_en">{{ translate('Meta Title En') }}</label>
                            <input type="text" class="form-control" name="meta_title_en"
                                placeholder="{{ translate('Meta Title En') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="meta_description_en">{{ translate('Meta Description Ar') }}</label>
                            <textarea name="meta_description_ar" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="meta_description_en">{{ translate('Meta Description En') }}</label>
                            <textarea name="meta_description_en" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function makeSlugar(val) {
            let str = val;
            let output = str.replace(/\s+/g, '-').toLowerCase();
            $('#slug_ar').val(output);
        }

        function makeSlugen(val) {
            let str = val;
            let output = str.replace(/\s+/g, '-').toLowerCase();
            $('#slug_en').val(output);
        }

        function copyUrl(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
        }

        function sort_brands(el) {
            $('#sort_brands').submit();
        }

    </script>
@endsection
