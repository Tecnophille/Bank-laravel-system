@extends('theme.layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">{{ _lang('About Us') }}</span>
          <h1 class="text-capitalize mb-5 text-lg">{{ get_trans_option('about_page_title') }}</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section team gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="section-title text-center">
					<h2 class="mb-4">{{ get_trans_option('our_team_title') }}</h2>
					<div class="divider mx-auto my-4"></div>
					<p>{{ get_trans_option('our_team_sub_title') }}</p>
				</div>
			</div>
		</div>

		<div class="row">
			@foreach($team_members as $team_member)
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="team-block mb-5 mb-lg-0">
					<img src="{{ media_images($team_member->image) }}" alt="{{ $team_member->name }}" class="img-fluid w-100">

					<div class="content">
						<h4 class="mt-4 mb-0">{{ $team_member->name }}</h4>
						<p>{{ $team_member->role }}</p>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>

<section class="section testimonial">
	<div class="testimonial-bg" style="background: url({{ get_option('about_us_image') == '' ? asset('public/theme/images/about-us-main.jpg'): media_images(get_option('about_us_image')) }})"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 offset-lg-6">
				<div class="section-title">
					<h2 class="mb-4">{{ get_trans_option('about_page_title') }}</h2>
					<div class="divider my-4"></div>
				</div>
			</div>
		</div>
		<div class="row align-items-center">
			<div class="col-lg-6 offset-lg-6">
				{!! xss_clean(get_trans_option('about_us_content')) !!}
			</div>
		</div>
	</div>
</section>
@endsection