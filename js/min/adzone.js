!function(a){var e=function(){a(".ba-adzone").each(function(){var e=a(this),t=/postid-(\d*)/.exec(document.body.className),n=a("body").hasClass("home")?"front-page":0;t&&(t=t.index);var d=e.data("show"),i=e.data("hide"),o=!1;d=d.length?d.split(","):[],i=i.length?i.split(","):[],(0===d.length||d.indexOf(t)>=0||n&&d.indexOf(n)>=0)&&(o=!0),i.length&&(i.indexOf(t)||n&&i.indexOf(n))&&(o=!1),o&&a.ajax({type:"POST",url:ajax_login_object.ajaxurl,data:{action:"get_ad",id:a(this).data("id"),security:ajax_login_object.nonce},success:function(a){a&&a.length&&e.html(a)}})})};a(document).ready(e)}(jQuery);