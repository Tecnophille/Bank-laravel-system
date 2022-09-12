@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ $navigation->name }}</span>
				<a class="btn btn-primary btn-sm ml-auto" href="{{ route('navigation_items.create', $navigation->id) }}"><i class="icofont-plus-circle"></i> {{ _lang('New Navigation Item') }}</a>
			</div>
			<div class="card-body">

			    <div class="dd">
				  {{ navigationTree($navigationitems, 0, 'NavigationItemController') }}
			    </div>

				<form method="post" action="{{ route('navigations.store_sorting') }}">
					{{ csrf_field() }}
					<textarea class="form-control d-none" name="sortable_menu" id="nestable-output"></textarea>
					</br>
					<button type="submit" class="btn btn-outline-success" id="save"><i class="icofont-check-circled"></i> {{ _lang('Save Sorting') }}</button>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('js-script')
<script src="{{ asset('public/backend/assets/js/jquery.nestable.js') }}"></script>
<script src="{{ asset('public/backend/assets/js/navigation.js') }}"></script>
@endsection


