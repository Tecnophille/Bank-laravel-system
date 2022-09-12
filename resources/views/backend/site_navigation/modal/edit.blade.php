<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('NavigationController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Name') }}</label>
		   <input type="text" class="form-control" name="name" value="{{ $navigation->name }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Status') }}</label>
			<select class="form-control auto-select" data-selected="{{ $navigation->status }}" name="status" required>
				<option value="1">{{ _lang('Active') }}</option>
				<option value="0">{{ _lang('In Active') }}</option>
			</select>
		</div>
	</div>

	<div class="form-group">
	    <div class="col-md-12">
		    <button type="submit" class="btn btn-primary"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
	    </div>
	</div>
</form>

