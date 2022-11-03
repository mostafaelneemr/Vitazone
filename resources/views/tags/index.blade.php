@extends('layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Tags') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('tags.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Tag') }}</span>
                </a>
            </div>
        </div>
    </div>
    <br>
    <!-- Basic Data Tables -->
    <!--===================================================-->
    <div class="card">
        <div class="card-header row gutters-5">
            <h3 class="mb-md-0 h6 pull-left pad-no">{{ translate('Tags') }}</h3>
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
                        <th width="10%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $key => $tag)
                        <tr>
                            <td>{{ $key + 1 + ($tags->currentPage() - 1) * $tags->perPage() }}</td>
                            <td>{{ __($tag->name) }}</td>
                            <td>
                                
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('tags.edit', encrypt($tag->id)) }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('tags.destroy', $tag->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                           
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix">
                <div class="pull-right">
                    {{ $tags->appends(request()->input())->links() }}
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

    </script>
@endsection
