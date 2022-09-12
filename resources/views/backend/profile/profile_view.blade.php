@extends('layouts.app')

@section('content')

@php $date_format = get_option('date_format','Y-m-d'); @endphp

<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header text-center">
				{{ _lang('My Profile Overview') }}
			</div>

			<div class="card-body">
				<table class="table table-bordered" width="100%">
					<tbody>
						<tr class="text-center">
							<td colspan="2"><img class="thumb-image-sm img-thumbnail" src="{{ profile_picture($profile->profile_picture) }}"></td>
						</tr>
							<tr>
								<td>{{ _lang('Name') }}</td>
								<td>{{ $profile->name }}</td>
							</tr>
							<tr>
								<td>{{ _lang('Email') }}</td>
								<td>{{ $profile->email }}</td>
							</tr>

							@if($profile->user_type != 'customer')
							<tr>
								<td>{{ _lang('User Type') }}</td>
								<td>{{ ucwords($profile->user_type) }}</td>
							</tr>
							@endif

							@if($profile->user_type == 'customer')
							<tr>
								<td>{{ _lang('Phone') }}</td>
								<td>{{ '+'.$profile->country_code.$profile->phone }}</td>
							</tr>
							<tr>
								<td>{{ _lang('Branch') }}</td>
								<td>{{ $profile->branch->name}}</td>
							</tr>
							<tr>
								<td>{{ _lang('Email Verified') }}</td>
								<td>{!! $profile->email_verified_at != null ? xss_clean(show_status(_lang('Yes'), 'primary')) : xss_clean(show_status(_lang('No'), 'danger')) !!}</td>
							</tr>
							<tr>
								<td>{{ _lang('Mobile Verified') }}</td>
								<td>{!! $profile->sms_verified_at != null ? xss_clean(show_status(_lang('Yes'), 'primary')) : xss_clean(show_status(_lang('No'), 'danger')) !!}</td>
							</tr>
							@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection