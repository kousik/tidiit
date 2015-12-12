;(function($){
  "use strict";
  Metis.MetisFile = function() {

    /*----------- BEGIN elfinder CODE -------------------------*/
    var elf = $('#elfinder').elfinder({
        url: 'assets/elfinder-2.0-rc1/php/connector.php'  // connector URL (REQUIRED)
                // lang: 'de',             // language (OPTIONAL)
    }).elfinder('instance');
    /*----------- END elfinder CODE -------------------------*/

};
  return Metis;
})(jQuery);

;(function($){
  "use strict";
  Metis.MetisMaps = function() {
    var map1, map2, map3, map4, map5, map6, path, addressMap;

    map1 = new GMaps({
        el: '#gmaps-basic',
        lat: -12.043333,
        lng: -77.028333,
        zoomControl: true,
        zoomControlOpt: {
            style: 'SMALL',
            position: 'TOP_LEFT'
        },
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        overviewMapControl: false
    });

    map2 = new GMaps({
        el: '#gmaps-marker',
        lat: -12.043333,
        lng: -77.028333
    });
    map2.addMarker({
        lat: -12.043333,
        lng: -77.03,
        title: 'Lima',
        details: {
            database_id: 42,
            author: 'HPNeo'
        },
        click: function(e) {
            if (console.log)
                console.log(e);
            alert('You clicked in this marker');
        },
        mouseover: function(e) {
            if (console.log)
                console.log(e);
        }
    });
    map2.addMarker({
        lat: -12.042,
        lng: -77.028333,
        title: 'Marker with InfoWindow',
        infoWindow: {
            content: '<p>HTML Content</p>'
        }
    });

    map3 = new GMaps({
        el: '#gmaps-geolocation',
        lat: -12.043333,
        lng: -77.028333
    });

    GMaps.geolocate({
        success: function(position) {
            map3.setCenter(position.coords.latitude, position.coords.longitude);
        },
        error: function(error) {
            alert('Geolocation failed: ' + error.message);
        },
        not_supported: function() {
            alert("Your browser does not support geolocation");
        },
        always: function() {
            //alert("Done!");
        }
    });

    map4 = new GMaps({
        el: '#gmaps-polylines',
        lat: -12.043333,
        lng: -77.028333,
        click: function(e) {
            console.log(e);
        }
    });

    path = [[-12.044012922866312, -77.02470665341184], [-12.05449279282314, -77.03024273281858], [-12.055122327623378, -77.03039293652341], [-12.075917129727586, -77.02764635449216], [-12.07635776902266, -77.02792530422971], [-12.076819390363665, -77.02893381481931], [-12.088527520066453, -77.0241058385925], [-12.090814532191756, -77.02271108990476]];

    map4.drawPolyline({
        path: path,
        strokeColor: '#131540',
        strokeOpacity: 0.6,
        strokeWeight: 6
    });

    map5 = new GMaps({
        el: '#gmaps-route',
        lat: -12.043333,
        lng: -77.028333
    });
    map5.drawRoute({
        origin: [-12.044012922866312, -77.02470665341184],
        destination: [-12.090814532191756, -77.02271108990476],
        travelMode: 'driving',
        strokeColor: '#131540',
        strokeOpacity: 0.6,
        strokeWeight: 6
    });

    addressMap = new GMaps({
        el: '#gmaps-geocoding',
        lat: -12.043333,
        lng: -77.028333
    });
    $('#geocoding_form').submit(function(e) {
        e.preventDefault();
        GMaps.geocode({
            address: $('#address').val().trim(),
            callback: function(results, status) {
                if (status === 'OK') {
                    var latlng = results[0].geometry.location;
                    addressMap.setCenter(latlng.lat(), latlng.lng());
                    addressMap.addMarker({
                        lat: latlng.lat(),
                        lng: latlng.lng()
                    });
                }
            }
        });
    });
  };
  return Metis;
})(jQuery);

;(function($, Metis) {
  if (!$().sortable) {
    return;
  }
  var $sortable = $('.inner [class*=col-]');
  Metis.metisSortable = function() {
    $sortable.sortable({
      placeholder: "ui-state-highlight"
    }).disableSelection();
  };
  return Metis;
})(jQuery, Metis || {});


;(function($){
  "use strict";
  
  Metis.MetisTable = function() {

    /*----------- BEGIN TABLESORTER CODE -------------------------*/
    /* required jquery.tablesorter.min.js*/
    $(".sortableTable").tablesorter();
    /*----------- END TABLESORTER CODE -------------------------*/

    /*----------- BEGIN datatable CODE -------------------------*/
    $('#dataTable').dataTable({
                "aLengthMenu": [5,10,15,20,25,50]
//         "sDom": "<'pull-right'l>t<'row'<'col-lg-6'f><'col-lg-6'p>>",
//         "sPaginationType": "bootstrap",
//         "oLanguage": {
//             "sLengthMenu": "Show _MENU_ entries"
//         }
    });
    /*----------- END datatable CODE -------------------------*/

    /*----------- BEGIN action table CODE -------------------------*/
    // DEPRECATED
//     $('#actionTable button.remove').on('click', function() {
//         $(this).closest('tr').remove();
//     });
//     $('#actionTable button.edit').on('click', function() {
//         $('#editModal').modal({
//             show: true
//         });
//         var val1 = $(this).closest('tr').children('td').eq(1),
//                 val2 = $(this).closest('tr').children('td').eq(2),
//                 val3 = $(this).closest('tr').children('td').eq(3);
//         $('#editModal #fName').val(val1.html());
//         $('#editModal #lName').val(val2.html());
//         $('#editModal #uName').val(val3.html());
// 
// 
//         $('#editModal #sbmtBtn').on('click', function() {
//             val1.html($('#editModal #fName').val());
//             val2.html($('#editModal #lName').val());
//             val3.html($('#editModal #uName').val());
//         });
// 
//     });
    /*----------- END action table CODE -------------------------*/

};
  
  return Metis;
})(jQuery);

;(function($, Metis) {
    "use strict";
    var _updateClass = function(el, c) {
        el.removeClass("primary success danger warning info default").addClass(c);
    };
    Metis.MetisPricing = function() {
        var $dark = $("ul.dark li.active"),
                $light = $("ul#light li.active");

        $("#dark-toggle label").on(Metis.buttonPressedEvent, function() {
            var $this = $(this);
            _updateClass($dark, $this.find("input").val());
        });

        $("#light-toggle label").on(Metis.buttonPressedEvent, function() {
            var $this = $(this);
            _updateClass($light, $this.find("input").val());
        });
    };
    return Metis;
})(jQuery, Metis || {});

;(function($, Metis){
  Metis.MetisProgress = function() {
    var $bar = $('.progress .progress-bar');
    $.each($bar, function () {
      
      var $this = $(this);
      
      $this
      .animate({
        width: $(this).attr('aria-valuenow') + '%'
      })
      .popover({
        placement: 'bottom',
        title: 'Source',
        content: this.outerHTML
      });
    });
  };
  return Metis;
})(jQuery, Metis);