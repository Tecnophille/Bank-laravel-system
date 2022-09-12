(function ($) {
  "use strict";

  $(document).on("change", "#navigation_type", function () {
    $(this).val() == "page"
      ? $("#page").removeClass("d-none")
      : $("#page").addClass("d-none");
    $(this).val() == "category"
      ? $("#category").removeClass("d-none")
      : $("#category").addClass("d-none");
    $(this).val() == "dynamic_url" || $(this).val() == "custom_url"
      ? $("#url").removeClass("d-none")
      : $("#url").addClass("d-none");
  });

  if ($(".dd").length) {
    var updateOutput = function (e) {
      var list = e.length ? e : $(e.target),
        output = list.data("output");
      if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable("serialize")));
      } else {
        output.val("JSON browser support required for this demo.");
      }
    };

    // Activate Nestable
    $(".dd")
      .nestable({
        group: 1,
        maxDepth: 1,
      })
      .on("change", updateOutput);

    updateOutput($(".dd").data("output", $("#nestable-output")));
  }
})(jQuery);
