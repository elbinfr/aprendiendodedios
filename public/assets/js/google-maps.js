/* ===== Google Map for Contact Us page ===== */

function initialize() {
    var map_canvas = document.getElementById('map_canvas');
    var map_options = {
        scrollwheel: false,
        center: new google.maps.LatLng(-8.069930, -79.012448),
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(map_canvas, map_options)
}
google.maps.event.addDomListener(window, 'load', initialize);
