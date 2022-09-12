@extends('theme.layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">{{ _lang('Our Services') }}</span>
          <h1 class="text-capitalize mb-5 text-lg">{{ _lang('Our Services') }}</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section service-2">
	<div class="container">
		<div class="row">
		@foreach($services as $service)
			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-block mb-5 text-center">
					{!! xss_clean($service->icon) !!}
					<div class="content">
						<h4 class="mt-4 mb-2 title-color">{{ $service->translation->title }}</h4>
						<p class="mb-4">{{ $service->translation->body }}</p>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	</div>
</section>
@endsection