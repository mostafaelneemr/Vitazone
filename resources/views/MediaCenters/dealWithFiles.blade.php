@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <a href="{{ route('MediaCenters.create') }}"
                class="btn btn-rounded btn-info pull-right">{{ translate('Add Media Center Image') }}</a>
        </div>
    </div>
    <br>
    <hr>
    <style>
        div.gallery {
            border: 1px solid #ccc;
            position: relative;
        }

        div.gallery:hover {
            border: 1px solid #777;
        }

        div.gallery img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        div.desc {
            padding: 15px;
            text-align: center;
        }

        * {
            box-sizing: border-box;
        }

        .responsive {
            padding: 1em;
            float: left;
            width: 24.99999%;
        }

        @media only screen and (max-width: 700px) {
            .responsive {
                width: 49.99999%;
                margin: 6px 0;
            }
        }

        @media only screen and (max-width: 500px) {
            .responsive {
                width: 100%;
            }
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .options {
            display: none;
            position: absolute;
            top: 0;
            height: 70%;
            width: 12%;
            margin: auto;
            padding: 1em;
            background-color: #FFF;
            border-bottom-right-radius: 40%;
        }

        .options i {
            color: #777;
            font-size: 1.5em;
            margin-bottom: 1em;
        }

        .check-but {
            position: absolute;
            right: 0;
            width: 10%;
            background-color: #FFF;
            padding: .7em;
            border: 2px solid #ECF0F5;
            border-bottom-left-radius: 40%;
            text-align: center;
        }

        #deleteAll {
            display: none;
        }

    </style>
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{ translate('Media Center') }}


            </h3>
        </div>

        <div class="panel-body">
            <div class="text-center" style="margin: 1em">
                <input type="checkbox" id="selectAll">&nbsp; &nbsp;
                <label for="">أختيار الكل</label>
            </div>
            <div class="row">
                <div class="col-md-4 text-center"></div>
                <div class="col-md-4 text-center">
                    <a id="deleteAll" href="#"><i class="fa fa-trash" style="font-size: 2em"></i></a>

                </div>
                <div class="col-md-4 text-center"></div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                        <a href="#" class="list-group-item disabled">
                            {{ translate('Filter Folders') }}
                        </a>
                        @foreach ($directories as $directory)
                            <a class="list-group-item {{ $filter == $directory ? 'active' : '' }}"
                                href="{{ route('MediaCenters.dealWithFiles', ['filter' => $directory]) }}">

                                {{ $directory }}
                            </a>

                        @endforeach

                    </div>
                </div>
                <div class="col-md-10">
                    <div class="scrolling-pagination">
                        @foreach ($paginator as $image)
                            @php
                                $imageName = explode('\\', $image);
                                $imageName = $imageName[sizeof($imageName) - 1];
                                
                                $ext = explode('.', $imageName);
                                $ext = $ext[sizeof($ext) - 1];
                            @endphp
                            {{-- <input type="checkbox" name="images[]" value="{{ $image }}" /> --}}
                            <div class="responsive">
                                <div class="gallery">
                                    <div class="options">

                                        <a href="{{ route('MediaCenters.deleteFile', ['file' => encrypt($image)]) }}"><i
                                                class="fa fa-trash" title="{{ translate('Delete') }}"></i></a>
                                        <a href="{{ route('MediaCenters.editFile', ['file' => encrypt($image)]) }}"><i
                                                class="fa fa-edit" title="{{ translate('Edit') }}"></i></a>
                                        <i class="fa fa-copy" onclick="copyDeep(this)" id="{{ $imageName }}"
                                            title="{{ translate('Copy link') }}" style="cursor: pointer"></i>
                                    </div>
                                    <div class="check-but">
                                        <input type="checkbox" class="checkbox_class" name="images[]"
                                            value="{{ $image }}" />
                                    </div>
                                    <a target="_blank" href="{{ my_asset('uploads/' . $image) }}">
                                        @if ($ext == 'pdf')
                                            <img src="{{ my_asset('uploads/pdf_avatar.png') }}" alt="Cinque Terre"
                                                width="600" height="400">
                                        @else
                                            <img src="{{ my_asset('uploads/' . $image) }}" alt="Cinque Terre" width="600"
                                                height="400">
                                        @endif


                                    </a>
                                    <input type="text" class="form-control" value="{{ my_asset('uploads/' . $image) }}"
                                        id="deep-{{ $imageName }}">



                                </div>
                            </div>
                        @endforeach
                        {{ $paginator->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>



@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
    <script type="text/javascript">
        function copyDeep(el) {
            var proId = el.id;
            var copyText = document.getElementById(`deep-${proId}`);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied the text: " + copyText.value);
        }
        $('ul.pagination').hide();
        $(function() {

            $('.scrolling-pagination').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });

    </script>
    <script>
        $('.gallery').hover(function() {
            $(this).children('.options').slideToggle();
        });
        $('#selectAll').click(function() {
            if ($(this).prop('checked')) {
                $('.checkbox_class').prop('checked', true);
                $('#deleteAll').show();
            } else {
                $('.checkbox_class').prop('checked', false);
                $('#deleteAll').hide();
            }
        });
        $('.checkbox_class').click(function() {
            alert()

        })

    </script>
@endsection
