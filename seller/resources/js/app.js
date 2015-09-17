/**
* Metis - Bootstrap-Admin-Template v2.3.2
* Author : onokumus 
* Copyright 2015
* Licensed under MIT (https://github.com/onokumus/Bootstrap-Admin-Template/blob/master/LICENSE.md)
*/

;(function($, Metis) {
    var $button = $('.inner a.btn');
    Metis.metisButton = function() {
        $.each($button, function() {
            $(this).popover({
                placement: 'bottom',
                title: this.innerHTML,
                content: this.outerHTML,
                trigger: (Metis.isTouchDevice) ? 'touchstart' : 'hover'
            });
        });
    };
    return Metis;
})(jQuery, Metis || {});



