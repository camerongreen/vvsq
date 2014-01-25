<script type="text/javascript">
  function initialize() {
    // obligatory Google help file naming
    var myLatLng = new google.maps.LatLng(<?= $lat; ?>, <?= $lng; ?>);
    var mapOptions = {
      center: myLatLng,
      zoom: 15
    };
    var map = new google.maps.Map(document.getElementById("qld-map"),
      mapOptions);
    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: '<?= $node->title; ?>'
    });
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div id="qld-map">
</div>
