@extends('install.layout')

@section('content')
<div class="card">
	<div class="card-header bg-dark text-white text-center">Check Requirements</div>
	<div class="card-body">
	  @if(empty($requirements))
		<div class="text-center">  
		  <h4>Your Server is ready for installation.</h4>
	      <a href="{{ url('install/database') }}" class="btn btn-install">Next</a>
		</div>
      @else
        @foreach($requirements as $r)
		   <p class="required"><i class="glyphicon glyphicon-info-sign"></i> {{ $r }}</p>
        @endforeach	
	  @endif
	</div>
</div>
@endsection