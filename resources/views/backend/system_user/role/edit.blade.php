@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title">{{ _lang('Update Role') }}</h4>
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ action('RoleController@update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
							   <label class="control-label">{{ _lang('Name') }}</label>
							   <input type="text" class="form-control" name="name" value="{{ $role->name }}" required>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
							   <label class="control-label">{{ _lang('Description') }}</label>
							   <textarea class="form-control" name="description">{{ $role->description }}</textarea>
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


