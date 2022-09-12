<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('GiftCardController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">
	<div class="row px-2">
		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Code') }}</label>
			<input type="text" class="form-control" name="code" value="{{ $giftcard->code }}" readonly>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Currency') }}</label>
				<select class="form-control auto-select select2" data-selected="{{ $giftcard->currency_id }}" name="currency_id"  required>
					<option value="">{{ _lang('Select One') }}</option>
					{{ create_option('currency','id','name','',array('status=' => 1)) }}
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
			<label class="control-label">{{ _lang('Amount') }}</label>
			<input type="text" class="form-control float-field" name="amount" value="{{ $giftcard->amount }}" required>
			</div>
		</div>

		<div class="col-md-12">
		    <div class="form-group">
			    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
		    </div>
		</div>
	</div>
</form>

