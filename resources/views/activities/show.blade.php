@extends('layouts.app')

@section('content')
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }
    </style>
    <div style="overflow-x:auto;">
        <table border="1">
            <thead>
            <tr>
                <th>#</th>
                <th>{{__('Value')}}</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td>{{__('ID')}}</td>
                <td>{{$result->id}}</td>
            </tr>


            <tr>
                <td>{{__('Status')}}</td>
                <td>{{$result->description}}</td>
            </tr>

            <tr>
                <td>{{__('Model')}}</td>
                <td>{{$result->subject_type}} ({{$result->subject_id}})</td>
            </tr>

            <tr>
                <td>{{__('User')}}</td>
                <td>{{$result->causer_type}} ({{$result->causer_id}})</td>
            </tr>

            @if(isset($result->location))
                <tr>
                    <td>{{__('Country')}}</td>
                    <td>{{$result->location->country}} ({{$result->location->countryCode}})</td>
                </tr>
                <tr>
                    <td>{{__('city')}}</td>
                    <td>{{$result->location->city}}</td>
                </tr>
                <tr>
                    <td>{{__('Region Name')}}</td>
                    <td>{{$result->location->regionName}}</td>
                </tr>
                <tr>
                    <td>{{__('ISP')}}</td>
                    <td>{{$result->location->isp}}</td>
                </tr>
                <tr>
                    <td>{{__('Latitude')}}</td>
                    <td>{{$result->location->lat}}</td>
                </tr>
                <tr>
                    <td>{{__('Longitude')}}</td>
                    <td>{{$result->location->lon}}</td>
                </tr>
            @endif

            <tr>
                <td>{{__('Created At')}}</td>
                <td>
                    @if($result->created_at == null)
                        --
                    @else
                        {{$result->created_at->format('Y-m-d H:i')}}
                    @endif
                </td>
            </tr>

            </tbody>
        </table>

        <hr>

        <h3 style="text-align: center;">{{__('Data')}}</h3>

        <table border="1">
            <thead>
            <tr>
                <th>Key</th>
                <th>{{__('New Attributes')}}</th>
                @if(isset($result->properties['old']))
                    <th>{{__('Old Attributes')}}</th>
                @endif
            </tr>
            </thead>
            <tbody>

            @php
                $keys =[];
    if (count($result->properties)){
                    $keys = array_keys($result->properties['attributes']);
                    }
            @endphp

            @foreach($keys as $value)

                <tr>
                    @if (!empty($result->properties))
                        <td>{{$value}}</td>
                        <td>
                            @if(is_array($result->properties['attributes'][$value]))
                                <pre>
                        {{print_r($result->properties['attributes'][$value])}}
                    </pre>
                            @else
                                {{$result->properties['attributes'][$value]}}
                            @endif
                        </td>
                        @if(isset($result->properties['old']))
                            <td>
                                @if(is_array($result->properties['old'][$value]))
                                    <pre>
                        {{print_r($result->properties['old'][$value])}}
                    </pre>
                                @else
                                    {{$result->properties['old'][$value]}}
                                @endif


                            </td>
                        @endif
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>


    </div>

@endsection
