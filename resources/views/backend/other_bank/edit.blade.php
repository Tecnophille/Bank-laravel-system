@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title">{{ _lang('Update Bank Details') }}</h4>
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ action('OtherBankController@update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Name') }}</label>
								<input type="text" class="form-control" name="name" value="{{ $otherbank->name }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Swift Code') }}</label>
								<input type="text" class="form-control" name="swift_code" value="{{ $otherbank->swift_code }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Bank Country') }}</label>
								<select class="form-control select2" name="bank_country" required>
									{{ get_country_list($otherbank->bank_country ) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Bank Currency') }}</label>
								<select class="form-control select2" name="bank_currency" required>
									{{ create_option('currency','id','name',$otherbank->bank_currency,array('status=' => 1)) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Minimum Transfer Amount') }}</label>
								<input type="text" class="form-control" name="minimum_transfer_amount" value="{{ $otherbank->minimum_transfer_amount }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Maximum Transfer Amount') }}</label>
								<input type="text" class="form-control" name="maximum_transfer_amount" value="{{ $otherbank->maximum_transfer_amount }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Fixed Charge') }}</label>
								<input type="text" class="form-control" name="fixed_charge" value="{{ $otherbank->fixed_charge }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Charge In Percentage') }}</label>
								<div class="input-group mb-3">
									<input type="text" class="form-control" name="charge_in_percentage" value="{{ $otherbank->charge_in_percentage }}" required>
									<div class="input-group-append">
										<span class="input-group-text">%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Status') }}</label>
								<select class="form-control auto-select" data-selected="{{ $otherbank->status }}" name="status" required>
									<option value="">{{ _lang('Select One') }}</option>
									<option value="1">{{ _lang('Active') }}</option>
									<option value="0">{{ _lang('Deactivate') }}</option>
								</select>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Instructions') }}</label>
								<textarea class="form-control" name="descriptions">{{ $otherbank->descriptions }}</textarea>
							</div>
						</div>


						<div class="col-md-12 mt-2">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


