@extends('layouts.app')

@section('content')
    <form class="form-horizontal" action="{{ route('subcategories.store') }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{{translate('Subcategory Arabic Information')}}</h3>
                </div>

                <div class="panel-body">
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Name Ar')}}" id="name" name="name_ar"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{translate('Category')}}</label>
                        <div class="col-sm-9">
                            <select name="category_id" required class="form-control demo-select2-placeholder">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{translate('Meta Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="meta_title_ar"
                                   placeholder="{{translate('Meta Title Ar')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="icon">{{translate('Icon')}}
                            <small>({{ translate('32x32') }})</small></label>
                        <div class="col-sm-9">
                            <input type="file" id="icon" name="icon" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{translate('Description Ar')}}</label>
                        <div class="col-sm-9">
                            <textarea name="meta_description_ar" rows="8" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{translate('Hash Tags')}}</label>
                        <div class="col-sm-9">
                            <select class="form-control demo-select2-placeholder" multiple="" name="hash_tags[]" id="hash_tags">
                                <option value="">{{ ('Select HashTags') }}</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
							<label class="col-sm-3 control-label">{{translate('New Hashtags EN')}}</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="hastags_add_en" placeholder="{{ translate('Add Hashtags') }}" id="hastags_add_en">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">{{translate('New Hashtags AR')}}</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="hastags_add_ar" placeholder="{{ translate('Add Hashtags') }}" id="hastags_add_ar">
							</div>
						</div>
						<button id="hastag_form" type="button" name="button" class="btn btn-info">{{ translate('Add') }}</button>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{{translate('Subcategory English Information')}}</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Name En')}}" id="name" name="name_en"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{translate('Meta Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="meta_title_en"
                                   placeholder="{{translate('Meta Title En')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{translate('Description En')}}</label>
                        <div class="col-sm-9">
                            <textarea name="meta_description_en" rows="8" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit">{{translate('Save')}}</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')

<script type="text/javascript">
	
	function getTags() {
		$.ajax({
			method : 'GET',
            url: "{{ route('tags.getTags') }}",
            success : function(data){
            	console.log(data);
	        	$('#hash_tags').html(null);
		    	for (var i = 0; i < data.length; i++) {
			        $('#hash_tags').append($('<option>', {
			            value: data[i].id,
			            text: data[i].name
			        }));
			    	$('.demo-select2').select2();
			    }
            }
        })
	}

	getTags();


	$(document).ready(function() 
    {
        $('#hastag_form').click(function(event) 
        {
        	console.log('clcikc')

	        $.ajax({
	        	method : 'POST',
	        	url : "{{ route('tags.addnew') }}",
	        	data : {
	        		"_token": "{{ csrf_token() }}",
	        		'name_en': $('#hastags_add_en').val(),
	            	'name_sa': $('#hastags_add_ar').val(),
	        	},
	        	success: function(data){
	        		$('#hastags_add_en').val("")
	            	$('#hastags_add_ar').val("")
	            	getTags();
                }
	    	});
        });

    });

</script>

@endsection
