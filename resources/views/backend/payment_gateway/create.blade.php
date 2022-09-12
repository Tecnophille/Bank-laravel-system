@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title">{{ _lang('Add New Payment Gateway') }}</h4>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('payment_gateways.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Name') }}</label>
							        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
						        </div>
						    </div>

							<div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Slug') }}</label>
							        <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" required>
						        </div>
						    </div>

							<div class="col-md-12">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Image') }}</label>
								    <input type="file" class="form-control dropify" name="image" >
							    </div>
							</div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Status') }}</label>
							        <select class="form-control auto-select" data-selected="{{ old('status') }}" name="status" required>
										<option value="0">{{ _lang('Disable') }}</option>
										<option value="1">{{ _lang('Enable') }}</option>
									</select>
								</div>
						    </div>

							<div class="col-md-12" id="parameters">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Parameter Name</label>
											<input type="text" class="form-control" name="parameter_name[]">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Parameter Value</label>
											<input type="text" class="form-control" name="parameter_value[]">
										</div>
									</div>
						        </div>
						    </div>

							<div class="col-md-12">
								<div class="form-group">
									<button type="button" id="add_row" class="btn btn-primary"> {{ _lang('Add New') }}</button>
									<button type="button" id="remove_row" class="btn btn-danger"> {{ _lang('Remove') }}</button>
								</div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Supported Currencies') }}</label>
								    <textarea class="form-control" name="supported_currencies">{{ old('supported_currencies') }}</textarea>
							    </div>
							</div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Extra') }}</label>
							        <input type="text" class="form-control" name="extra" value="{{ old('extra') }}">
						        </div>
						    </div>


						<div class="col-md-12">
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
		$("#parameters").append(`<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Parameter Name</label>
												<input type="text" class="form-control" name="parameter_name[]">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">Parameter Value</label>
												<input type="text" class="form-control" name="parameter_value[]">
											</div>
										</div>
									</div>`);
	});

	$(document).on('click','#remove_row', function(){
		$("#parameters").children().last().remove();
	});
})(jQuery);
</script>
@endsection

