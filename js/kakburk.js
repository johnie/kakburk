;(function () {

  'use strict';

  window.Kakburk = window.Kakburk || {
    defaults: {
      handle: window.kakburken.handle,
      description: window.kakburken.description,
      readmore_text: window.kakburken.readmore_text || "",
      readmore_link: window.kakburken.readmore_link || "",
      button: window.kakburken.button || "Close"
    },
    pop: function () {
      var _kakan = this.defaults;
      var readmore;

      if ( _kakan.readmore_text !== "" ) {
        readmore = ' <a href="' + _kakan.readmore_link + '">' + _kakan.readmore_text + '</a>';
      }

      var kakburk = '<div class="kakburk ' + _kakan.handle + '"><div class="kakburk__inner"> <p>'+ _kakan.description + readmore + '</p> <button class="kakburk__close" id="kakburk_close">' + _kakan.button + '</button> </div> </div>';

      if ( 'set' !== $.cookie( 'kakburk-pop' ) ) {

        $('body').prepend(kakburk);

        $('#kakburk_close').on('click', function () {
          $.cookie( 'kakburk-pop', 'set' );
          $( '.' + _kakan.handle ).remove();
        });

      }
    },
    init: function () {
      this.pop();
    }
  };

  Kakburk.init();
  
})();
