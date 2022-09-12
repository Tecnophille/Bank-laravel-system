(function ($) {
  "use strict";

  /*================================
	Preloader
	==================================*/
  var preloader = $("#preloader");
  $(window).on("load", function () {
    setTimeout(function () {
      preloader.fadeOut("slow", function () {
        $(this).remove();
      });
    }, 300);
  });

  /*================================
	sidebar collapsing
	==================================*/
  $("#sidebarToggle").on("click", function (e) {
    e.preventDefault();
    $("body").toggleClass("sb-sidenav-toggled");
  });

  $(document).on("click", ".close-mobile-nav", function (e) {
    $("body").removeClass("sb-sidenav-toggled");
  });

  /*================================
	Active Sidebar Menu
	==================================*/
  var path = window.location.href;
  $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function () {
    if ($(this).attr("href") === path) {
      $(this).addClass("active");
      $(this).parent().parent().addClass("show");
    }
  });

  /*================================
	Init Tooltip 
	==================================*/

  $('[data-toggle="tooltip"]').tooltip({ html: true });

  /*================================
	Hide Empty Menu 
	==================================*/
  $(".sb-sidenav-menu .collapse").each(function () {
    var elem = $(this);
    if ($(elem).has("nav").length > 0) {
      if ($(elem).find("nav").has("a").length === 0) {
        $(elem).prev().remove();
        $(elem).remove();
      }
    }
  });

  /*================================
	form bootstrap validation
	==================================*/
  $('[data-toggle="popover"]').popover();

  /*================================
	Fullscreen Page
	==================================*/
  if ($("#full-view").length) {
    var requestFullscreen = function (ele) {
      if (ele.requestFullscreen) {
        ele.requestFullscreen();
      } else if (ele.webkitRequestFullscreen) {
        ele.webkitRequestFullscreen();
      } else if (ele.mozRequestFullScreen) {
        ele.mozRequestFullScreen();
      } else if (ele.msRequestFullscreen) {
        ele.msRequestFullscreen();
      } else {
        console.log("Fullscreen API is not supported.");
      }
    };

    var exitFullscreen = function () {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      } else {
        console.log("Fullscreen API is not supported.");
      }
    };

    var fsDocButton = document.getElementById("full-view");
    var fsExitDocButton = document.getElementById("full-view-exit");

    fsDocButton.addEventListener("click", function (e) {
      e.preventDefault();
      requestFullscreen(document.documentElement);
      $("body").addClass("expanded");
    });

    fsExitDocButton.addEventListener("click", function (e) {
      e.preventDefault();
      exitFullscreen();
      $("body").removeClass("expanded");
    });
  }

  //App Js
  $(document).ajaxStart(function () {
    Pace.restart();
  });

  $(document).on("click", ".btn-remove-2", function () {
    var link = $(this).attr("href");
    //Sweet Alert for delete action
    Swal.fire({
      title: $lang_alert_title,
      text: $lang_alert_message,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: $lang_confirm_button_text,
      cancelButtonText: $lang_cancel_button_text,
    }).then((result) => {
      if (result.value) {
        window.location.href = link;
      }
    });

    return false;
  });

  $(document).on("click", ".btn-remove", function () {
    //Sweet Alert for delete action
    Swal.fire({
      title: $lang_alert_title,
      text: $lang_alert_message,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: $lang_confirm_button_text,
      cancelButtonText: $lang_cancel_button_text,
    }).then((result) => {
      if (result.value) {
        $(this).closest("form").submit();
      }
    });

    return false;
  });

  $(".select2").select2();

  /** Init Datepicker **/
  init_datepicker();

  $(".dropify").dropify();

  //Form validation
  if ($(".validate").length) {
    $(".validate").parsley();
  }

  init_editor();

  $(".float-field").on("keypress", function (event) {
    if (
      (event.which != 46 || $(this).val().indexOf(".") != -1) &&
      (event.which < 48 || event.which > 57)
    ) {
      event.preventDefault();
    }
  });

  $(".int-field").on("keypress", function (event) {
    if (event.which < 48 || event.which > 57) {
      event.preventDefault();
    }
  });

  $(document).on("click", "#modal-fullscreen", function () {
    $("#main_modal >.modal-dialog").toggleClass("fullscreen-modal");
  });

  $(document).on("click", "#close_alert", function () {
    $("#main_alert").fadeOut();
  });

  //TrickyCode File Upload Field
  $(".trickycode-file").after(
    "<input type='text' class='form-control filename' placeholder='Choose Flie' readOnly>" +
      "<button type='button' class='btn btn-primary trickycode-upload-btn'>Browse</button>"
  );

  $(".trickycode-file").each(function () {
    if ($(this).data("value")) {
      $(this).parent().find(".filename").val($(this).data("value"));
    }
    if ($(this).attr("required")) {
      $(this).parent().find(".filename").prop("required", true);
    }
  });

  $(document).on("click", ".trickycode-upload-btn", function () {
    $(this).parent().find("input[type=file]").click();
  });

  $(document).on("change", ".trickycode-file", function () {
    readFileURL(this);
  });

  if (
    $("input:required, select:required, textarea:required")
      .closest(".form-group")
      .find(".required").length == 0
  ) {
    $("input:required, select:required, textarea:required")
      .closest(".form-group")
      .find("label")
      .append("<span class='required'> *</span>");
  }

  //Print Command
  $(document).on("click", ".print", function (event) {
    event.preventDefault();
    $("#preloader").css("display", "block");
    var div = "#" + $(this).data("print");
    $(div).print({
      timeout: 1000,
    });
  });

  //Ajax Select2
  if ($(".select2-ajax").length) {
    $(".select2-ajax").each(function (i, obj) {
      var display2 = "";
      if (typeof $(this).data("display2") !== "undefined") {
        display2 = "&display2=" + $(this).data("display2");
      }

      $(this)
        .select2({
          placeholder: $lang_select_one,
          allowClear: true,
          ajax: {
            url:
              _url +
              "/ajax/get_table_data?table=" +
              $(this).data("table") +
              "&value=" +
              $(this).data("value") +
              "&display=" +
              $(this).data("display") +
              display2 +
              "&where=" +
              $(this).data("where"),
            processResults: function (data) {
              return {
                results: data,
              };
            },
          },
        })
        .on("select2:open", () => {
          target_select = $(this);
          $(".select2-results:not(:has(a))").append(
            '<p class="border-top m-0 p-2"><a class="ajax-modal-2" href="' +
              $(this).data("href") +
              '" data-title="' +
              $(this).data("title") +
              '" data-reload="false"><i class="icofont-plus-circle"></i> ' +
              $lang_add_new +
              "</a></p>"
          );
        });
    });
  }

  //Ajax Modal Function
  var target_select;
  $(document).on("click", ".ajax-modal", function () {
    var link = $(this).data("href");
    if (typeof link == "undefined") {
      link = $(this).attr("href");
    }

    var title = $(this).data("title");
    var fullscreen = $(this).data("fullscreen");
    var reload = $(this).data("reload");

    $.ajax({
      url: link,
      beforeSend: function () {
        $("#preloader").css("display", "block");
      },
      success: function (data) {
        $("#preloader").css("display", "none");
        $("#main_modal .modal-title").html(title);
        $("#main_modal .modal-body").html(data);
        $("#main_modal .alert-primary").addClass("d-none");
        $("#main_modal .alert-danger").addClass("d-none");
        $("#main_modal").modal("show");

        if (fullscreen == true) {
          $("#main_modal >.modal-dialog").addClass("fullscreen-modal");
        } else {
          $("#main_modal >.modal-dialog").removeClass("fullscreen-modal");
        }

        if (reload == false) {
          $("#main_modal .ajax-submit, #main_modal .ajax-screen-submit").attr(
            "data-reload",
            false
          );
        }

        //init Essention jQuery Library
        if ($(".ajax-submit").length) {
          $(".ajax-submit").parsley();
        }

        if ($(".ajax-screen-submit").length) {
          $(".ajax-screen-submit").parsley();
        }

        init_editor();

        /** Init Datepicker **/
        init_datepicker();

        /** Init DateTimepicker **/
        $(".datetimepicker").daterangepicker({
          timePicker: true,
          timePicker24Hour: true,
          singleDatePicker: true,
          showDropdowns: true,
          locale: {
            format: "YYYY-MM-DD HH:mm",
          },
        });

        $(".float-field").keypress(function (event) {
          if (
            (event.which != 46 || $(this).val().indexOf(".") != -1) &&
            (event.which < 48 || event.which > 57)
          ) {
            event.preventDefault();
          }
        });

        $(".int-field").keypress(function (event) {
          if (event.which < 48 || event.which > 57) {
            event.preventDefault();
          }
        });

        $('[data-toggle="tooltip"]').tooltip({ html: true });

        //Select2
        $("#main_modal select.select2").select2({
          dropdownParent: $("#main_modal .modal-content"),
        });

        //Ajax Select2
        if ($("#main_modal .select2-ajax").length) {
          $("#main_modal .select2-ajax").each(function (i, obj) {
            var display2 = "";
            if (typeof $(this).data("display2") !== "undefined") {
              display2 = "&display2=" + $(this).data("display2");
            }

            $(this)
              .select2({
                placeholder: $lang_select_one,
                allowClear: true,
                ajax: {
                  url:
                    _url +
                    "/ajax/get_table_data?table=" +
                    $(this).data("table") +
                    "&value=" +
                    $(this).data("value") +
                    "&display=" +
                    $(this).data("display") +
                    display2 +
                    "&where=" +
                    $(this).data("where"),
                  processResults: function (data) {
                    return {
                      results: data,
                    };
                  },
                },
                dropdownParent: $("#main_modal .modal-content"),
              })
              .on("select2:open", () => {
                target_select = $(this);
                $(".select2-results:not(:has(a))").append(
                  '<p class="border-top m-0 p-2"><a class="ajax-modal-2" href="' +
                    $(this).data("href") +
                    '" data-title="' +
                    $(this).data("title") +
                    '" data-reload="false"><i class="icofont-plus-circle"></i> ' +
                    $lang_add_new +
                    "</a></p>"
                );
              });
          });
        }

        //Auto Selected
        if ($(".auto-select").length) {
          $(".auto-select").each(function (i, obj) {
            $(this).val($(this).data("selected")).trigger("change");
          });
        }

        $(".dropify").dropify();
        $(
          "#main_modal .ajax-submit input:required, #main_modal .ajax-submit select:required, #main_modal .ajax-submit textarea:required"
        )
          .closest(".form-group")
          .find(".control-label")
          .append("<span class='required'> *</span>");
        $(
          "#main_modal .ajax-screen-submit input:required, #main_modal .ajax-screen-submit select:required, #main_modal .ajax-screen-submit textarea:required"
        )
          .closest(".form-group")
          .find(".control-label")
          .append("<span class='required'> *</span>");
      },
      error: function (request, status, error) {
        console.log(request.responseText);
      },
    });

    return false;
  });

  $("#main_modal").on("show.bs.modal", function () {
    $("#main_modal").css("overflow-y", "hidden");
  });

  $("#main_modal").on("shown.bs.modal", function () {
    $("#main_modal").css("overflow-y", "auto");
  });

  //Ajax Secondary Modal Function
  $(document).on("click", ".ajax-modal-2", function () {
    var link = $(this).attr("href");

    var title = $(this).data("title");
    var fullscreen = $(this).data("fullscreen");
    var reload = $(this).data("reload");

    $.ajax({
      url: link,
      beforeSend: function () {
        $("#preloader").css("display", "block");
      },
      success: function (data) {
        $("#preloader").css("display", "none");
        $("#secondary_modal .modal-title").html(title);
        $("#secondary_modal .modal-body").html(data);
        $("#secondary_modal .alert-primary").addClass("d-none");
        $("#secondary_modal .alert-danger").addClass("d-none");
        $("#secondary_modal").modal("show");

        if (fullscreen == true) {
          $("#secondary_modal >.modal-dialog").addClass("fullscreen-modal");
        } else {
          $("#secondary_modal >.modal-dialog").removeClass("fullscreen-modal");
        }

        if (reload == false) {
          target_select.select2("close");
          $(
            "#secondary_modal .ajax-submit, #secondary_modal .ajax-screen-submit"
          ).attr("data-reload", false);
        }

        //init Essention jQuery Library
        $("#secondary_modal select.select2").select2({
          dropdownParent: $("#secondary_modal .modal-content"),
        });

        //$('.year').mask('0000-0000');
        if ($(".ajax-submit").length) {
          $(".ajax-submit").parsley();
        }

        if ($(".ajax-screen-submit").length) {
          $(".ajax-screen-submit").parsley();
        }

        /** Init Datepicker **/
        init_datepicker();

        $(".float-field").on("keypress", function (event) {
          if (
            (event.which != 46 || $(this).val().indexOf(".") != -1) &&
            (event.which < 48 || event.which > 57)
          ) {
            event.preventDefault();
          }
        });

        $(".int-field").on("keypress", function (event) {
          if (event.which < 48 || event.which > 57) {
            event.preventDefault();
          }
        });

        //Ajax Select2
        if ($("#secondary_modal .select2-ajax").length) {
          $("#secondary_modal .select2-ajax").each(function (i, obj) {
            var display2 = "";
            if (typeof $(this).data("display2") !== "undefined") {
              display2 = "&display2=" + $(this).data("display2");
            }

            $(this)
              .select2({
                placeholder: $lang_select_one,
                allowClear: true,
                ajax: {
                  url:
                    _url +
                    "/ajax/get_table_data?table=" +
                    $(this).data("table") +
                    "&value=" +
                    $(this).data("value") +
                    "&display=" +
                    $(this).data("display") +
                    display2 +
                    "&where=" +
                    $(this).data("where"),
                  processResults: function (data) {
                    return {
                      results: data,
                    };
                  },
                },
              })
              .on("select2:open", () => {
                target_select = $(this);
                $(".select2-results:not(:has(a))").append(
                  '<p class="border-top m-0 p-2"><a class="ajax-modal-2" href="' +
                    $(this).data("href") +
                    '" data-title="' +
                    $(this).data("title") +
                    '" data-reload="false"><i class="icofont-plus-circle"></i> ' +
                    $lang_add_new +
                    "</a></p>"
                );
              });
          });
        }

        $(".dropify").dropify();
        $(
          "#secondary_modal input:required, #secondary_modal select:required, #secondary_modal textarea:required"
        )
          .closest(".form-group")
          .find(".control-label")
          .append("<span class='required'> *</span>");
      },
      error: function (request, status, error) {
        console.log(request.responseText);
      },
    });

    return false;
  });

  $("#secondary_modal").on("show.bs.modal", function () {
    $("#secondary_modal").css("overflow-y", "hidden");
  });

  $("#secondary_modal").on("shown.bs.modal", function () {
    $("#secondary_modal").css("overflow-y", "auto");
  });

  //Ajax Modal Submit
  $(document).on("submit", ".ajax-submit", function () {
    var link = $(this).attr("action");
    var reload = $(this).data("reload");
    var current_modal = $(this).closest(".modal");

    var elem = $(this);
    $(elem).find("button[type=submit]").prop("disabled", true);

    $.ajax({
      method: "POST",
      url: link,
      data: new FormData(this),
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $("#preloader").css("display", "block");
      },
      success: function (data) {
        $(elem).find("button[type=submit]").attr("disabled", false);
        $("#preloader").css("display", "none");
        var json = JSON.parse(data);
        if (json["result"] == "success") {
          if (reload != false) {
            //Main Modal
            if (json["action"] == "store") {
              $("#main_modal .ajax-submit")[0].reset();
            }
            $("#main_modal .alert-primary").html(json["message"]);
            $("#main_modal .alert-primary").removeClass("d-none");
            $("#main_modal .alert-danger").addClass("d-none");

            window.setTimeout(function () {
              window.location.reload();
            }, 500);
          } else {
            //Secondary Modal
            if (json["action"] == "store") {
              $(current_modal).find(".ajax-submit")[0].reset();
            }

            $(current_modal).find(".alert-primary").html(json["message"]);
            $(current_modal).find(".alert-primary").removeClass("d-none");
            $(current_modal).find(".alert-danger").addClass("d-none");

            var select_value = json["data"][target_select.data("value")];
            var select_display = json["data"][target_select.data("display")];

            var newOption = new Option(
              select_display,
              select_value,
              true,
              true
            );
            target_select.append(newOption).trigger("change");
            $(current_modal).modal("hide");
          }
        } else {
          if (Array.isArray(json["message"])) {
            if (reload != false) {
              //Main Modal
              jQuery.each(json["message"], function (i, val) {
                $("#main_modal .alert-danger").append("<p>" + val + "</p>");
              });
              $("#main_modal .alert-primary").addClass("d-none");
              $("#main_modal .alert-danger").removeClass("d-none");
            } else {
              //Secondary Modal
              jQuery.each(json["message"], function (i, val) {
                $(current_modal)
                  .find(".alert-danger")
                  .append("<p>" + val + "</p>");
              });
              $(current_modal).find(".alert-primary").addClass("d-none");
              $(current_modal).find(".alert-danger").removeClass("d-none");
            }
          } else {
            if (reload != false) {
              $("#main_modal .alert-danger").html(
                "<p>" + json["message"] + "</p>"
              );
              $("#main_modal .alert-primary").addClass("d-none");
              $("#main_modal .alert-danger").removeClass("d-none");
            } else {
              $(current_modal)
                .find(".alert-danger")
                .html("<p>" + json["message"] + "</p>");
              $(current_modal).find(".alert-primary").addClass("d-none");
              $(current_modal).find(".alert-danger").removeClass("d-none");
            }
          }
        }
      },
      error: function (request, status, error) {
        console.log(request.responseText);
      },
    });

    return false;
  });

  //Ajax Modal Submit without loading
  $(document).on("submit", ".ajax-screen-submit", function () {
    var link = $(this).attr("action");
    var reload = $(this).data("reload");
    var current_modal = $(this).closest(".modal");

    var elem = $(this);
    $(elem).find("button[type=submit]").prop("disabled", true);

    $.ajax({
      method: "POST",
      url: link,
      data: new FormData(this),
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $("#preloader").css("display", "block");
      },
      success: function (data) {
        $(elem).find("button[type=submit]").attr("disabled", false);
        $("#preloader").css("display", "none");
        var json = JSON.parse(data);
        if (json["result"] == "success") {
          $(document).trigger("ajax-screen-submit");

          $.toast({
            text: json["message"],
            showHideTransition: "slide",
            icon: "success",
            position: "top-right",
          });

          var table = json["table"];

          if (json["action"] == "update") {
            $(table + ' tr[data-id="row_' + json["data"]["id"] + '"]')
              .find("td")
              .each(function () {
                if (typeof $(this).attr("class") != "undefined") {
                  $(this).html(
                    json["data"][$(this).attr("class").split(" ")[0]]
                  );
                }
              });
          } else if (json["action"] == "store") {
            $(elem)[0].reset();
            var new_row = $(table).find("tbody").find("tr:eq(0)").clone();

            $(new_row).attr("data-id", "row_" + json["data"]["id"]);

            $(new_row)
              .find("td")
              .each(function () {
                if ($(this).attr("class") == "dataTables_empty") {
                  window.location.reload();
                }
                if (typeof $(this).attr("class") != "undefined") {
                  $(this).html(
                    json["data"][$(this).attr("class").split(" ")[0]]
                  );
                }
              });

            $(new_row)
              .find("form")
              .attr("action", link + "/" + json["data"]["id"]);
            $(new_row)
              .find(".dropdown-edit")
              .attr("data-href", link + "/" + json["data"]["id"] + "/edit");
            $(new_row)
              .find(".dropdown-view")
              .attr("data-href", link + "/" + json["data"]["id"]);

            $(table).prepend(new_row);

            if (reload == false) {
              var select_value = json["data"][target_select.data("value")];
              var select_display = json["data"][target_select.data("display")];

              var newOption = new Option(
                select_display,
                select_value,
                true,
                true
              );
              target_select.append(newOption).trigger("change");
              $(current_modal).modal("hide");
            }
          }

          $(current_modal).find(".alert-primary").addClass("d-none");
          $(current_modal).find(".alert-danger").addClass("d-none");
        } else if (json["result"] == "error") {
          $(current_modal).find(".alert-danger").html("");
          if (Array.isArray(json["message"])) {
            jQuery.each(json["message"], function (i, val) {
              $(current_modal)
                .find(".alert-danger")
                .append("<p>" + val + "</p>");
            });
            $(current_modal).find(".alert-primary").addClass("d-none");
            $(current_modal).find(".alert-danger").removeClass("d-none");
          } else {
            $(current_modal)
              .find(".alert-danger")
              .html("<p>" + json["message"] + "</p>");
            $(current_modal).find(".alert-primary").addClass("d-none");
            $(current_modal).find(".alert-danger").removeClass("d-none");
          }
        } else {
          $.toast({
            text: data.replace(/(<([^>]+)>)/gi, ""),
            showHideTransition: "slide",
            icon: "error",
            position: "top-right",
          });
        }
      },
      error: function (request, status, error) {
        console.log(request.responseText);
      },
    });

    return false;
  });

  //Ajax Remove without loading
  $(document).on("click", ".ajax-get-remove", function () {
    var current_modal = $(this).closest(".modal");

    Swal.fire({
      title: $lang_alert_title,
      text: $lang_alert_message,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: $lang_confirm_button_text,
      cancelButtonText: $lang_cancel_button_text,
    }).then((result) => {
      if (result.value) {
        var link = $(this).attr("href");
        $.ajax({
          method: "GET",
          url: link,
          beforeSend: function () {
            $("#preloader").css("display", "block");
          },
          success: function (data) {
            $("#preloader").css("display", "none");

            var json = JSON.parse(JSON.stringify(data));
            console.log(json["result"]);
            if (json["result"] == "success") {
              $.toast({
                text: json["message"],
                showHideTransition: "slide",
                icon: "success",
                position: "top-right",
              });

              var table = json["table"];
              //$(table).find('#row_' + json['id']).remove();
              $(table + ' tr[data-id="row_' + json["id"] + '"]').remove();
            } else if (json["result"] == "error") {
              if (Array.isArray(json["message"])) {
                jQuery.each(json["message"], function (i, val) {
                  $.toast({
                    text: val,
                    showHideTransition: "slide",
                    icon: "error",
                    position: "top-right",
                  });
                });
              } else {
                $.toast({
                  text: json["message"],
                  showHideTransition: "slide",
                  icon: "error",
                  position: "top-right",
                });
              }
            } else {
              $.toast({
                text: data.replace(/(<([^>]+)>)/gi, ""),
                showHideTransition: "slide",
                icon: "error",
                position: "top-right",
              });
            }
          },
          error: function (request, status, error) {
            console.log(request.responseText);
          },
        });
      }
    });

    return false;
  });

  //Ajax Remove without loading
  $(document).on("submit", ".ajax-remove", function (event) {
    event.preventDefault();

    var current_modal = $(this).closest(".modal");

    Swal.fire({
      title: $lang_alert_title,
      text: $lang_alert_message,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: $lang_confirm_button_text,
      cancelButtonText: $lang_cancel_button_text,
    }).then((result) => {
      if (result.value) {
        var link = $(this).attr("action");
        $.ajax({
          method: "POST",
          url: link,
          data: $(this).serialize(),
          beforeSend: function () {
            $("#preloader").css("display", "block");
          },
          success: function (data) {
            $("#preloader").css("display", "none");
            var json = JSON.parse(JSON.stringify(data));
            if (json["result"] == "success") {
              $.toast({
                text: json["message"],
                showHideTransition: "slide",
                icon: "success",
                position: "top-right",
              });

              var table = json["table"];
              //$(table).find('#row_' + json['id']).remove();
              $(table + ' tr[data-id="row_' + json["id"] + '"]').remove();
            } else if (json["result"] == "error") {
              if (Array.isArray(json["message"])) {
                jQuery.each(json["message"], function (i, val) {
                  $.toast({
                    text: val,
                    showHideTransition: "slide",
                    icon: "error",
                    position: "top-right",
                  });
                });
              } else {
                $.toast({
                  text: json["message"],
                  showHideTransition: "slide",
                  icon: "error",
                  position: "top-right",
                });
              }
            } else {
              $.toast({
                text: data.replace(/(<([^>]+)>)/gi, ""),
                showHideTransition: "slide",
                icon: "error",
                position: "top-right",
              });
            }
          },
          error: function (request, status, error) {
            console.log(request.responseText);
          },
        });
      }
    });
  });

  //Ajax submit without validate
  $(document).on("submit", ".settings-submit", function () {
    var elem = $(this);
    var button_val = "";
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
        button_val = $(elem).find("button[type=submit]").text();
        $(elem)
          .find("button[type=submit]")
          .html(
            '<div class="spinner-border text-primary spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>'
          );
      },
      success: function (data) {
        $("#preloader").fadeOut();
        $(elem).find("button[type=submit]").html(button_val);
        $(elem).find("button[type=submit]").attr("disabled", false);
        var json = JSON.parse(data);

        if (json["result"] == "success") {
          $("#main_alert > span.msg").html(json["message"]);
          $("#main_alert")
            .addClass("alert-success")
            .removeClass("alert-danger");
          $("#main_alert").css("display", "block");
        } else {
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

  //Auto Selected
  if ($(".auto-select").length) {
    $(".auto-select").each(function (i, obj) {
      $(this).val($(this).data("selected")).trigger("change");
    });
  }

  if ($(".auto-multiple-select").length) {
    $(".auto-multiple-select").each(function (i, obj) {
      var values = $(this).data("selected");
      $(this).val(values).trigger("change");
    });
  }

  if ($(".data-table").length) {
    $(".data-table").each(function (i, obj) {
      var table = $(this).DataTable({
        responsive: true,
        bAutoWidth: false,
        ordering: false,
        //"lengthChange": false,
        language: {
          decimal: "",
          emptyTable: $lang_no_data_found,
          info:
            $lang_showing +
            " _START_ " +
            $lang_to +
            " _END_ " +
            $lang_of +
            " _TOTAL_ " +
            $lang_entries,
          infoEmpty: $lang_showing_0_to_0_of_0_entries,
          infoFiltered: "(filtered from _MAX_ total entries)",
          infoPostFix: "",
          thousands: ",",
          lengthMenu: $lang_show + " _MENU_ " + $lang_entries,
          loadingRecords: $lang_loading,
          processing: $lang_processing,
          search: $lang_search,
          zeroRecords: $lang_no_matching_records_found,
          paginate: {
            first: $lang_first,
            last: $lang_last,
            previous: "<i class='icofont-rounded-left'></i>",
            next: "<i class='icofont-rounded-right'></i>",
          },
          aria: {
            sortAscending: ": activate to sort column ascending",
            sortDescending: ": activate to sort column descending",
          },
        },
        drawCallback: function () {
          $(".dataTables_paginate > .pagination").addClass(
            "pagination-bordered"
          );
        },
      });
    });
  }

  var report_table = $(".report-table").DataTable({
    responsive: true,
    bAutoWidth: false,
    ordering: false,
    lengthChange: false,
    language: {
      decimal: "",
      emptyTable: $lang_no_data_found,
      info:
        $lang_showing +
        " _START_ " +
        $lang_to +
        " _END_ " +
        $lang_of +
        " _TOTAL_ " +
        $lang_entries,
      infoEmpty: $lang_showing_0_to_0_of_0_entries,
      infoFiltered: "(filtered from _MAX_ total entries)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: $lang_show + " _MENU_ " + $lang_entries,
      loadingRecords: $lang_loading,
      processing: $lang_processing,
      search: $lang_search,
      zeroRecords: $lang_no_matching_records_found,
      paginate: {
        first: $lang_first,
        last: $lang_last,
        previous: "<i class='icofont-rounded-left'></i>",
        next: "<i class='icofont-rounded-right'></i>",
      },
      aria: {
        sortAscending: ": activate to sort column ascending",
        sortDescending: ": activate to sort column descending",
      },
      buttons: {
        copy: $lang_copy,
        excel: $lang_excel,
        pdf: $lang_pdf,
        print: $lang_print,
      },
    },
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-bordered");
    },
    buttons: [
      "copy",
      "excel",
      "pdf",
      {
        extend: "print",
        title: "",
        customize: function (win) {
          $(win.document.body)
            .css("font-size", "10pt")
            .prepend(
              '<div class="text-center">' +
                $(".report-header").html() +
                "</div>"
            );

          $(win.document.body)
            .find("table")
            .addClass("compact")
            .css("font-size", "inherit");
        },
      },
    ],
  });

  report_table
    .buttons()
    .container()
    .appendTo("#DataTables_Table_0_wrapper .col-md-6:eq(0)");

  //General Settings Page
  if ($("#mail_type").val() == "mail") {
    $(".smtp").prop("disabled", true);
  }

  $(document).on("change", "#mail_type", function () {
    if ($(this).val() == "mail") {
      $(".smtp").prop("disabled", true);
    } else {
      $(".smtp").prop("disabled", false);
    }
  });

  //Access Control
  $(document).on("change", "#permissions #role_id", function () {
    showRole($(this));
  });

  $("#permissions .custom-control-input").each(function () {
    if ($(this).prop("checked") == true) {
      $(this).closest(".collapse").addClass("show");
    }
  });

  //Contacts Create/Edit
  if ($("#client_login").is(":checked") == false) {
    $("#client_login_card input, #client_login_card select").prop(
      "disabled",
      true
    );
  }

  $(document).on("change", "#client_login", function () {
    if ($(this).is(":checked") == false) {
      $("#client_login_card input, #client_login_card select").prop(
        "disabled",
        true
      );
    } else {
      $("#client_login_card input, #client_login_card select").prop(
        "disabled",
        false
      );
    }
  });

  $(document).on("change", "#select_company_email_template", function () {
    if ($(this).val() != "") {
      $.ajax({
        url: _url + "/company_email_template/get_template/" + $(this).val(),
        beforeSend: function () {
          $("#preloader").css("display", "block");
        },
        success: function (data) {
          $("#preloader").css("display", "none");
          var json = JSON.parse(data);
          $("#email_subject").val(json["subject"]);
          tinymce.activeEditor.setContent(json["body"]);
        },
      });
    }
  });

  //Company Email Template
  $(document).on("change", "#email_template_related_to", function () {
    if ($(this).val() == "invoice") {
      $("#invoice-paremeter").removeClass("d-none");
      $("#quotation-paremeter").addClass("d-none");
    } else if ($(this).val() == "quotation") {
      $("#quotation-paremeter").removeClass("d-none");
      $("#invoice-paremeter").addClass("d-none");
    }
  });

  $(document).on("change", "#user_type", function () {
    $(this).val() == "user"
      ? $("#role_id").prop("disabled", false)
      : $("#role_id").prop("disabled", true);
  });

  if ($("#gateway_currency").val() != "") {
    $("#gateway_currency_preview").html($("#gateway_currency").val());
  }

  $(document).on("change", "#gateway_status", function () {
    $(this).val() == 0
      ? $("#exchange_rate").prop("required", false)
      : $("#exchange_rate").prop("required", true);
  });

  $(document).on("change", "#gateway_currency", function () {
    if ($(this).val() != "") {
      $("#gateway_currency_preview").html($(this).val());
    }
  });

  $(document).on("click", ".notification_mark_as_read", function (event) {
    event.preventDefault();
    var notification = $(this);
    $.ajax({
      url: $(notification).attr("href"),
      beforeSend: function () {
        $("#preloader").css("display", "block");
      },
      success: function (data) {
        $(notification).prev().find("p").removeClass("unread-notification");
        $(notification).remove();
        $("#notification-count").html(
          parseInt($("#notification-count").html()) - 1
        );
        $("#preloader").css("display", "none");
      },
    });
  });
})(jQuery);

function readFileURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {};

    $(input).parent().find(".filename").val(input.files[0].name);
    reader.readAsDataURL(input.files[0]);
  }
}

function init_editor() {
  if ($(".summernote").length > 0) {
    tinymce.init({
      selector: "textarea.summernote",
      theme: "modern",
      height: 250,
      plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor",
      ],
      toolbar:
        "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
      style_formats: [
        { title: "Bold text", inline: "b" },
        { title: "Red text", inline: "span", styles: { color: "#ff0000" } },
        { title: "Red header", block: "h1", styles: { color: "#ff0000" } },
        { title: "Example 1", inline: "span", classes: "example1" },
        { title: "Example 2", inline: "span", classes: "example2" },
        { title: "Table styles" },
        { title: "Table row 1", selector: "tr", classes: "tablerow1" },
      ],
    });
  }
}

function init_datepicker() {
  /** Start Datepicker **/
  var date_format = [
    "Y-m-d",
    "d-m-Y",
    "d/m/Y",
    "m-d-Y",
    "m.d.Y",
    "m/d/Y",
    "d.m.Y",
    "d/M/Y",
    "M/d/Y",
    "d M, Y",
  ];
  var picker_date_format = [
    "YYYY-MM-DD",
    "DD-MM-YYYY",
    "DD/MM/YYYY",
    "MM-DD-YYYY",
    "MM.DD.YYYY",
    "MM/DD/YYYY",
    "DD.MM.YYYY",
    "DD/MMM/YYYY",
    "MMM/DD/YYYY",
    "DD MMM, YYYY",
  ];

  var fake_format = picker_date_format[date_format.indexOf(_date_format)];

  //Set Default date
  if ($(".datepicker").length) {
    $(".datepicker").each(function (i, obj) {
      $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
          format: "YYYY-MM-DD",
        },
      });

      $(".datepicker").css("color", "transparent");

      if (typeof $(this).next().attr("class") === "undefined") {
        $(this).after('<span class="fake_datepicker"></span>');
        $(this).next(".fake_datepicker").css("margin-top", "-38.2px");
      }
      $(this)
        .next(".fake_datepicker")
        .html(moment($(this).val()).format(fake_format));
    });
  }

  $(".datepicker").on("apply.daterangepicker", function (ev, picker) {
    $(this)
      .next(".fake_datepicker")
      .html(moment($(this).val()).format(fake_format));
  });

  $(document).on("click", ".fake_datepicker", function () {
    $(this).prev().focus();
  });

  /** End Datepicker **/
}

function showRole(elem) {
  if ($(elem).val() == "") {
    return;
  }
  window.location = _url + "/admin/permission/control/" + $(elem).val();
}
