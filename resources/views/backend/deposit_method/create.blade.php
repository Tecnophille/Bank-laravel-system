@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title">{{ _lang('New Deposit Method') }}</h4>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('deposit_methods.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Name') }}</label>
								<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Image') }}</label>
								<input type="file" class="form-control dropify" name="image" >
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Currency') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('currency_id') }}" name="currency_id" required>
									<option value="">{{ _lang('Select One') }}</option>
									{{ create_option('currency','id','name','',array('status=' => 1)) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Status') }}</label>
								<select class="form-control auto-select" data-selected="{{ old('status') }}" name="status">
									<option value="">{{ _lang('Select One') }}</option>
									<option value="1">{{ _lang('Active') }}</option>
									<option value="0">{{ _lang('Deactivate') }}</option>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Minimum Amount') }}</label>
								<input type="text" class="form-control float-field" name="minimum_amount" value="{{ old('minimum_amount') }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Maximum Amount') }}</label>
								<input type="text" class="form-control float-field" name="maximum_amount" value="{{ old('maximum_amount') }}" required>
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
								<label class="control-label">{{ _lang('Descriptions') }}</label>
								<textarea class="form-control" name="descriptions">{{ old('descriptions') }}</textarea>
							</div>
						</div>


						<div class="col-md-12 mt-3">
							<div class="d-flex align-items-center">
								<h5><b>{{ _lang('Deposit Informations') }}</b></h5>
								<button type="button" id="add_row" class="btn btn-outline-primary btn-sm ml-auto"><i class="icofont-plus"></i> {{ _lang('Add New Field') }}</button>
							</div>
							<hr>
							<div class="row" id="custom_fields">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">{{ _lang('Field Name') }}</label>
										<div class="input-group mb-3">
											<input type="text" class="form-control" name="requirements[]" placeholder="EX: Transaction ID" required>
											<div class="input-group-append">
												<button class="btn btn-danger btn-sm" id="remove_field"><i class="icofont-trash"></i></button>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">{{ _lang('Field Name') }}</label>
										<div class="input-group mb-3">
											<input type="text" class="form-control" name="requirements[]" placeholder="EX: Reference Number" required>
											<div class="input-group-append">
												<button class="btn btn-danger btn-sm" id="remove_field"><i class="icofont-trash"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 mt-2">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
							</div>
						</div>
					</div>
			    </form>
			</div>
		</div>
    </div>
</div>
@endsection

@section('js-script')
<script>
(function ($) {
  "use strict";

	$(document).on('click','#add_row', function(){
		$("#custom_fields").append(`<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Field Name') }}</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="requirements[]" required>
												<div class="input-group-append">
													<button class="btn btn-danger btn-sm" id="remove_field"><i class="icofont-trash"></i></button>
												</div>
											</div>
										</div>
									</div>`);
	});

	$(document).on('click','#remove_field', function(){
		$(this).closest('.col-md-6').remove();
	});
})(jQuery);
</script>
@endsection


