;(function($){
  "use strict";
  Metis.addProduct = function() {
    $('#productPageType').change(function(){
        location.href=myJsMain.baseURL+'product/add_product/'+$(this).val();
    });
};
return Metis;
})(jQuery);