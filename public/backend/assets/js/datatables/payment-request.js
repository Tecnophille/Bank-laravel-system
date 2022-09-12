(function ($) {
  "use strict";

  var user_table = $("#payment_requests_table").DataTable({
    processing: true,
    serverSide: true,
    ajax: _url + "/payment_requests/get_table_data",
    columns: [
      { data: "created_at", name: "created_at" },
      { data: "currency.name", name: "currency.name" },
      { data: "amount", name: "amount" },
      { data: "status", name: "status" },
      { data: "sender.name", name: "sender.name" },
      { data: "receiver.name", name: "receiver.name" },
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
})(jQuery);
