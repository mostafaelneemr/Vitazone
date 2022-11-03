@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('MediaCenters.create') }}"
                class="btn btn-rounded btn-info pull-right">{{ translate('Add Media Center Image') }}</a>
        </div>
    </div>
    <div class="container">
        <style>
            .col-md-6 {
                padding: 1em;
            }

        </style>
        <h3>{{ translate('Media Center') }}</h3>
        <hr>
        <div class="row">
            @forelse ($MediaCenter as $oneImage)
                <div class="col-md-3">
                    <div class="card" style="width: 90%;">
                        <img loading="lazy" src="{{ my_asset($oneImage->file_path) }}"
                            alt="{{ $oneImage->{'alt_' . locale()} }}" style="width: 100%;height: 15em;" class="card-img-top">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <a
                                        href="{{ route('MediaCenters.edit', encrypt($oneImage->id)) }}">{{ translate('Edit') }}</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('MediaCenters.destroy', $oneImage->id) }}">{{ translate('delete') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty

            @endforelse

        </div>
        <div class="clearfix">
            <div class="pull-right">
                {{ $MediaCenter->links() }}
            </div>
        </div>
    </div>
@endsection
