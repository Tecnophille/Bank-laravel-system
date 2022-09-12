@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title text-center">{{ _lang('New Payment Request') }}</h4>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('payment_requests.store') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Receiver Account') }}</label>
                                <input type="text" class="form-control" name="receiver_account" value="{{ old('receiver_account') }}" required>
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
                                <label class="control-label">{{ _lang('Amount') }}</label>
                                <input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Description') }}</label>
                                <textarea class="form-control" name="description" required>{{ old('description') }}</textarea>
                            </div>
                        </div>

						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Send Request') }}</button>
							</div>
						</div>
					</div>
			    </form>
			</div>
		</div>
    </div>
</div>
@endsection