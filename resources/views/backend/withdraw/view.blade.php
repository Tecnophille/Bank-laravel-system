@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="header-title">{{ _lang('Transaction Details') }}</span>
			</div>

			<div class="card-body">
			    <table class="table table-bordered">
					<tr><td>{{ _lang('User Name') }}</td><td>{{ $transaction->user->name }}</td></tr>
					<tr><td>{{ _lang('User Email') }}</td><td>{{ $transaction->user->email }}</td></tr>
					<tr><td>{{ _lang('Amount') }}</td><td>{{ currency($transaction->currency->name).' '.$transaction->amount }}</td></tr>
					<tr><td>{{ _lang('DR/CR') }}</td><td>{{ strtoupper($transaction->dr_cr) }}</td></tr>
					<tr><td>{{ _lang('Type') }}</td><td>{{ $transaction->type }}</td></tr>
					<tr><td>{{ _lang('Method') }}</td><td>{{ $transaction->method }}</td></tr>
					<tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(transaction_status($transaction->status)) !!}</td></tr>
					<tr><td>{{ _lang('Note') }}</td><td>{{ $transaction->note }}</td></tr>
					<tr><td>{{ _lang('Created By') }}</td><td>{{ $transaction->created_by->name}}</td></tr>
			    </table>
			</div>
	    </div>
	</div>
</div>
@endsection


