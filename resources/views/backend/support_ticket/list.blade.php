@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header d-flex align-items-center">
				<span class="panel-title">{{ $title }}</span>
				@if(has_permission('support_tickets.create'))
				<a class="btn btn-primary btn-sm ml-auto ajax-modal" data-title="{{ _lang('Create New Ticket') }}" href="{{ route('support_tickets.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
				@endif
			</div>
			<div class="card-body">
				<table id="support_tickets_table" class="table table-bordered" data-status="{{ $status }}">
					<thead>
					    <tr>
						    <th>{{ _lang('User') }}</th>
						    <th>{{ _lang('Email') }}</th>
							<th>{{ _lang('Subject') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th>{{ _lang('Created') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-script')
<script src="{{ asset('public/backend/assets/js/datatables/support_tickets.js') }}"></script>
@endsection