@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="header-title">{{ _lang('Ticket Details') }}</span>
			</div>

			<div class="card-body">
			    <table class="table table-striped">
				    <tr>
						<td><b>{{ _lang('User') }}</b></td><td>{{ $supportticket->user->name }} - {{ $supportticket->user->email }}</td>
					</tr>
					<tr>
						<td><b>{{ _lang('Subject') }}</b></td>
						<td>{{ $supportticket->subject }}</td>
					</tr>
					<tr>
						<td>{{ _lang('Status') }}</td><td>{!! xss_clean(ticket_status($supportticket->status)) !!}</td>
					</tr>
					<tr>
						<td>{{ _lang('Created') }}</td><td>{{ $supportticket->created_by->name }}</td>
					</tr>
					<tr>
						<td>{{ _lang('Support Staff') }}</td>
						@if($supportticket->operator_id != null)
							<td>{{  $supportticket->operator->name }}</td>
						@else
							<td>
								<div class="dropdown">
									<button class="btn btn-warning btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										{{ _lang('Select Staff') }}
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										@foreach(\App\Models\User::where('user_type','!=','customer')->get() as $user)
											<a href="{{ route('support_tickets.assign_staff', [$supportticket->id, $user->id]) }}" class="dropdown-item">{{ $user->name }}</a>
										@endforeach
									</div>
								</div>
							</td>
						@endif
					</tr>
					@if($supportticket->status == 2)
						<tr><td>{{ _lang('Closed') }}</td><td>{{ $supportticket->closed_by->name }}</td></tr>
					@endif
			    </table>
			</div>
	    </div>
	</div>

	<div class="col-lg-12 mt-3">
		<div class="card">
		    <div class="card-header d-flex align-items-center">
				<span class="header-title">{{ _lang('Conversations') }}</span>
				@if($supportticket->status == 1 && has_permission('support_tickets.mark_as_closed'))
					<a href="{{ route('support_tickets.mark_as_closed', $supportticket->id) }}" class="btn btn-outline-success ml-auto"><i class="icofont-check-circled"></i> {{ _lang('Mark as Closed') }}</a>
				@endif
			</div>

			<div class="card-body">
				<!-- Messages-->
				@foreach($supportticket->messages as $message)
				<div class="comment">
					<div class="comment-author-ava"><img src="{{ profile_picture($message->sender->profile_picture) }}" alt="Avatar"></div>
					<div class="comment-body">
						<p class="comment-text">{{ $message->message }}</p>
						@if($message->attachment != null)
						<a href="{{ asset('public/uploads/media/'.$message->attachment) }}" target="_blank"><small><i class="icofont-attachment"></i> {{ $message->attachment }}</small></a>
						@endif
						<div class="comment-footer"><span class="comment-meta">{{ $message->sender->name }}</span></div>
					</div>
				</div>
				@endforeach

				@if($supportticket->status == 1)

				<!-- Reply Form-->
				<h5 class="mb-30 padding-top-1x">{{ _lang('Leave Message') }}</h5>
				<form method="post" action="{{ route('support_tickets.reply',$supportticket->id) }}" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<textarea class="form-control form-control-rounded" name="message" rows="8" placeholder="{{ _lang('Write your message here') }}..." required>{{ old('message') }}</textarea>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="form-group">
								<input type="file" class="trickycode-file" name="attachment">
							</div>
						</div>
						<div class="col-lg-12">
							<div class="text-right">
								<button class="btn btn-outline-primary" type="submit">{{ _lang('Send Message') }}</button>
							</div>
						</div>
					</div>
				</form>

				@endif
			</div>
		</div>
	</div>

</div>
@endsection


