@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Useful Link') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('links.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Link') }}</span>
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
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('My Medical About / My Medical Links') }}</th>
                        <th width="10%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($links as $key => $link)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $link->name }}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_links_about(this)" value="{{ $link->id }}" type="checkbox"
                                        <?php if ($link->links_about == 1) {
                                    echo 'checked';
                                    } ?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('links.edit', encrypt($link->id)) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('links.destroy', $link->id) }}"
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
        function update_links_about(el) {
            if (el.checked) {
                var links_about = 1;
            } else {
                var links_about = 0;
            }
            $.post('{{ route('links.update_links_about') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                links_about: links_about
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success',
                    '{{ translate('Links About products updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
