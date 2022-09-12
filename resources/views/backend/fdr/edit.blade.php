@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title">{{ _lang('Update Fixed Deposit') }}</h4>
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ action('FixedDepositController@update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Deposit Plan') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ $fixeddeposit->fdr_plan_id }}" name="fdr_plan_id" required>
									<option value="">{{ _lang('Select One') }}</option>
									{{ create_option('fdr_plans','id','name', array('status=' => 1)) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('User Account') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ $fixeddeposit->user_id }}" name="user_id" required>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(get_table('users',array('user_type='=>'customer')) as $user )
										<option value="{{ $user->id }}">{{ $user->email .' ('. $user->name . ')' }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Currency') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ $fixeddeposit->currency_id }}" name="currency_id" required>
									<option value="">{{ _lang('Select One') }}</option>
									{{ create_option('currency','id','name','',array('status=' => 1)) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Deposit Amount') }}</label>
								<input type="text" class="form-control float-field" name="deposit_amount" value="{{ $fixeddeposit->deposit_amount }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Return Amount') }}</label>
								<input type="text" class="form-control float-field" name="return_amount" value="{{ $fixeddeposit->return_amount }}" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Mature Date') }}</label>
								<input type="text" class="form-control datepicker" name="mature_date" value="{{ $fixeddeposit->getRawOriginal('mature_date') }}">
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Remarks') }}</label>
								<textarea class="form-control" name="remarks">{{ $fixeddeposit->remarks }}</textarea>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Attachment') }}</label>
								<input type="file" class="form-control dropify" name="attachment" data-default-file="{{ $fixeddeposit->attachment != null ? asset('public/uploads/media/'.$fixeddeposit->attachment) : '' }}" >
							</div>
						</div>


						<div class="col-md-12">
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


