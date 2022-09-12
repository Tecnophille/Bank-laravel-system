@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-6 offset-lg-3">
		@if($api_error != '')
		<div class="alert alert-info">
			<p><i class="icofont-warning"></i> {{ $api_error }}</p>
		</div>
		@endif
		<div class="card">
			<div class="card-header text-center">
				{{ _lang('Mobile Verification') }}
			</div>

			<div class="card-body">
				<form action="{{ route('profile.mobile_verification') }}" autocomplete="off" id="mobile_verification" method="post">
					@csrf
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Verification Code') }}</label>
								<input type="text" class="form-control" name="verification_code" required>
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block"><i class="icofont-check-circled"></i> {{ _lang('Submit') }}</button>
							</div>
						</div>

						<input type="hidden" name="sms_token" value="{{ $sms_token }}">

						<div class="col-12 text-center">
							<a href="{{ route('profile.mobile_verification') }}" class="btn-link">{{ _lang('Resend Verification Code') }}</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js-script')
<script>
(function ($) {
  "use strict";

  $(document).on("submit", "#mobile_verification", function () {
    var elem = $(this);
    $(elem).find("button[type=submit]").prop("disabled", true);
    var link = $(this).attr("action");
    $.ajax({
      method: "POST",
      url: link,
      data: new FormData(this),
	  mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $("#preloader").fadeIn();
      },
      success: function (data) {
        $("#preloader").fadeOut();

		var json = JSON.parse(data);

        if (json["result"] == "success") {
          $("#main_alert > span.msg").html(json["message"]);
          $("#main_alert")
            .addClass("alert-success")
            .removeClass("alert-danger");
          $("#main_alert").css("display", "block");
		  setTimeout(function (){
			window.location.href = "{{ route('dashboard.index') }}";
		  }, 1000);
        } else {
		  $(elem).find("button[type=submit]").attr("disabled", false);
          if (Array.isArray(json["message"])) {
            $("#main_alert > span.msg").html("");
            $("#main_alert")
              .addClass("alert-danger")
              .removeClass("alert-success");

            jQuery.each(json["message"], function (i, val) {
              $("#main_alert > span.msg").append(
                '<i class="far fa-times-circle"></i> ' + val + "<br>"
              );
            });
            $("#main_alert").css("display", "block");
          } else {
            $("#main_alert > span.msg").html("");
            $("#main_alert")
              .addClass("alert-danger")
              .removeClass("alert-success");
            $("#main_alert > span.msg").html("<p>" + json["message"] + "</p>");
            $("#main_alert").css("display", "block");
          }
        }
      },
      error: function (request, status, error) {
        console.log(request.responseText);
      },
    });

    return false;
  });

})(jQuery);
</script>
@endsection

