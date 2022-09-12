(function ($) {
  "use strict";

  var support_tickets_table = $("#support_tickets_table").DataTable({
    processing: true,
    serverSide: true,
    ajax:
      _url +
      "/admin/support_tickets/get_table_data/" +
      $("#support_tickets_table").data("status"),
    columns: [
      { data: "user.name", name: "user.name" },
      { data: "user.email", name: "user.email" },
      { data: "subject", name: "subject" },
      { data: "status", name: "status" },
      { data: "created_by.name", name: "created_by.name" },
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

  $(document).on("ajax-screen-submit", function () {
    support_tickets_table.draw();
  });
})(jQuery);
