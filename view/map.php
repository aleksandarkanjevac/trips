
<div id="map"></div>


<script>
function loadGPXFileIntoGoogleMap(map, filename) {
    $.ajax({
        url: filename,
        dataType: "xml",
        success: function (data) {
            var parser = new GPXParser(data, map);
            parser.setTrackColour("#00cc66"); // Set the track line colour
            parser.setTrackWidth(5); // Set the track line width
            parser.setMinTrackPointDelta(0.001); // Set the minimum distance between track points
            parser.centerAndZoom(data);
            parser.addTrackpointsToMap(); // Add the trackpoints
            parser.addRoutepointsToMap(); // Add the routepoints
            parser.addWaypointsToMap(); // Add the waypoints
        },
        error:function(error){
            console.log(error);
        }
    });
}

    $(document).ready(function () {
   
    var file = "index.php?r=getMap&id=<?=$map->id?>";
    var mapOptions = {
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map"),
        mapOptions);
    loadGPXFileIntoGoogleMap(map,file);
      
});
</script>




