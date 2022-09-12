<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('CurrencyController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">
	<div class="row px-2">
		<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Name') }}</label>						
		   <input type="text" class="form-control" name="name" value="{{ $currency->name }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Exchange Rate') }}</label>						
		   <input type="text" class="form-control float-field" name="exchange_rate" value="{{ $currency->exchange_rate }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Base Currency') }}</label>						
			<select class="form-control auto-select" data-selected="{{ $currency->base_currency }}" name="base_currency"  required>
				<option value="">{{ _lang('Select One') }}</option>
				<option value="0">{{ _lang('No') }}</option>
<option value="1">{{ _lang('Yes') }}</option>
			</select>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Status') }}</label>						
			<select class="form-control auto-select" data-selected="{{ $currency->status }}" name="status"  required>
				<option value="">{{ _lang('Select One') }}</option>
				<option value="1">{{ _lang('Active') }}</option>
<option value="0">{{ _lang('Deactivate') }}</option>
			</select>
		</div>
	</div>

	
		<div class="form-group">
		    <div class="col-md-12">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
		    </div>
		</div>
	</div>
</form>

