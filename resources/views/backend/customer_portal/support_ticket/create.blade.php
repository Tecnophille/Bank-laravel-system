@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card no-export">
		    <div class="card-header">
				<h4 class="header-title text-center">{{ _lang('Create New Ticket') }}</h4>
			</div>
			<div class="card-body">
                <form method="post" class="validate" autocomplete="off" action="{{ route('tickets.create_ticket') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row px-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Subject') }}</label>
                                <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Message') }}</label>
                                <textarea class="form-control" name="message" rows="8" required>{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Attachment') }}</label>
                                <input type="file" class="trickycode-file" name="attachment">
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
@endsection