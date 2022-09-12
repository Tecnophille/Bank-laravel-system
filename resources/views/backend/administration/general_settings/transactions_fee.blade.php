@extends('layouts.app')

@section('content')
<div class="row">
	@php $settings = \App\Models\Setting::all(); @endphp

	<div class="col-md-12">
        <div class="alert alert-info">
			<p><i class="icofont-info-circle"></i> {{ _lang('Fixed transfer fee & exchange fee will be in base currency and it will convert to other currency automatically based on exchange rate') }}</p>
		</div>
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">{{ _lang('Transactions Fee') }}</h4>
            </div>

            <div class="card-body">
            <form method="post" class="settings-submit params-panel" autocomplete="off" action="{{ route('settings.system_settings') }}" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Transfer Fee Type') }}</label>
                                <select class="form-control auto-select" data-selected="{{ get_setting($settings, 'transfer_fee_type', 'percentage') }}" name="transfer_fee_type">
                                    <option value="percentage">{{ _lang('Percentage') }}</option>
                                    <option value="fixed">{{ _lang('Fixed') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Transfer Fee') }}</label>
                                <input type="text" class="form-control" name="transfer_fee" value="{{ get_setting($settings, 'transfer_fee',0) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Currency Exchange Fee Type') }}</label>
                                <select class="form-control auto-select" data-selected="{{ get_setting($settings, 'exchange_fee_type','percentage') }}" name="exchange_fee_type">
                                    <option value="percentage">{{ _lang('Percentage') }}</option>
                                    <option value="fixed">{{ _lang('Fixed') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Currency Exchange Fee') }}</label>
                                <input type="text" class="form-control" name="exchange_fee" value="{{ get_setting($settings, 'exchange_fee',0) }}" required>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="icofont-check-circled"></i> {{ _lang('Save Settings') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>
@endsection
