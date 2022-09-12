<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('TeamController@update', $id) }}" enctype="multipart/form-data">
    {{ csrf_field()}}
    <input name="_method" type="hidden" value="PATCH">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Name') }}</label>
                <input type="text" class="form-control" name="name" value="{{ $team->name }}" required>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Image') }}</label>
                <input type="file" class="form-control dropify" name="image"  data-default-file="{{ $team->image != '' ? asset('public/uploads/media/'.$team->image) : '' }}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Role') }}</label>
                <input type="text" class="form-control" name="role" value="{{ $team->role }}"  data-default-file="{{ $team->image != '' ? asset('public/uploads/media/'.$team->image) : '' }}">
            </div>
        </div>


        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
            </div>
        </div>
    </div>
</form>