<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('gift_cards.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="row px-2">
	    <div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Code') }}</label>
				<input type="text" class="form-control" name="code" value="{{ generate_gift_card() }}" readonly>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Currency') }}</label>
				<select class="form-control auto-select select2" data-selected="{{ old('currency_id') }}" name="currency_id"  required>
					<option value="">{{ _lang('Select One') }}</option>
					{{ create_option('currency','id','name','',array('status=' => 1)) }}
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Amount') }}</label>
				<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
			</div>
		</div>


		<div class="col-md-12">
		    <div class="form-group">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
		    </div>
		</div>
	</div>
</form>
