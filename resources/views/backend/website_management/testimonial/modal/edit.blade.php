<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('TestimonialController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">
	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Name') }}</label>
				<input type="text" class="form-control" name="trans[name]" value="{{ $testimonial->translation->name }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Testimonial') }}</label>
				<textarea class="form-control" name="trans[testimonial]" required>{{ $testimonial->translation->testimonial }}</textarea>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Image') }}</label>
				<input type="file" class="form-control dropify" name="image" data-default-file="{{ $testimonial->image != '' ? asset('public/uploads/media/'.$testimonial->image) : '' }}">
			</div>
		</div>


		<div class="form-group">
		    <div class="col-md-12">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
		    </div>
		</div>
	</div>
</form>

