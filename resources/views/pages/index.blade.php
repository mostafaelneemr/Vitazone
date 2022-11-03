@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Pages') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('pages.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Page') }}</span>
                </a>
            </div>
        </div>
    </div>
    <br>

    <div class="card">

        <div class="card-body">
            <table class="table aiz-table mb-0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>{{ translate('Title') }}</th>
                        <th>{{ translate('Slug') }}</th>
                        <th>{{ translate('Mobile Show/Hide') }}</th>
                        <th width="10%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $key => $page)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $page->title }}</td>
                            <td><a target="_blank" class="btn btn-primary"
                                    href="{{ route('custom-pages.show_custom_page', $page->slug) }}">{{ $page->slug }}</a>
                            </td>
                            <td>

                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="update_mobile_appear(this)"
                                        value="{{ $page->id }}" <?php if ($page->mobile_apear == 1) {
                                    echo 'checked';
                                    } ?>>
                                    <span></span>
                                </label>
                            </td>
                            <td>
                                <a href="javascript:void(0)" title="{{ translate('Copy Deep Link') }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="copyUrl(this)"
                                    data-url="{{ url('/',[ $page->id]) }}">
                                    <i class="las la-clipboard mr-2"></i>
                                </a>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('pages.edit', $page->slug) }}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('pages.destroy', $page->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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

        function update_mobile_appear(el) {
            if (el.checked) {
                var mobile_apear = 1;
            } else {
                var mobile_apear = 0;
            }
            $.post('{{ route('pages.update_mobile_appear') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                mobile_apear: mobile_apear
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Mobile Apear updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
