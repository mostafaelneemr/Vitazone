@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <div class="card p-3">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ $language->{'name_' . locale()} }}</h5>
            </div>
        </div>
        <form class="form-horizontal" action="{{ route('languages.key_value_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $language->id }}">
            <div class="card-body">
                <table class="table table-striped table-bordered demo-dt-basic" id="tranlation-table" cellspacing="0"
                    width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th width="35%">{{ translate('Key') }}</th>
                            <th width="35%">{{ translate('value') }}</th>
                            <th width="35%">{{ translate('Edit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach (openJSONFile('en') as $key => $value)

                            <tr>
                                <td>{{ $i }}</td>
                                <td class="key">{{ $key }}</td>
                                <td>
                                    @isset(openJSONFile($language->code)[$key])
                                        {{ openJSONFile($language->code)[$key] }}

                                    @endisset
                                </td>
                                <td>
                                    <input type="text" class="form-control value" style="width:100%"
                                        name="key[{{ $key }}]" @isset(openJSONFile($language->code)[$key])
                                        value="{{ openJSONFile($language->code)[$key] }}"
                                    @endisset>
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">

                </div>
            </div>
            <div class="form-group mb-0 text-right">
                <button type="button" class="btn btn-primary"
                    onclick="copyTranslation()">{{ translate('Copy Translations') }}</button>
                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tranlation-table').DataTable();
        });
        //translate in one click
        function copyTranslation() {
            $('#tranlation-table > tbody  > tr').each(function(index, tr) {
                $(tr).find('.value').val($(tr).find('.key').text());
            });
        }

    </script>
@endsection
