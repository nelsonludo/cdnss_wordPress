(function ($) {
  "use strict";

  // Utility to collect current filters
  function collectFilters($container) {
    var authors = [];
    $container.find('input[name="wajsf_authors[]"]:checked').each(function () {
      authors.push($(this).val());
    });
    return {
      authors: authors,
      date_from: $("#wajsf_date_from").val(),
      date_to: $("#wajsf_date_to").val(),
      posts_per_page: $("#wajsf-results").data("posts-per-page") || 5,
      s: getUrlParameter("s") || "",
    };
  }

  // get query param helper
  function getUrlParameter(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
    var results = regex.exec(location.search);
    return results === null
      ? ""
      : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

  // show more toggle
  $(document).on("click", ".wajsf-show-more", function (e) {
    e.preventDefault();
    $(".wajsf-hidden-by-default").removeClass("wajsf-hidden-by-default");
    $(this).hide();
  });

  // author search filter (client-side)
  $(document).on("input", "#wajsf_author_search", function () {
    var q = $(this).val().toLowerCase();
    $(".wajsf-author-item").each(function () {
      var text = $(this).text().toLowerCase();
      if (text.indexOf(q) === -1) {
        $(this).hide();
      } else {
        $(this).show();
      }
    });
    // hide show-more button if necessary
  });

  // toggle boxes
  $(document).on("click", ".wajsf-toggle-btn", function () {
    var $btn = $(this);
    var $box = $btn.closest(".wajsf-filter-box");
    var $body = $box.find(".wajsf-filter-body");
    $body.toggle();
    $btn.text($body.is(":visible") ? "–" : "+");
  });

  // reset handlers scope-specific
  $(document).on("click", ".wajsf-reset-btn", function (e) {
    e.preventDefault();
    var scope = $(this).data("scope");
    if (scope === "author") {
      $('input[name="wajsf_authors[]"]').prop("checked", false);
      $("#wajsf_author_search").val("");
      $(".wajsf-author-item").show();
      $(".wajsf-show-more").show();
    } else if (scope === "date") {
      $("#wajsf_date_from").val("");
      $("#wajsf_date_to").val("");
    }
    // trigger apply after reset
    doApplyFilters();
  });

  // apply handlers (from any section) - will trigger ajax update
  $(document).on("click", ".wajsf-apply-btn", function (e) {
    e.preventDefault();
    doApplyFilters();
  });

  // pagination click
  $(document).on("click", ".wajsf-page-link", function (e) {
    e.preventDefault();
    var page = $(this).data("page") || 1;
    doApplyFilters(page);
  });

  // main function - makes AJAX call
  function doApplyFilters(page) {
    var $wrap = $("#wajsf-results");
    if ($wrap.length === 0) return;

    var filters = collectFilters($("body")); // inputs live in DOM
    filters.paged = page || 1;

    var data = {
      action: "wajsf_fetch_posts",
      nonce: wajsfData.nonce,
      posts_per_page: filters.posts_per_page,
      paged: filters.paged,
      authors: filters.authors,
      date_from: filters.date_from,
      date_to: filters.date_to,
      s: filters.s,
    };

    // optional: show loading UI
    $wrap.addClass("wajsf-loading");

    $.post(wajsfData.ajax_url, data, function (resp) {
      $wrap.removeClass("wajsf-loading");
      if (resp.success && resp.data.html) {
        $wrap.html(resp.data.html);
        // update the browser URL (pushState) with query params so back/forward works
        updateUrlForFilters(filters);
        // scroll to results on mobile maybe
        $("html,body").animate({ scrollTop: $wrap.offset().top - 20 }, 300);
      }
    });
  }

  // update URL with current filters for bookmarking/back button
  function updateUrlForFilters(filters) {
    var url = new URL(window.location.href);
    // remove old wajsf params
    url.searchParams.delete("wajsf_paged");
    url.searchParams.delete("wajsf_authors[]");
    url.searchParams.delete("wajsf_date_from");
    url.searchParams.delete("wajsf_date_to");

    if (filters.paged && parseInt(filters.paged) > 1) {
      url.searchParams.set("wajsf_paged", filters.paged);
    }
    if (filters.date_from)
      url.searchParams.set("wajsf_date_from", filters.date_from);
    if (filters.date_to) url.searchParams.set("wajsf_date_to", filters.date_to);
    if (filters.authors && filters.authors.length) {
      filters.authors.forEach(function (a) {
        url.searchParams.append("wajsf_authors[]", a);
      });
    }
    // keep standard search param if present
    var newUrl = url.toString();
    window.history.pushState({}, "", newUrl);
  }

  // On load: allow server-rendered results to be used; attach popstate for back/forward
  $(window).on("popstate", function () {
    // re-apply filters based on url
    // parse url params and set UI, then call doApplyFilters
    var params = new URLSearchParams(window.location.search);
    var authors = [];
    params.getAll("wajsf_authors[]").forEach(function (a) {
      authors.push(a);
    });

    $('input[name="wajsf_authors[]"]').prop("checked", false);
    authors.forEach(function (a) {
      $('input[name="wajsf_authors[]"][value="' + a + '"]').prop(
        "checked",
        true
      );
    });

    var date_from = params.get("wajsf_date_from") || "";
    var date_to = params.get("wajsf_date_to") || "";
    $("#wajsf_date_from").val(date_from);
    $("#wajsf_date_to").val(date_to);

    doApplyFilters(params.get("wajsf_paged") || 1);
  });
})(jQuery);
