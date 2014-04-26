<script type="text/javascript">
  function initialize() {
    // obligatory Google help file naming
    var bounds = new google.maps.LatLngBounds();
    var restaurant;

    var map = new google.maps.Map(document.getElementById("restaurants-map"));

    <?php
         foreach ($restaurants as $restaurant) {
            $lat = $restaurant->field_latitude[$restaurant->language][0]['value'];
            $lng = $restaurant->field_longitude[$restaurant->language][0]['value'];
            print 'restaurant = new google.maps.LatLng(' . $lat . ',' . $lng . ');';
            print 'bounds.extend(restaurant);';
            print '
       new google.maps.Marker({
         position: restaurant,
         map: map,
         title: \'' . t('@title', array('@title' => $restaurant->title)) . '\'
       });
';
         }
    ?>
    map.fitBounds(bounds);
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div id="restaurants-map">
</div>
