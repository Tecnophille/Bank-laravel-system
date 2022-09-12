<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('services.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row px-2">
	    <div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('ICON') }}</label>
				<a href="https://icofont.com/icons" class="float-right" target="_blank">{{ _lang('BROWSE ICONS') }}</a>
				<input type="text" class="form-control" name="icon" value="{{ old('icon') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Title') }}</label>
				<input type="text" class="form-control" name="trans[title]" value="{{ old('trans.title') }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Body') }}</label>
				<textarea class="form-control" name="trans[body]">{{ old('trans.body') }}</textarea>
			</div>
		</div>

		<div class="col-md-12">
		    <div class="form-group">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
		    </div>
		</div>
	</div>
</form>
