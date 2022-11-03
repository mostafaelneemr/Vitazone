@extends('layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Phone') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ action('PhonesController@export') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Export xls') }}</span>
                </a>
            </div>
        </div>
    </div>
    <br>
    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="card">
        <div class="card-header row gutters-5">
            <h3 class="mb-md-0 h6 pull-left pad-no">{{ translate('Phone') }}</h3>
            <div class="pull-right clearfix">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search"
                            value="{{ isset($_GET['phone']) ? $_GET['phone'] : '' }}"
                            placeholder="{{ translate('Type Phone & hit Enter') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>{{ translate('phone') }}</th>
                        <th>{{ translate('user') }}</th>
                        <th>{{ translate('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($phones as $key => $phone)
                        <tr>
                            <td>{{ $key + 1 + ($phones->currentPage() - 1) * $phones->perPage() }}</td>
                            <td>{{ $phone->phone }}</td>
                            <td>
                                @if ($phone->user)
                                    {{ $phone->user->name }}
                                @else
                                    <span style="color: red">{{ translate('Deleted user') }}</span>
                                @endif
                            </td>
                            <td>
                                <select class="form-control demo-select2 update_phone_status"
                                    onchange="updatePhoneStatus(this)" data-minimum-results-for-search="Infinity">
                                    <option value="has_attempts" phone_id="{{ $phone->id }}" @if ($phone->status == 'has_attempts') selected @endif>
                                        {{ translate('Has attempts') }}</option>
                                    <option value="actived" phone_id="{{ $phone->id }}" @if ($phone->status == 'actived') selected @endif>
                                        {{ translate('Actived') }}</option>
                                    <option value="blocked" phone_id="{{ $phone->id }}" @if ($phone->status == 'blocked') selected @endif>
                                        {{ translate('Blocked') }}</option>
                                </select>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $phones->appends(request()->input())->links() }}
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
        // $('.update_phone_status').change(function() {
        //     var status = $(this).val();
        //     var phone_id = $(this).children(":selected").attr("phone_id");
        //     $.post(`{{ route('phones.update_status') }}`, {
        //             _token: '{{ csrf_token() }}',
        //             status: status,
        //             phone_id: phone_id
        //         },
        //         function(data) {
        //             if (data == 1) {
        //                 AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
        //                 showAlert('success', 'Status updated successfully');
        //             } else {
        //                 AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        //             }
        //         });
        // });

        function updatePhoneStatus(eee) {
            var status = eee.value;
            var phone_id = eee.options[eee.selectedIndex].getAttribute('phone_id');
            $.post(`{{ route('phones.update_status') }}`, {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    phone_id: phone_id
                },
                function(data) {
                    if (data == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                });

        }

        $('#search').keyup(function() {
            $(document).keyup(function(e) {
                var valName = $('#search').val();
                if (e.which == 13) {
                    window.location.replace(`{{ URL::to('/') }}/admin/phones?phone=${valName}`);
                }
            });
        });
    </script>
@endsection
