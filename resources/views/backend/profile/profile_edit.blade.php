@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header text-center">
				{{ _lang('Profile Settings') }}
			</div>
			<div class="card-body">

				<form action="{{ route('profile.update') }}" autocomplete="off" class="form-horizontal form-groups-bordered validate" enctype="multipart/form-data" method="post">
					@csrf
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Name') }}</label>
								<input type="text" class="form-control" name="name" value="{{$profile->name}}" required>
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Email') }}</label>
								<input type="text" class="form-control" name="email" value="{{ $profile->email }}" required>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Country Code') }}</label>
								<select class="form-control select2 auto-select" data-selected="{{ $profile->country_code }}" name="country_code" required>
									<option value="">{{ _lang('Select One') }}</option>
									@foreach(get_country_codes() as $key => $value)
									<option value="{{ $value['dial_code'] }}">{{ $value['country'].' (+'.$value['dial_code'].')' }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Phone') }}</label>
								<input type="text" class="form-control" name="phone" value="{{ $profile->phone }}" required>
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Image') }} (300 X 300)</label>
								<input type="file" class="form-control dropify" data-default-file="{{ $profile->profile_picture != "" ? asset('public/uploads/profile/'.$profile->profile_picture) : '' }}" name="profile_picture" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG">
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block"><i class="icofont-check-circled"></i> {{ _lang('Update Profile') }}</button>
							</div>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>
@endsection

