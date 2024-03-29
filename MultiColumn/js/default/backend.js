/**
 * This file is part of an ADDON for use with Black Cat CMS Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 *   @author			Matthias Glienke, letima development
 *   @copyright			2023, Black Cat Development
 *   @link				https://blackcat-cms.org
 *   @license			https://www.gnu.org/licenses/gpl.html
 *   @category			CAT_Modules
 *   @package			multiColumn
 *
 */

if (typeof checkCols !== "function") {
  function checkCols($ul) {
    var $par = $ul.closest("div"),
      $yes = $par.children(".cc_MC_y"),
      $no = $par.children(".cc_MC_n"),
      size = $ul.children("li").not(".prevTemp, .clear").length;
    if (size == 0) {
      $yes.hide();
      $no.show();
    } else {
      $yes.show();
      $no.hide();
    }
  }
}
if (typeof orderMC !== "function") {
  function orderMC($ul, $setKind) {
    $ul.children(".clear").remove();
    var $par =
        '<li class="clear">' +
        cattranslate("Row", false, false, "cc_multicolumn") +
        " __id__</li>",
      count = 1,
      kind = parseInt($setKind.filter(":checked").val()),
      size = parseInt($ul.children("li").not(".prevTemp, .clear").length);
    $ul
      .removeClass(function (index, css) {
        return (css.match(/(^|\s)MC_col\S+/g) || []).join(" ");
      })
      .addClass("MC_col" + kind);

    if (size > 0) {
      i = 0;
      while (i < size) {
        $ul
          .children("li")
          .not(".prevTemp, .clear")
          .eq(i)
          .before(
            $($par).html(function (index, html) {
              return html.replace(/__id__/g, count++);
            })
          );
        i = i + kind;
      }
    }
  }
}
if (typeof disableWYSIWYG !== "function") {
  function disableWYSIWYG($WYSIWYG) {
    var wID = $WYSIWYG.children("textarea").attr("id"),
      editorInstance = CKEDITOR.instances[wID],
      config = editorInstance.config;

    editorInstance.destroy();
    CKEDITOR.remove(wID);

    return config;
  }
}
if (typeof enableWYSIWYG !== "function") {
  function enableWYSIWYG($WYSIWYG, config, content) {
    var newConfig = {},
      wID = $WYSIWYG.children("textarea").attr("id");
    $.each(config, function (index, cF) {
      if (
        typeof cF == "string" ||
        typeof cF == "boolean" ||
        typeof cF == "number"
      ) {
        newConfig[index] = cF;
      }
    });
    CKEDITOR.replace(wID, newConfig);
    CKEDITOR.instances[wID].setData(content);
    CKEDITOR.instances[wID].updateElement();
  }
}

$(document).ready(function () {
  if (typeof mCIDs !== "undefined" && typeof mCLoaded === "undefined") {
    // This is a workaround if backend.js is loaded twice
    mCLoaded = true;
    $.each(mCIDs, function (index, mCID) {
      var $MC = $("#cc_MC_" + mCID.mc_id),
        $mcUL = $MC.children("#cc_MC_cols_" + mCID.mc_id),
        cols = $mcUL.data("cols"),
        $WYSIWYG = $("#MC_WYSIWYG_" + mCID.mc_id).hide(),
        $prevTemp = $mcUL
          .children(".prevTemp")
          .clone()
          .removeClass("prevTemp")[0].outerHTML,
        $mcNav = $("#cc_MC_nav_" + mCID.mc_id),
        $setKind = $MC.find(".set_kind");
      if ($setKind.length == 0) {
        $setKind = $('<input value="2" type="radio" checked="checked">');
      }
      $MC
        .find(".cc_MC_tabs")
        .find("input[type=submit]")
        .click(function () {
          orderMC($mcUL, $setKind);
        });

      $MC.find(".cc_toggle_set").next("form").hide();
      $MC.on("click", ".cc_toggle_set, .mC_skin input:reset", function () {
        $(this).closest(".mC_skin").children("form").slideToggle(200);
      });

      orderMC($mcUL, $setKind);

      $(".colCount").on("click", function () {
        $(this).select();
      });

      $("#add_C_" + mCID.mc_id).click(function () {
        var ajaxData = {
          page_id: mCID.page_id,
          section_id: mCID.section_id,
          mc_id: mCID.mc_id,
          action: "addContent",
          colCount: $(this).next("input").val(),
          _cat_ajax: 1,
        };

        $.ajax({
          type: "POST",
          context: $MC,
          url: CAT_URL + "/modules/cc_multicolumn/save.php",
          dataType: "JSON",
          data: ajaxData,
          cache: false,
          beforeSend: function (data) {
            // Set activity and store in a variable to use it later
            data.process = set_activity("Adding column");
          },
          success: function (data, textStatus, jqXHR) {
            clearInterval(timerId);
            sessionSetTimer(TimeStringToSecs(data.session));
            if (data.success === true) {
              $.each(data.colIDs, function (index, cID) {
                var $newCont = $($prevTemp);
                $mcUL.children("li:last").before($newCont);
                $newCont
                  .attr("id", "catMC_" + cID)
                  .html(function (index, html) {
                    return html.replace(/__column_id__/g, cID);
                  })
                  .find("input, button, textarea")
                  .prop("disabled", false);
                dialog_form($newCont.find(".ajaxForm"));
              });
              $mcUL.sortable("refresh");
              checkCols($mcUL);
              orderMC($mcUL, $setKind);
              return_success(jqXHR.process, data.message);
            } else {
              // return error
              return_error(jqXHR.process, data.message);
            }
          },
          error: function (data, textStatus, jqXHR) {
            return_error(jqXHR.process, data.message);
          },
        });
      });

      $mcUL.on("click", ".mCIcon-remove", function () {
        $(this).closest("div").children("p").slideToggle(100);
      });

      $mcUL.on("click", ".cc_MC_del_res", function () {
        $(this).closest("div").children("p").slideUp(100);
      });

      $mcUL.on("click", ".cc_MC_del_conf", function () {
        var $cur = $(this),
          $li = $cur.closest("li"),
          $inputs = $li.find("input"),
          ajaxData = {
            page_id: mCID.page_id,
            section_id: mCID.section_id,
            mc_id: mCID.mc_id,
            colID: $inputs.filter("input[name=colID]").val(),
            action: "removeContent",
            _cat_ajax: 1,
          };

        $.ajax({
          type: "POST",
          context: $li,
          url: CAT_URL + "/modules/cc_multicolumn/save.php",
          dataType: "JSON",
          data: ajaxData,
          cache: false,
          beforeSend: function (data) {
            // Set activity and store in a variable to use it later
            data.process = set_activity("Deleting column");
          },
          success: function (data, textStatus, jqXHR) {
            clearInterval(timerId);
            sessionSetTimer(TimeStringToSecs(data.session));
            if (data.success === true) {
              $(this).slideUp(300, function () {
                $(this).remove();
                checkCols($mcUL);
                orderMC($mcUL, $setKind);
              });
              return_success(jqXHR.process, data.message);
            } else {
              // return error
              return_error(jqXHR.process, data.message);
            }
          },
          error: function (data, textStatus, jqXHR) {
            return_error(jqXHR.process, data.message);
          },
        });
      });

      $mcUL.on("click", ".MC_publish", function () {
        var $cur = $(this),
          $li = $cur.closest("li"),
          $inputs = $li.find("input"),
          ajaxData = {
            page_id: mCID.page_id,
            section_id: mCID.section_id,
            mc_id: mCID.mc_id,
            colID: $inputs.filter("input[name=colID]").val(),
            action: "publishContent",
            _cat_ajax: 1,
          };
        dialog_ajax(
          "Inhalt veröffentlichen",
          CAT_URL + "/modules/cc_multicolumn/save.php",
          ajaxData,
          "POST",
          "JSON",
          false,
          function (data) {
            if (data.published == 1) $cur.addClass("active");
            else $cur.removeClass("active");
            clearInterval(timerId);
            sessionSetTimer(TimeStringToSecs(data.session));
          },
          $cur
        );
      });

      $mcUL.sortable({
        handle: ".drag_corner",
        start: function (event, ui) {
          var config = disableWYSIWYG($WYSIWYG);
          $WYSIWYG.data("config", config);
        },
        stop: function (event, ui) {
          var $cur = $(this),
            $par = $cur.closest("li"),
            $form = $par.children("form");

          enableWYSIWYG(
            $WYSIWYG,
            $WYSIWYG.data("config"),
            $form.find(".MC_content").html()
          );
        },
        update: function (event, ui) {
          var current = $(this),
            ajaxData = {
              positions: current.sortable("toArray"),
              section_id: mCID.section_id,
              page_id: mCID.page_id,
              mc_id: mCID.mc_id,
              action: "reorder",
              _cat_ajax: 1,
            };
          $.ajax({
            type: "POST",
            url: CAT_URL + "/modules/cc_multicolumn/save.php",
            dataType: "json",
            data: ajaxData,
            cache: false,
            beforeSend: function (data) {
              data.process = set_activity("Sort entries");
            },
            success: function (data, textStatus, jqXHR) {
              clearInterval(timerId);
              sessionSetTimer(TimeStringToSecs(data.session));
              if (data.success === true) {
                return_success(jqXHR.process, data.message);
              } else {
                return_error(jqXHR.process, data.message);
              }
              orderMC($mcUL, $setKind);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              return_error(jqXHR.process, errorThrown.message);
            },
          });
        },
      });

      $mcNav.children("li").click(function () {
        var $curr = $(this),
          cur_ind = $curr.index(),
          $nav = $curr.closest("ul"),
          $tabs = $nav.next("ul"),
          $currT = $tabs.children("li").eq(cur_ind);
        $nav
          .children("li")
          .removeClass("active")
          .filter($curr)
          .addClass("active");
        $tabs
          .children("li")
          .removeClass("active")
          .filter($currT)
          .addClass("active");
      });

      $MC.on("click", ".saveCol", function (e) {
        e.preventDefault();
        var $cur = $(this),
          $par = $cur.closest("li"),
          $form = $par.children("form"),
          colID = $form.children("input[name=colID]").val(),
          $content = $("#MC_cont_" + colID),
          wID = $WYSIWYG.children("textarea").attr("id"),
          editorInstance = CKEDITOR.instances[wID],
          config = editorInstance.config,
          oldContent = editorInstance.getData(),
          $oldForm = $WYSIWYG.closest("form");

        if ($cur.hasClass("input_100p")) {
          $content.html(oldContent);
          $cur
            .addClass("input_50p")
            .removeClass("input_100p")
            .prev("button")
            .show();
          editorInstance.updateElement();
        } else {
          var config = disableWYSIWYG($WYSIWYG);
          $WYSIWYG.show().insertAfter($content.hide());
          enableWYSIWYG($WYSIWYG, config, $form.find(".MC_content").html());
        }
        $MC.find(".MC_content").show();
        $WYSIWYG.hide();

        dialog_form(
          $form,
          false,
          function (data) {
            clearInterval(timerId);
            sessionSetTimer(TimeStringToSecs(data.session));
          },
          "JSON",
          function () {}
        );
        $form.submit();
      });

      $MC.on("click", ".showWYSIWYG", function (e) {
        e.preventDefault();

        var $cur = $(this),
          $par = $cur.closest("li"),
          $form = $par.children("form"),
          colID = $par.find("input[name=colID]").val(),
          $content = $("#MC_cont_" + colID),
          content = $content.html(),
          wID = $WYSIWYG.children("textarea").attr("id"),
          editorInstance = CKEDITOR.instances[wID],
          config = editorInstance.config,
          oldContent = editorInstance.getData(),
          $oldForm = $WYSIWYG.closest("form");

        $oldForm
          .children(".showWYSIWYG")
          .show()
          .next("input")
          .addClass("input_50p")
          .removeClass("input_100p");
        $cur
          .hide()
          .next("input")
          .removeClass("input_50p")
          .addClass("input_100p");

        if ($oldForm.length > 0) {
          editorInstance.updateElement();
          $oldForm.submit();
          $oldForm.find(".MC_content").html(oldContent);
        }

        var config = disableWYSIWYG($WYSIWYG);

        $MC.find(".MC_content").show();

        $WYSIWYG.show().insertAfter($content.hide());

        enableWYSIWYG($WYSIWYG, config, $form.find(".MC_content").html());
      });

      $WYSIWYG.on("click", "input:reset", function (e) {
        e.preventDefault();
        $mcUL.children("li").removeClass("MC_WYSIWYG fc_gradient1");
        $WYSIWYG.hide();
      });

      checkCols($mcUL);
    });
  }
});
