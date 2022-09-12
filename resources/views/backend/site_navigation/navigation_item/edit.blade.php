@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('Update Navigation Item') }}</span>
			</div>
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ action('NavigationItemController@update', $id) }}" enctype="multipart/form-data">
					{{ csrf_field()}}
					<input name="_method" type="hidden" value="PATCH">
					<div class="row">

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('Name') }}</label>
						        <input type="text" class="form-control" name="trans[name]" value="{{ $navigationitem->translation->name }}">
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('Navigation Type') }}</label>
						        <select class="form-control auto-select" data-selected="{{ $navigationitem->type }}" id="navigation_type" name="type" required>
									<option value='page'>{{ _lang('Page') }}</option>
									<option value='dynamic_url'>{{ _lang('Dynamic URL') }}</option>
									<option value='custom_url'>{{ _lang('Custom URL') }}</option>
								</select>
							</div>
					    </div>

						<div class="col-md-6 {{ $navigationitem->type == 'page' ?: 'd-none' }}" id="page">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('Page') }} <span class="required"> *</span></label>
						        <select class="form-control select2 auto-select" data-selected="{{ $navigationitem->page_id }}" name="page_id">
						        	<option value="">{{ _lang('Select Page') }}</option>
									@foreach(\App\Models\Page::all() as $page)
										<option value="{{ $page->id }}">{{ $page->translation->title }}</option>
									@endforeach
						        </select>
					        </div>
					    </div>

						<div class="col-md-6  {{ $navigationitem->type == 'dynamic_url' || $navigationitem->type == 'custom_url' ?: 'd-none' }}" id="url">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('URL') }} <span class="required"> *</span></label>
						        <input type="text" class="form-control" name="url" value="{{ $navigationitem->url }}">
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('Icon') }}</label>
						        <input type="text" class="form-control" name="icon" value="{{ $navigationitem->icon }}">
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('Target') }}</label>
						        <select class="form-control auto-select" data-selected="{{ $navigationitem->target }}" name="target" required>
									<option value="_self">{{ _lang('Same Window') }}</option>
									<option value="_blank">{{ _lang('New Window') }}</option>
								</select>
							</div>
					    </div>

					    <div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('CSS Class') }}</label>
						        <input type="text" class="form-control" name="css_class" value="{{ $navigationitem->css_class }}">
					        </div>
					    </div>

					    <div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('CSS ID') }}</label>
						        <input type="text" class="form-control" name="css_id" value="{{ $navigationitem->css_id }}">
					        </div>
					    </div>

						<div class="col-md-6">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('Status') }}</label>
						        <select class="form-control auto-select" data-selected="{{ $navigationitem->status }}" name="status" required>
									<option value="1">{{ _lang('Active') }}</option>
									<option value="0">{{ _lang('In Active') }}</option>
								</select>
							</div>
					    </div>


						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection


@section('js-script')
<script src="{{ asset('public/backend/assets/js/navigation.js') }}"></script>
@endsection



