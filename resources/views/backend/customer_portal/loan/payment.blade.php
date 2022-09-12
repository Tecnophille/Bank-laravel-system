@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-header">
                <span class="header-title text-center">{{ _lang('Loan Repayment') }}</span>
            </div>
            <div class="card-body">
                <form method="post" class="validate" autocomplete="off" action="{{ route('loans.loan_payment', $loan->id) }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Loan ID') }}</label>
                                <input type="text" class="form-control" name="loan_id" value="{{ $loan->loan_id }}" readonly="true" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Due Payment Of') }}</label>
                                <input type="text" class="form-control" name="due_amount_of" value="{{ $loan->next_payment->repayment_date }}" readonly="true">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Late Penalties').' ( '._lang('It will only apply if payment date is over') }} )</label>
                                <div class="input-group">
                                    <input type="text" class="form-control float-field" name="late_penalties" id="late_penalties" value="{{ $loan->next_payment->penalty }}" readonly="true">
                                    <div class="input-group-append">
                                        <span class="input-group-text currency">{{ $loan->currency->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Amount To Pay') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control float-field" name="amount_to_pay" id="amount_to_pay" value="{{ $loan->next_payment->amount_to_pay }}" readonly="true" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text currency">{{ $loan->currency->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Remarks') }}</label>
                                <textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Make Payment') }}</button>
							</div>
						</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

