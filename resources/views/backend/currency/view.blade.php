@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="header-title">{{ _lang('Currency Details') }}</span>
			</div>
			
			<div class="card-body">
			    <table class="table table-bordered">
				    <tr><td>{{ _lang('Name') }}</td><td>{{ $currency->name }}</td></tr>
					<tr><td>{{ _lang('Exchange Rate') }}</td><td>{{ $currency->exchange_rate }}</td></tr>
					<tr><td>{{ _lang('Base Currency') }}</td><td>{{ $currency->base_currency }}</td></tr>
					<tr><td>{{ _lang('Status') }}</td><td>{{ $currency->status }}</td></tr>
			    </table>
			</div>
	    </div>
	</div>
</div>
@endsection


