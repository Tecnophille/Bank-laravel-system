@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ _lang('Navigation List') }}</span>
				<a class="btn btn-primary btn-sm ml-auto" data-title="{{ _lang('Add Navigation') }}" href="{{ route('navigations.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="navigations_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Name') }}</th>
						    <th>{{ _lang('Manage') }}</th>
						    <th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($navigations as $navigation)
					    <tr data-id="row_{{ $navigation->id }}">
							<td class='name'>{{ $navigation->name }}</td>
							<td class='manage'>
								<a href="{{ route('navigations.show',$navigation->id) }}" class="btn btn-success btn-sm"><i class="icofont-navigation-menu"></i> {{ _lang('Manage Menu Items') }}</a>
							</td>
							<td class='status'>{{ $navigation->status == 1 ? _lang('Active') : _lang('In-Active') }}</td>
							<td class="text-center">
								<div class="dropdown">
								  <button class="btn btn-dark dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  <i class="fas fa-angle-down"></i>
								  </button>
								  <form action="{{ action('NavigationController@destroy', $navigation['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('NavigationController@edit', $navigation['id']) }}" data-title="{{ _lang('Update Navigation') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</a>
										<button class="btn-remove dropdown-item" type="submit"><i class="mdi mdi-delete"></i> {{ _lang('Delete') }}</button>
									</div>
								  </form>
								</div>
							</td>
					    </tr>
					    @endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection