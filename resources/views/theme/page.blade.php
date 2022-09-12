@extends('theme.layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">{{ $page->translation->title }}</span>
          <h1 class="text-capitalize mb-5 text-lg">{{ $page->translation->title }}</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section general-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
        {!! xss_clean($page->translation->body) !!}
			</div>
		</div>
	</div>
</section>
@endsection