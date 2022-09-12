<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('testimonials.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Name') }}</label>
				<input type="text" class="form-control" name="trans[name]" value="{{ old('trans.name') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Testimonial') }}</label>
				<textarea class="form-control" name="trans[testimonial]" required>{{ old('trans.testimonial') }}</textarea>
			</div>
		</div>

	    <div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Image') }}</label>
				<input type="file" class="form-control dropify" name="image" >
			</div>
		</div>

		<div class="col-md-12">
		    <div class="form-group">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
		    </div>
		</div>
	</div>
</form>
