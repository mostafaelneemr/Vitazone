@extends('layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('city') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('cities.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New city') }}</span>
                </a>
            </div>
        </div>
    </div>
    <br>
    <div class="card p-4">

        <div class="card-header row gutters-5">
            <h3 class="mb-md-0 h6 pull-left pad-no">{{ translate('city') }}</h3>
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
            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>{{ translate('Province Name') }}</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Code') }}</th>
                        <th>{{ translate('Show/Hide') }}</th>
                        <th>{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cities as $key => $city)
                        <tr>
                            <td>{{ $key + 1 + ($cities->currentPage() - 1) * $cities->perPage() }}</td>
                            <td>
                                @if ($city->province)
                                    {{ $city->province->name }}
                                @endif

                            </td>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->code }}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_status(this)" value="{{ $city->id }}" type="checkbox" <?php if ($city->status == 1) {
                                    echo 'checked';
                                    } ?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('cities.edit', encrypt($city->id)) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('cities.destroy', $city->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $cities->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('cities.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('City status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
