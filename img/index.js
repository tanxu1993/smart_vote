var qs = function(e) {
      return document.querySelector(e);
    };
    var button = document.getElementById("button");

    var geetest = qs(".geetest");
    button.onclick = function() {
      geetest.style.display = "block";
    };
    var close = document.getElementById("close");
    close.onclick = function() {
      geetest.style.display = "none";
    };
    qs(".bg").onclick = function() {
      geetest.style.display = "none";
    };
    window.gt_custom_ajax = function(result, id, message) {
      if(result) {
        qs('#' + id).parentNode.parentNode.style.display = "none";
      }
    }