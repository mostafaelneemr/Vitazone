@extends('layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Categories') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('categories.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Category') }}</span>
                </a>
            </div>
        </div>
    </div>
    <br>
    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="card">
        <div class="card-header row gutters-5">
            <h3 class="mb-md-0 h6 pull-left pad-no">{{ translate('Categories') }}</h3>
            <div class="pull-right clearfix">
                <form class="" id="sort_categories" action="" method="GET">
                    <div class="box-inline pad-rgt pull-left">
                        <div class="" style="min-width: 200px;">
                            <input type="text" class="form-control" id="search" name="search" @isset($sort_search)
                                value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                        </div>
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
                        <th>{{ translate('Parent Category') }}</th>
                        <th>{{ translate('Banner') }}</th>
                        <th>{{ translate('Icon') }}</th>
                        <th>{{ translate('Featured') }}</th>
                        <th data-breakpoints="md">{{ translate('Published') }}</th>
                        <th>{{ translate('Commission') }}</th>
                        <th width="10%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key => $category)
                        <tr>
                            <td>{{ $key + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @php
                                    $parent = \App\Category::where('id', $category->parent_id)->first();
                                @endphp
                                @if ($parent != null)
                                    {{ $parent->{'name_' . locale()} }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($category->banner != null)
                                    <img src="{{ uploaded_asset($category->banner) }}" alt="{{ translate('Banner') }}"
                                        class="h-50px">
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($category->icon != null)
                                    <span class="avatar avatar-square avatar-xs">
                                        <img src="{{ uploaded_asset($category->icon) }}"
                                            alt="{{ translate('icon') }}">
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="update_featured(this)" value="{{ $category->id }}"
                                        <?php if ($category->featured == 1) {
                                    echo 'checked';
                                    } ?>>
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_published(this)" value="{{ $category->id }}" type="checkbox"
                                        <?php if ($category->published == 1) {
                                    echo 'checked';
                                    } ?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>{{ $category->commision_rate }} %</td>

                            <td>
                                <a href="javascript:void(0)" title="{{ translate('Copy Deep Link') }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="copyUrl(this)"
                                    data-url="{{ route('products.category', [$category->id]) }}">
                                    <i class="las la-clipboard mr-2"></i>
                                </a>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('categories.edit', encrypt($category->id)) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                @if ($category->id != 163)
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                        data-href="{{ route('categories.destroy', $category->id) }}"
                                        title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $categories->appends(request()->input())->links() }}
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



        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('categories.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Featured categories updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('categories.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success',
                    '{{ translate('Published categories updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
