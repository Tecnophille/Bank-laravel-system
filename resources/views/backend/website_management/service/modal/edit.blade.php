<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('ServiceController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">
	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('ICON') }}</label>
				<a href="https://icofont.com/icons" class="float-right" target="_blank">{{ _lang('BROWSE ICONS') }}</a>
				<input type="text" class="form-control" name="icon" value="{{ $service->icon }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Title') }}</label>
				<input type="text" class="form-control" name="trans[title]" value="{{ $service->translation->title }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Body') }}</label>
				<textarea class="form-control" name="trans[body]">{{ $service->translation->body }}</textarea>
			</div>
		</div>


		<div class="form-group">
		    <div class="col-md-12">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
		    </div>
		</div>
	</div>
</form>

