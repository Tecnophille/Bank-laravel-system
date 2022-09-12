@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ $title }}</span>
				<a class="btn btn-primary btn-sm ml-auto" href="{{ route('tickets.create_ticket') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="support_tickets_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('ID') }}</th>
							<th>{{ _lang('Subject') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th>{{ _lang('Created') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
						@foreach($supporttickets as $supportticket)
						<tr>
						    <td>{{ $supportticket->id }}</td>
							<td>{{ $supportticket->subject }}</td>
							<td>{!! xss_clean(ticket_status($supportticket->status)) !!}</td>
							<td>{{ $supportticket->created_at }}</td>
							<td class="text-center">
								@if($supportticket->status == 2)
									<a href="{{ route('tickets.show', $supportticket['id']) }}" class="btn btn-primary btn-sm"><i class="icofont-ui-messaging"></i> {{ _lang('View Conversations') }}</a>
								@endif
								@if($supportticket->status != 2)
									<a href="{{ route('tickets.show', $supportticket['id']) }}" class="btn btn-primary btn-sm"><i class="icofont-reply"></i> {{ _lang('Reply') }}</a>
									<a href="{{ route('tickets.mark_as_closed', $supportticket['id']) }}" class="btn btn-success btn-sm"><i class="icofont-check-circled"></i> {{ _lang('Mark as Closed') }}</a>
								@endif
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