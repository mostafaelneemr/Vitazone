@extends('layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Sub-Categories') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('subcategories.create')}}" class="btn btn-circle btn-info">
                <span>{{ translate('Add New Subcategory') }}</span>
            </a>
        </div>
    </div>
</div>
<br>
<!-- Basic Data Tables -->
<!--===================================================-->
<div class="card">
    <div class="card-header row gutters-5">
        <h3 class="mb-md-0 h6 pull-left pad-no">{{translate('Sub-Categories')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_subcategories" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
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
                    <th>{{translate('Icon')}}</th>
                    <th>{{translate('Subcategory')}}</th>
                    <th>{{translate('Category')}}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subcategories as $key => $subcategory)
                    @if ($subcategory->category != null)
                        <tr>
                            <td>{{ ($key+1) + ($subcategories->currentPage() - 1)*$subcategories->perPage() }}</td>
                            <td><img loading="lazy"  class="h-25px" src="{{ my_asset($subcategory->icon) }}" alt="{{translate('icon')}}"></td>
                            <td>{{$subcategory->name}}</td>
                            <td>{{$subcategory->category->{'name_'.locale()} }}</td>
                            <td>
                          
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{route('subcategories.edit', encrypt($subcategory->id))}}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{route('subcategories.destroy', $subcategory->id)}}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                           
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $subcategories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
