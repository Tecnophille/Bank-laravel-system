<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('other_banks.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row px-2">
	    <div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Name') }}</label>
				<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Swift Code') }}</label>
				<input type="text" class="form-control" name="swift_code" value="{{ old('swift_code') }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Bank Country') }}</label>
				<select class="form-control select2" name="bank_country" required>
					{{ get_country_list() }}
				</select>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Bank Currency') }}</label>
				<select class="form-control select2" name="bank_currency" required>
					{{ create_option('currency','id','name','',array('status=' => 1)) }}
				</select>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Minimum Transfer Amount') }}</label>
				<input type="text" class="form-control" name="minimum_transfer_amount" value="{{ old('minimum_transfer_amount') }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Maximum Transfer Amount') }}</label>
				<input type="text" class="form-control" name="maximum_transfer_amount" value="{{ old('maximum_transfer_amount') }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Fixed Charge') }}</label>
				<input type="text" class="form-control" name="fixed_charge" value="{{ old('fixed_charge',0) }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Charge In Percentage') }}</label>
				<div class="input-group mb-3">
					<input type="text" class="form-control" name="charge_in_percentage" value="{{ old('charge_in_percentage',0) }}" required>
					<div class="input-group-append">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Instructions') }}</label>
				<textarea class="form-control" name="descriptions">{{ old('descriptions') }}</textarea>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Status') }}</label>
				<select class="form-control auto-select" data-selected="{{ old('status') }}" name="status"  required>
					<option value="">{{ _lang('Select One') }}</option>
					<option value="1">{{ _lang('Active') }}</option>
					<option value="0">{{ _lang('Deactivate') }}</option>
				</select>
			</div>
		</div>


		<div class="col-md-12 mt-2">
		    <div class="form-group">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
		    </div>
		</div>
	</div>
</form>
