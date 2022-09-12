@extends('theme.layout')


@section('content')
<!-- Slider Start -->
<section class="banner d-flex align-items-center" style="background: url({{ get_option('home_banner') == '' ? asset('public/theme/images/slider-bg-1.jpg') : media_images(get_option('home_banner')) }})">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<div class="block">
					<h1 class="mb-3">{{ get_trans_option('main_heading') }}</h1>

					<p class="mb-4 pr-5 text-white">{{ get_trans_option('sub_heading') }}</p>
					<div class="btn-container">
						<a href="{{ get_option('allow_singup') == 'yes' ? route('register') : route('login') }}" target="_blank" class="btn btn-main-2">Get Started <i class="icofont-simple-right ml-2"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section about">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="about-img">
					<img src="{{ get_option('home_about_us_banner') == '' ? asset('public/theme/images/about-us.jpg') : media_images(get_option('home_about_us_banner')) }}" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-6">
				<div class="about-content pl-4 mt-4 mt-lg-0">
					<h2 class="title-color">{{ get_trans_option('home_about_us_heading') }}</h2>
					<p class="mt-4 mb-5">{{ get_trans_option('home_about_us_content') }}</p>

					<a href="{{ url('/services') }}" class="btn btn-main-2 btn-icon">{{ _lang('Services') }}<i class="icofont-simple-right ml-3"></i></a>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="cta-section ">
	<div class="container">
		<div class="cta position-relative">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-doctor"></i>
						<span class="h3">{{ get_option('total_customer',0) }}</span>+
						<p>{{ _lang('Customers') }}</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-flag"></i>
						<span class="h3">{{ get_option('total_branch',0) }}</span>
						<p>{{ _lang('Branches') }}</p>
					</div>
				</div>

				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-credit-card"></i>
						<span class="h3">{{ get_option('total_transactions',0) }}</span>M
						<p>{{ _lang('Total Transactions') }}</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-globe"></i>
						<span class="h3">{{ get_option('total_countries',0) }}</span>+
						<p>{{ _lang('Supported Country') }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section service gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<div class="section-title">
					<h2>{{ get_trans_option('home_service_heading') }}</h2>
					<div class="divider mx-auto my-4"></div>
					<p>{{ get_trans_option('home_service_content') }}</p>
				</div>
			</div>
		</div>

		<div class="row">
		@foreach($services as $service)
			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						{!! xss_clean($service->icon) !!}
						<h4 class="mt-3 mb-3">{{ $service->translation->title }}</h4>
					</div>

					<div class="content">
						<p class="mb-4">{{ $service->translation->body }}</p>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	</div>
</section>

<section class="section fdr-plan">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<div class="section-title">
					<h2>{{ get_trans_option('home_fixed_deposit_heading') }}</h2>
					<div class="divider mx-auto my-4"></div>
					<p>{{ get_trans_option('home_fixed_deposit_content') }}</p>
				</div>
			</div>
		</div>

		<div class="row">

			@foreach($fdr_plans as $fdr_plan)
			<div class="col-lg-4">
				<div class="fdr-item mb-4">
					<div class="title">
						<div class="d-flex flex-wrap justify-content-between">
							<h4 class="my-3">{{ $fdr_plan->name }}</h4>
							<h4 class="my-3">{{ $fdr_plan->interest_rate }}%</h4>
						</div>
					</div>

					<div class="content">
						<ul class="plan-feature-list pl-0">
							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Duration') }}</span>
								<span>{{ $fdr_plan->duration.' '.ucwords($fdr_plan->duration_type) }}</span>
							</li>
							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Interest Rate') }}</span>
								<span>{{ $fdr_plan->interest_rate.' %' }}</span>
							</li>

							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Minimum') }}</span>
								<span>{{ decimalPlace($fdr_plan->minimum_amount, currency()) }}</span>
							</li>
							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Maximum') }}</span>
								<span>{{ decimalPlace($fdr_plan->maximum_amount, currency()) }}</span>
							</li>
						</ul>
						<a href="{{ route('fixed_deposits.apply') }}" class="btn btn-main-2 btn-block">{{ _lang('Apply Now') }}</a>
					</div>
				</div>
			</div>
			@endforeach

		</div>
	</div>
</section>


<section class="section loan gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<div class="section-title">
					<h2>{{ get_trans_option('home_loan_heading') }}</h2>
					<div class="divider mx-auto my-4"></div>
					<p>{{ get_trans_option('home_loan_content') }}</p>
				</div>
			</div>
		</div>

		<div class="row">
			@foreach($loan_plans as $loan_plan)
			<div class="col-lg-4">
				<div class="loan-item mb-4">
					<div class="title">
						<div class="d-flex flex-wrap justify-content-between">
							<h4 class="my-3">{{ $loan_plan->name }}</h4>
							<h4 class="my-3">{{ $loan_plan->interest_rate.' %' }}</h4>
						</div>
					</div>

					<div class="content">
						<ul class="plan-feature-list pl-0">
							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Term') }}</span>
								<span>
									{{ $loan_plan->term }}
									@if($loan_plan->term_period === '+1 month')
										{{ _lang('Month') }}
									@elseif($loan_plan->term_period === '+1 year')
										{{ _lang('Year') }}
									@elseif($loan_plan->term_period === '+1 day')
										{{ _lang('Day') }}
									@elseif($loan_plan->term_period === '+1 week')
										{{ _lang('Week') }}
									@endif
								</span>
							</li>

							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Interest Rate') }}</span>
								<span>{{ $loan_plan->interest_rate.' %' }}</span>
							</li>

							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Interest Type') }}</span>
								<span>{{ ucwords(str_replace("_"," ", $loan_plan->interest_type)) }}</span>
							</li>

							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Minimum') }}</span>
								<span>{{ decimalPlace($loan_plan->minimum_amount, currency()) }}</span>
							</li>

							<li class="d-flex flex-wrap justify-content-between">
								<span>{{ _lang('Maximum') }}</span>
								<span>{{ decimalPlace($loan_plan->maximum_amount, currency()) }}</span>
							</li>
						</ul>
						<a href="{{ route('loans.apply_loan') }}" class="btn btn-main btn-block">Apply Now</a>
					</div>
				</div>
			</div>
			@endforeach

		</div>
	</div>
</section>


<section class="section testimonial-2">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="section-title text-center">
					<h2>{{ get_trans_option('home_testimonial_heading') }}</h2>
					<div class="divider mx-auto my-4"></div>
					<p>{{ get_trans_option('home_testimonial_content') }}</p>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-12 testimonial-wrap-2">
			@foreach($testimonials as $testimonial)
				<div class="testimonial-block style-2 gray-bg">
					<i class="icofont-quote-right"></i>

					<div class="testimonial-thumb">
						<img src="{{ media_images($testimonial->image) }}" alt="{{ $testimonial->translation->name }}" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>{{ $testimonial->translation->name }}</h4>
						<p>{{ $testimonial->translation->testimonial }}</p>
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
</section>
@endsection
