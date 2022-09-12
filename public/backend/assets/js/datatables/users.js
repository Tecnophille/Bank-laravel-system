(function ($) {
  "use strict";

  var user_table = $("#users_table").DataTable({
    processing: true,
    serverSide: true,
    ajax:
      _url + "/admin/users/get_table_data/" + $("#users_table").data("status"),
    columns: [
      { data: "profile_picture", name: "profile_picture" },
      { data: "name", name: "name" },
      { data: "email", name: "email" },
      { data: "phone", name: "phone" },
      { data: "branch.name", name: "branch.name" },
      { data: "status", name: "status" },
      { data: "email_verified_at", name: "email_verified_at" },
      { data: "sms_verified_at", name: "sms_verified_at" },
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
    user_table.draw();
  });
})(jQuery);
