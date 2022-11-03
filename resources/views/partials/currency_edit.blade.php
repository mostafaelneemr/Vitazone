<form action="{{ route('your_currency.update') }}" method="POST" >
    @csrf
    <input type="hidden" name="id" value="{{ $currency->id }}">
    <div class="modal-header">
    	<h5 class="modal-title h6">{{translate('Update Currency')}}</h5>
    	<button type="button" class="close" data-dismiss="modal">
    	</button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="name_en">{{translate('Name English')}}</label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Name English')}}" id="name_en" name="name_en" value="{{ $currency->name_en }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="name_ar">{{translate('Name Arabic')}}</label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Name Arabic')}}" id="name_ar" name="name_ar" value="{{ $currency->name_ar }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="symbol_en">{{translate('Symbol English')}}</label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Symbol English')}}" id="symbol_en" name="symbol_en" value="{{ $currency->symbol_en }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="symbol_ar">{{translate('Symbol Arabic')}}</label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Symbol Arabic')}}" id="symbol_ar" name="symbol_ar" value="{{ $currency->symbol_ar }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="code">{{translate('Code')}}</label>
            <div class="col-sm-10">
                <input type="text" placeholder="{{translate('Code')}}" id="code" name="code" value="{{ $currency->code }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-from-label" for="exchange_rate">{{translate('Exchange Rate')}}</label>
            <div class="col-sm-10">
                <input type="number" lang="en" step="0.01" min="0" placeholder="{{translate('Exchange Rate')}}" id="exchange_rate" name="exchange_rate" value="{{ $currency->exchange_rate }}" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
    </div>
</form>

