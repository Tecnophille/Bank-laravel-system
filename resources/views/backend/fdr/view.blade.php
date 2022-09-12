@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="header-title">{{ _lang('Fixed Deposit Details') }}</span>
			</div>

			<div class="card-body">
			    <table class="table table-bordered">
				    <tr><td>{{ _lang('Deposit Plan') }}</td><td>{{ $fixeddeposit->plan->name }}</td></tr>
					<tr><td>{{ _lang('User Account') }}</td><td>{{ $fixeddeposit->user->name }}</td></tr>
					<tr><td>{{ _lang('Currency') }}</td><td>{{ $fixeddeposit->currency->name }}</td></tr>
					<tr><td>{{ _lang('Deposit Amount') }}</td><td>{{ $fixeddeposit->deposit_amount }}</td></tr>
					<tr><td>{{ _lang('Return Amount') }}</td><td>{{ $fixeddeposit->return_amount }}</td></tr>
					<tr>
						<td>{{ _lang('Attachment') }}</td>
						<td>
							{!! $fixeddeposit->attachment == "" ? '' : '<a href="'. asset('public/uploads/media/'.$fixeddeposit->attachment) .'" target="_blank">'._lang('Download').'</a>' !!}
						</td>
					</tr>
					<tr><td>{{ _lang('Remarks') }}</td><td>{{ $fixeddeposit->remarks }}</td></tr>
					<tr><td>{{ _lang('Status') }}</td><td>{!! xss_clean(fdr_status($fixeddeposit->status)) !!}</td></tr>
					<tr><td>{{ _lang('Approved Date') }}</td><td>{{ $fixeddeposit->approved_date }}</td></tr>
					<tr><td>{{ _lang('Mature Date') }}</td><td>{{ $fixeddeposit->mature_date }}</td></tr>
					<tr><td>{{ _lang('Approved By') }}</td><td>{{ $fixeddeposit->approved_by->name }}</td></tr>
					<tr><td>{{ _lang('Created By') }}</td><td>{{ $fixeddeposit->created_by->name }}</td></tr>
					<tr><td>{{ _lang('Updated By') }}</td><td>{{ $fixeddeposit->updated_by->name }}</td></tr>
			    </table>
			</div>
	    </div>
	</div>
</div>
@endsection


