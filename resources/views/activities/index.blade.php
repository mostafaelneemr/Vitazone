@extends('layouts.app')

@section('content')

    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0 h6">{{ translate('Activities') }}</h3>
            @if (Auth::user()->user_type == 'admin')
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('activity.clear') }}" class="btn btn-circle btn-info">
                        <span>{{ translate('Clear logs') }}</span>
                    </a>
                </div>
            @endif
        </div>
        <div class="panel-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Status') }}</th>
                        <th>{{ translate('Model') }}</th>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('Created At') }}</th>
                        <th width="10%">{{ translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $key => $activity)
                        <tr>
                            <td>{{ $key + 1 + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->subject_type }}({{ $activity->subject_id }})</td>
                            <td>{{ $activity->causer_type }}({{ $activity->causer_id }})</td>
                            <td>{{ $activity->created_at }}</td>

                            <td>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('activity.show', $activity->id) }}"
                                    title="{{ translate('Show') }}">
                                    <i class="las la-eye"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $activities->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post(`{{ route('categories.featured') }}`, {
                _token: `{{ csrf_token() }}`,
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    showAlert('success', 'Featured categories updated successfully');
                } else {
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

    </script>
@endsection
