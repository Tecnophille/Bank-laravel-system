@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h4 class="header-title">{{ _lang('Create Email Template') }}</h4>
			</div>
			<div class="card-body">
			  <form method="post" class="validate" autocomplete="off" action="{{ route('email_templates.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{{ _lang('Name') }}</label>
							<input type="text" class="form-control" name="name" value="{{ old('name') }}">
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
							<label class="control-label">{{ _lang('Body') }}</label>
							<textarea class="form-control summernote" name="body" required>{{ old('body') }}</textarea>
						</div>
					</div>


					<div class="form-group">
						<div class="col-md-12">
							<button type="reset" class="btn btn-danger">{{ _lang('Reset') }}</button>
							<button type="submit" class="btn btn-primary"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection


