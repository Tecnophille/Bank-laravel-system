<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('support_tickets.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row px-2">
	    <div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('User') }}</label>
				<select class="form-control auto-select" data-selected="{{ old('user_id') }}" name="user_id"  required>
					<option value="">{{ _lang('Select One') }}</option>
					@foreach(get_table('users',array('user_type='=>'customer')) as $user )
						<option value="{{ $user->id }}">{{ $user->email .' ('. $user->name . ')' }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Subject') }}</label>
				<input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Message') }}</label>
				<textarea class="form-control" name="message" required>{{ old('message') }}</textarea>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Attachment') }}</label>
				<input type="file" class="file form-control" name="attachment">
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Status') }}</label>
				<select class="form-control auto-select" data-selected="{{ old('status') }}" name="status" required>
					<option value="">{{ _lang('Select One') }}</option>
					<option value="0">{{ _lang('Pending') }}</option>
					<option value="1">{{ _lang('Open') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
		    <div class="form-group">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
		    </div>
		</div>
	</div>
</form>
