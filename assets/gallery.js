(function(win, doc) {
    'use strict';

    /* Basic Object
    --------------------------------------------------- */
    win.scGallery = win.scGallery || {};

    win.scGallery = {
        selector: '.sc-gallery',

        init: function()
        {
            let self = this;
            self.galleries = doc.querySelectorAll( self.selector );

            if ( self.galleries )
            {
                self.galleries.forEach( function( gallery )
                {
                    Chocolat( gallery.querySelectorAll( 'a' ), {
                        loop: true,
                        fullScreen: false,
                    });
                });
            }

        },
    };

    if ( doc.querySelectorAll( win.scGallery.selector ) )
    {
        win.scGallery.init();
    }

})(window, document);
