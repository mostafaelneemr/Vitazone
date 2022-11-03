@extends('layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Sub-Sub-categories') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('subsubcategories.create')}}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Sub Subcategory')}}</span>
            </a>
        </div>
    </div>
</div>
<br>
<!-- Basic Data Tables -->
<!--===================================================-->
<div class="card">
    <div class="card-header row gutters-5">
        <h3 class="mb-md-0 h6 pull-left pad-no">{{translate('Sub-Sub-categories')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_subsubcategories" action="" method="GET">
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
                    <th>{{translate('Sub Subcategory')}}</th>
                    <th>{{translate('Subcategory')}}</th>
                    <th>{{translate('Category')}}</th>
                    {{-- <th>{{translate('Attributes')}}</th> --}}
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subsubcategories as $key => $subsubcategory)
                    @if ($subsubcategory->subcategory != null && $subsubcategory->subcategory->category != null)
                        <tr>
                            <td>{{ ($key+1) + ($subsubcategories->currentPage() - 1)*$subsubcategories->perPage() }}</td>
                            <td><img loading="lazy"  class="img-xs" src="{{ my_asset($subsubcategory->icon) }}" alt="{{translate('icon')}}"></td>
                            <td>{{$subsubcategory->name}}</td>
                            <td>{{$subsubcategory->subcategory->{'name_'.locale()} }}</td>
                            <td>{{$subsubcategory->subcategory->category->{'name_'.locale()} }}</td>
                            <td>
                          
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{route('subsubcategories.edit', encrypt($subsubcategory->id))}}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{route('subsubcategories.destroy', $subsubcategory->id)}}"
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
                {{ $subsubcategories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
