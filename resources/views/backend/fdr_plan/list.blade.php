@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header">
				<span class="panel-title">{{ _lang('FDR Plans') }}</span>
				<a class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add New Plan') }}" href="{{ route('fdr_plans.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="fdr_plans_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Minimum Amount') }}</th>
							<th>{{ _lang('Maximum Amount') }}</th>
							<th>{{ _lang('Interest Rate') }}</th>
							<th>{{ _lang('Duration') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($fdrplans as $fdrplan)
					    <tr data-id="row_{{ $fdrplan->id }}">
							<td class='name'>{{ $fdrplan->name }}</td>
							<td class='minimum_amount'>{{ decimalPlace($fdrplan->minimum_amount, currency()) }}</td>
							<td class='maximum_amount'>{{ decimalPlace($fdrplan->maximum_amount, currency()) }}</td>
							<td class='interest_rate'>{{ $fdrplan->interest_rate.' %' }}</td>
							<td class='duration'>{{ $fdrplan->duration.' '.ucwords($fdrplan->duration_type) }}</td>
							<td class='status'>{!! xss_clean(status($fdrplan->status)) !!}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('FDRPlanController@destroy', $fdrplan['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('FDRPlanController@edit', $fdrplan['id']) }}" data-title="{{ _lang('Update Plan') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="icofont-ui-edit"></i> {{ _lang('Edit') }}</a>
										<a href="{{ action('FDRPlanController@show', $fdrplan['id']) }}" data-title="{{ _lang('Plan Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="icofont-eye-alt"></i> {{ _lang('View') }}</a>
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