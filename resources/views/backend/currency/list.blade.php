@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-info">
			<p><i class="icofont-info-circle"></i> {{ _lang('Base Currency exchange rate always 1.00') }}</p>
		</div>
		<div class="card no-export">
		    <div class="card-header">
				<span class="panel-title">{{ _lang('Currency List') }}</span>
				<a class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add New Currency') }}" href="{{ route('currency.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="currency_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Exchange Rate') }}</th>
							<th>{{ _lang('Base Currency') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($currencys as $currency)
					    <tr data-id="row_{{ $currency->id }}">
							<td class='name'>{{ $currency->name }}</td>
							<td class='exchange_rate'>{{ $currency->exchange_rate }}</td>
							<td class='base_currency'>{!! $currency->base_currency == 1 ? xss_clean(show_status(_lang('Yes'),'success')) : xss_clean(show_status(_lang('No'),'danger')) !!}</td>
							<td class='status'>{!! xss_clean(status($currency->status)) !!}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('CurrencyController@destroy', $currency['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('CurrencyController@edit', $currency['id']) }}" data-title="{{ _lang('Update Currency') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="icofont-ui-edit"></i> {{ _lang('Edit') }}</a>
										<a href="{{ action('CurrencyController@show', $currency['id']) }}" data-title="{{ _lang('Currency Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="icofont-eye-alt"></i> {{ _lang('View') }}</a>
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