@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('Update Loan Product') }}</span>
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ action('LoanProductController@update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Name') }}</label>
								<input type="text" class="form-control" name="name" value="{{ $loanproduct->name }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Minimum Amount').' '.currency() }}</label>
								<input type="text" class="form-control float-field" name="minimum_amount" value="{{ $loanproduct->minimum_amount }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Maximum Amount').' '.currency() }}</label>
								<input type="text" class="form-control float-field" name="maximum_amount" value="{{ $loanproduct->maximum_amount }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Interest Rate Per Year') }}</label>
								<input type="text" class="form-control float-field" name="interest_rate" value="{{ $loanproduct->interest_rate }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Interest Type') }}</label>
								<select class="form-control auto-select" data-selected="{{ $loanproduct->interest_type }}" name="interest_type" required>
									<option value="flat_rate">{{ _lang('Flat Rate') }}</option>
									<option value="fixed_rate">{{ _lang('Fixed Rate') }}</option>
									<option value="mortgage">{{ _lang('Mortgage amortization') }}</option>
									<option value="one_time">{{ _lang('One-time payment') }}</option>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Term') }}</label>
								<input type="number" class="form-control" name="term" value="{{ $loanproduct->term }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Term Period') }}</label>
								<select class="form-control auto-select" data-selected="{{ $loanproduct->term_period }}" name="term_period" id="term_period" required>
									<option value="">{{ _lang('Select One') }}</option>
									<option value="+1 day">{{ _lang('Day') }}</option>
									<option value="+1 week">{{ _lang('Week') }}</option>
									<option value="+1 month">{{ _lang('Month') }}</option>
									<option value="+1 year">{{ _lang('Year') }}</option>
								</select>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Status') }}</label>
								<select class="form-control auto-select" data-selected="{{ $loanproduct->status }}" name="status" required>
									<option value="">{{ _lang('Select One') }}</option>
									<option value="1">{{ _lang('Active') }}</option>
									<option value="0">{{ _lang('Deactivate') }}</option>
								</select>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Description') }}</label>
								<textarea class="form-control" name="description">{{ $loanproduct->description }}</textarea>
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary"><i class="icofont-check-circled"></i> {{ _lang('Update Changes') }}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


