@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ $title }}</span>
				<a class="btn btn-primary btn-sm ml-auto ajax-modal" data-title="{{ _lang('Create Gift Card') }}" href="{{ route('gift_cards.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="gift_cards_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Code') }}</th>
							<th>{{ _lang('Currency') }}</th>
							<th>{{ _lang('Amount') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($giftcards as $giftcard)
					    <tr data-id="row_{{ $giftcard->id }}">
							<td class='code'>{{ $giftcard->code }}</td>
							<td class='currency_id'>{{ $giftcard->currency->name }}</td>
							<td class='amount'>{{ decimalPlace($giftcard->amount, currency($giftcard->currency->name)) }}</td>
							<td class='status'>{!! $giftcard->status == 0 ? xss_clean(show_status(_lang('Unused'),'primary')) : xss_clean(show_status(_lang('Used'),'danger')) !!}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('GiftCardController@destroy', $giftcard['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('GiftCardController@edit', $giftcard['id']) }}" data-title="{{ _lang('Update Gift Card') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="icofont-ui-edit"></i> {{ _lang('Edit') }}</a>
										<a href="{{ action('GiftCardController@show', $giftcard['id']) }}" data-title="{{ _lang('Gift Card Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="icofont-eye-alt"></i> {{ _lang('View') }}</a>
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