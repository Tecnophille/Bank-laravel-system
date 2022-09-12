<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('FDRPlanController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">
	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Name') }}</label>
			<input type="text" class="form-control" name="name" value="{{ $fdrplan->name }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Minimum Amount').' '.currency() }}</label>
			<input type="text" class="form-control float-field" name="minimum_amount" value="{{ $fdrplan->minimum_amount }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
			<label class="control-label">{{ _lang('Maximum Amount').' '.currency() }}</label>
			<input type="text" class="form-control float-field" name="maximum_amount" value="{{ $fdrplan->maximum_amount }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
			<label class="control-label">{{ _lang('Interest Rate') }} %</label>
			<input type="text" class="form-control float-field" name="interest_rate" value="{{ $fdrplan->interest_rate }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
			<label class="control-label">{{ _lang('Duration') }}</label>
			<input type="number" class="form-control" name="duration" value="{{ $fdrplan->duration }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Duration Type') }}</label>
				<select class="form-control auto-select" data-selected="{{ $fdrplan->duration_type }}" name="duration_type" >
					<option value="">{{ _lang('Select One') }}</option>
					<option value="day">{{ _lang('Day') }}</option>
					<option value="week">{{ _lang('Week') }}</option>
					<option value="month">{{ _lang('Month') }}</option>
					<option value="year">{{ _lang('Year') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Status') }}</label>
				<select class="form-control auto-select" data-selected="{{ $fdrplan->status }}" name="status" >
					<option value="">{{ _lang('Select One') }}</option>
					<option value="1">{{ _lang('Active') }}</option>
					<option value="0">{{ _lang('Deactivate') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Description') }}</label>
			<textarea class="form-control" name="description">{{ $fdrplan->description }}</textarea>
			</div>
		</div>


		<div class="col-md-12">
			<div class="form-group">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
		    </div>
		</div>
	</div>
</form>

