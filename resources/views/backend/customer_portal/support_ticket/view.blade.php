@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12 mt-3">
		<div class="card">
		    <div class="card-header d-flex align-items-center">
				<span class="header-title">{{ _lang('Conversations') }}</span>
				@if($supportticket->status == 1)
				<a href="{{ route('tickets.mark_as_closed', $supportticket->id) }}" class="btn btn-outline-success ml-auto"><i class="icofont-check-circled"></i> {{ _lang('Mark as Closed') }}</a>
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

				@if($supportticket->status != 2)

				<!-- Reply Form-->
				<h5 class="mb-30 padding-top-1x">{{ _lang('Leave Message') }}</h5>
				<form method="post" action="{{ route('tickets.reply',$supportticket->id) }}" enctype="multipart/form-data">
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


