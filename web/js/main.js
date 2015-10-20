/**
 * Created with JetBrains PhpStorm.
 * User: usimpma
 * Date: 7/07/13
 * Time: 5:20 PM
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){

  $('div.datetime').datetimepicker({
    pickTime: true
  });

  $('div.date').datetimepicker({
    pickTime: false
  });

  $('*[title]').each(function(i, el){
    $(el).on({
      "keyup focus": function() {
//        if (this.value.length != 0) {
//          if ($(this).attr('placeholder'))
//            if ($(".tooltip").length == 0) {
//              $(this).tooltip("show");
//            }
//          } else {
//            if ($(".tooltip").length != 0) {
//              $(this).tooltip("hide");
//            }
//          }
      },
      blur: function() {
//        $(this).tooltip("hide");
      }
//    }).tooltip({
//      placement: "right",
//      trigger: "focus"
    }).tooltip({
      placement: "right",
      trigger: "hover"
    });
  });

  $("input[name='issue[date]']").on('shown.bs.tooltip', function () {
    $('.tooltip').each(function(index, el){
      if ($(el).text().indexOf('date')) {
        $(el).css('left', $(this).position().left + 25 + 'px');
      }
    });
  });

  // remove unpleasant text from existing file upload fields
  $('span.fileupload-preview').each(function(i, el) {
    var needles = ['_hpub_', '_sandbox_pem_', '_production_pem_'];
    $.each(needles, function(j, needle) {
      var txt = $(el).html();
      if (txt.indexOf(needle) >= 0) {
        txt = txt.substr(txt.indexOf(needle) + needle.length);
        $(el).html(txt);
      }
    })
  });

});