<form method="post" class="validate" autocomplete="off" action="{{ route('deposit.automatic_deposit',$deposit_method->id) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Amount') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ get_base_currency() }}</span>
                    </div>
                    <input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Minimum Deposit') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ get_base_currency() }}</span>
                    </div>
                    <input type="text" class="form-control float-field" value="{{ $deposit_method->minimum_amount }}" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Maximum Deposit') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ get_base_currency() }}</span>
                    </div>
                    <input type="text" class="form-control float-field" value="{{ $deposit_method->maximum_amount }}" readonly>
                </div>
            </div>
        </div>


        <div class="col-md-12 mb-2">
            <h6 class="text-danger text-center"><b>{{ decimalPlace($deposit_method->fixed_charge, currency()) }} + {{ $deposit_method->charge_in_percentage }}% {{ _lang('transaction charge will be applied') }}</b></h6>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Process') }}</button>
            </div>
        </div>
    </div>
</form>
