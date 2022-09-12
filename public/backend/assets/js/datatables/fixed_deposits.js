(function ($) {
  "use strict";

  var fdr_table = $("#fdr_table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: _url + "/admin/fixed_deposits/get_table_data",
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
      { data: "id", name: "id" },
      { data: "plan.name", name: "plan.name" },
      { data: "user.name", name: "user.name" },
      { data: "currency.name", name: "currency.name" },
      { data: "deposit_amount", name: "deposit_amount" },
      { data: "return_amount", name: "return_amount" },
      { data: "status", name: "status" },
      { data: "mature_date", name: "mature_date" },
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
    fdr_table.draw();
  });

  $(document).on("ajax-screen-submit", function () {
    fdr_table.draw();
  });
})(jQuery);
