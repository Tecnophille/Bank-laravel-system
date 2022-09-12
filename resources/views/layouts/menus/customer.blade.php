<div class="sb-sidenav-menu-heading">{{ _lang('NAVIGATIONS') }}</div>

<a class="nav-link" href="{{ route('dashboard.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-dashboard-web"></i></div>
	{{ _lang('Dashboard') }}
</a>

<a class="nav-link" href="{{ route('transfer.send_money') }}">
	<div class="sb-nav-link-icon"><i class="icofont-location-arrow"></i></div>
	{{ _lang('Send Money') }}
</a>

<a class="nav-link" href="{{ route('transfer.exchange_money') }}">
	<div class="sb-nav-link-icon"><i class="icofont-exchange"></i></div>
	{{ _lang('Exchange Money') }}
</a>

<a class="nav-link" href="{{ route('transfer.wire_transfer') }}">
	<div class="sb-nav-link-icon"><i class="icofont-bank-transfer"></i></div>
	{{ _lang('Wire Transfer') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#payment_request" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-credit-card"></i></div>
	{{ _lang('Payment Request') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="payment_request" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('payment_requests.create') }}">{{ _lang('New Request') }}</a>
		<a class="nav-link" href="{{ route('payment_requests.index') }}">{{ _lang('All Requests') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#deposit" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-plus-circle"></i></div>
	{{ _lang('Deposit Money') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="deposit" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('deposit.automatic_methods') }}">{{ _lang('Automatic Deposit') }}</a>
		<a class="nav-link" href="{{ route('deposit.manual_methods') }}">{{ _lang('Manual Deposit') }}</a>
		<a class="nav-link" href="{{ route('deposit.redeem_gift_card') }}">{{ _lang('Redeem Gift Card') }}</a>
	</nav>
</div>

<a class="nav-link" href="{{ route('withdraw.manual_methods') }}">
	<div class="sb-nav-link-icon"><i class="icofont-minus-circle"></i></div>
	{{ _lang('Withdraw Money') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#loans" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-dollar-minus"></i></div>
	{{ _lang('Loans') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="loans" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('loans.apply_loan') }}">{{ _lang('Apply New Loan') }}</a>
		<a class="nav-link" href="{{ route('loans.my_loans') }}">{{ _lang('My Loans') }}</a>
		<a class="nav-link" href="{{ route('loans.calculator') }}">{{ _lang('Loan Calculator') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#fdr" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-money"></i></div>
	{{ _lang('Fixed Deposit') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="fdr" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('fixed_deposits.apply') }}">{{ _lang('Apply New FRD') }}</a>
		<a class="nav-link" href="{{ route('fixed_deposits.history') }}">{{ _lang('FDR History') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tickets" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-live-support"></i></div>
	{{ _lang('Support Tickets') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="tickets" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('tickets.create_ticket') }}">{{ _lang('Create New Ticket') }}</a>
		<a class="nav-link" href="{{ route('tickets.my_tickets',['status' => 'pending']) }}">{{ _lang('Pending Tickets') }}</a>
		<a class="nav-link" href="{{ route('tickets.my_tickets',['status' => 'active']) }}">{{ _lang('Active Tickets') }}</a>
		<a class="nav-link" href="{{ route('tickets.my_tickets',['status' => 'closed']) }}">{{ _lang('Closed Tickets') }}</a>
	</nav>
</div>

<a class="nav-link" href="{{ route('reports.transactions_report') }}">
	<div class="sb-nav-link-icon"><i class="icofont-chart-histogram"></i></div>
	{{ _lang('Transactions Report') }}
</a>
