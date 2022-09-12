@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header">
				<span class="panel-title">{{ _lang('Other Banks') }}</span>
				<a class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add New Bank') }}" href="{{ route('other_banks.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="other_banks_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Swift Code') }}</th>
							<th>{{ _lang('Currency') }}</th>
							<th>{{ _lang('Minimum Transfer') }}</th>
							<th>{{ _lang('Maximum Transfer') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($otherbanks as $otherbank)
					    <tr data-id="row_{{ $otherbank->id }}">
							<td class='name'>{{ $otherbank->name }}</td>
							<td class='swift_code'>{{ $otherbank->swift_code }}</td>
							<td class='bank_currency'>{{ $otherbank->currency->name }}</td>
							<td class='minimum_transfer_amount'>{{ $otherbank->minimum_transfer_amount }}</td>
							<td class='maximum_transfer_amount'>{{ $otherbank->maximum_transfer_amount }}</td>
							<td class='status'>{!! xss_clean(status($otherbank->status)) !!}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('OtherBankController@destroy', $otherbank['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('OtherBankController@edit', $otherbank['id']) }}" data-title="{{ _lang('Update Bank Details') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="icofont-ui-edit"></i> {{ _lang('Edit') }}</a>
										<a href="{{ action('OtherBankController@show', $otherbank['id']) }}" data-title="{{ _lang('Bank Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="icofont-eye-alt"></i> {{ _lang('View') }}</a>
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