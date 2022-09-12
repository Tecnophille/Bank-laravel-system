<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('faqs.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Question') }}</label>
				<input type="text" class="form-control" name="trans[question]" value="{{ old('trans.question') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Answer') }}</label>
				<textarea class="form-control" rows="6" name="trans[answer]" required>{{ old('trans.answer') }}</textarea>
			</div>
		</div>

	    <div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Status') }}</label>
				<select class="form-control auto-select" data-selected="{{ old('status',1) }}" name="status" required>
					<option value="1">{{ _lang('Active') }}</option>
					<option value="0">{{ _lang('Deactive') }}</option>
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
