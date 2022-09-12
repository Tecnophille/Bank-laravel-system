@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ _lang('Teams') }}</span>
				<a class="btn btn-primary btn-sm ml-auto ajax-modal" data-title="{{ _lang('Add Team Member') }}" href="{{ route('teams.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="teams_table" class="table table-bordered data-table">
					<thead>
					    <tr>
                            <th>{{ _lang('Image') }}</th>
						    <th>{{ _lang('Name') }}</th>
						    <th>{{ _lang('Role') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($teams as $team)
					    <tr data-id="row_{{ $team->id }}">
                            <td class='image'><img src="{{ media_images($team->image) }}" class="thumb-sm img-thumbnail"/></td>
							<td class='name'>{{ $team->name }}</td>
							<td class='role'>{{ $team->role }}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('TeamController@destroy', $team['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('TeamController@edit', $team['id']) }}" data-title="{{ _lang('View Team') }}" class="dropdown-item dropdown-view ajax-modal"><i class="icofont-edit-alt"></i> {{ _lang('Edit') }}</a>
										<button class="btn-remove dropdown-item" type="submit"><i class="icofont-trash"></i> {{ _lang('Delete') }}</button>
									</div>
								  </form>
								</span>
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