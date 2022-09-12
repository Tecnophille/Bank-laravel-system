@extends('theme.layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
			<span class="text-white">{{ _lang('FAQ') }}</span>
            <h1 class="text-capitalize mb-5 text-lg">{{ _lang('Frequently Asked Questions') }}</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section service-2">
   <div class="container">
      <div class="row">
         <div class="col-lg-8 offset-lg-2">
            @foreach($faqs as $faq)
            <div class="faq-item">
               <h3>
                  <a class="faq-question collapsed" data-toggle="collapse" href="#faq-{{ $faq->id }}" role="button" aria-expanded="false" aria-controls="faq-1">
                  {{ $faq->translation->question }}
                  </a>
               </h3>
               <div class="collapse" id="faq-{{ $faq->id }}">
                  <div class="faq-content">
                  {{ $faq->translation->answer }}
                  </div>
               </div>
            </div>
            <hr>
            @endforeach
         </div>
      </div>
   </div>
</section>
@endsection