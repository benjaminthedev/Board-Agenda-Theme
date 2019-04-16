(function($) {
  var getAdverts = function() {
    $('.ba-adzone').each(function() {
      var $this = $(this);

      // where to show
      var postid = /postid-(\d*)/.exec(document.body.className);
      var isHome = $('body').hasClass('home') ? 'front-page' : 0;
      if ( postid ) {
        postid = postid.index;
      }

      var show = $this.data('show');
      var hide = $this.data('hide');
      var showMe = false;

      show = show.length ? show.split(',') : [];
      hide = hide.length ? hide.split(',') : [];
      // Selective show?
      if ( show.length === 0 || show.indexOf(postid) >= 0 || ( isHome && show.indexOf(isHome) >= 0 ) ) {
        showMe = true;
      }

      if ( hide.length && ( hide.indexOf(postid) || ( isHome && hide.indexOf(isHome) ) ) ) {
        showMe = false;
      }

      if( showMe ) {
        $.ajax({
          type: 'POST',
          url: ajax_login_object.ajaxurl,
          data: {
            'action': 'get_ad', //calls wp_ajax_nopriv_ajaxlogin
            'id': $(this).data('id'),
            'security': ajax_login_object.nonce,
          },
          success: function(data) {
            if (data && data.length) {
              $this.html(data);
            }
          }
        });
      }
    });
  }

  $(document).ready( getAdverts );
})(jQuery);
