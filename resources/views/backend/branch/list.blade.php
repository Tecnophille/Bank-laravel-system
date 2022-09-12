@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header">
				<span class="panel-title">{{ _lang('All Branch') }}</span>
				<a class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add New Branch') }}" href="{{ route('branches.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="branches_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Contact Email') }}</th>
							<th>{{ _lang('Contact Phone') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($branchs as $branch)
					    <tr data-id="row_{{ $branch->id }}">
							<td class='name'>{{ $branch->name }}</td>
							<td class='contact_email'>{{ $branch->contact_email }}</td>
							<td class='contact_phone'>{{ $branch->contact_phone }}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('BranchController@destroy', $branch['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('BranchController@edit', $branch['id']) }}" data-title="{{ _lang('Update Branch') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="icofont-ui-edit"></i> {{ _lang('Edit') }}</a>
										<a href="{{ action('BranchController@show', $branch['id']) }}" data-title="{{ _lang('Branch Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="icofont-eye-alt"></i> {{ _lang('View') }}</a>
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