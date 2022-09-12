(function ($) {
  "use strict";

  var transfer_request_table = $("#transfer_request_table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: _url + "/admin/transfer_requests/get_table_data",
      method: "POST",
      data: function (d) {
        d._token = $('meta[name="csrf-token"]').attr("content");

        if ($("select[name=status]").val() != "") {
          d.status = $("select[name=status]").val();
        }
      },
      error: function (request, status, error) {
        console.log(request.responseText);
      },
    },
    columns: [
      { data: "user.name", name: "user.name" },
      { data: "currency.name", name: "currency.name" },
      { data: "amount", name: "amount" },
      { data: "other_bank.name", name: "other_bank.name" },
      { data: "status", name: "status" },
      { data: "action", name: "action" },
    ],
    responsive: true,
    bStateSave: true,
    bAutoWidth: false,
    ordering: false,
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
    },
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-bordered");
    },
  });

  $(".select-filter").on("change", function (e) {
    transfer_request_table.draw();
  });

  $(document).on("ajax-screen-submit", function () {
    transfer_request_table.draw();
  });
})(jQuery);
