<?
	$eName = $_REQUEST["eName"];
	$location = $_REQUEST["location"];
	$searchAddress = $_REQUEST["searchAddress"];
	$popupWidth = (int)($_REQUEST["popupWidth"]);
	$popupHeight = (int)($_REQUEST["popupWidth"]);
	$mapWidth = $popupWidth - 20;
	$mapHeight = $popupHeight - 100;
	
	$latlng = explode( ",", $location );
	$centerLat = trim($latlng[0]);
	$centerLong = trim($latlng[1]);
	
	$key = $_REQUEST["key"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Find geographical coordinates (latitude, longitude) with Google Maps</title>
<meta name="description" content="Find latitude and longitude with Google maps">
<meta name="keywords" content="find, geographical, coordinates, Google, map, longitude, latitude">
<style type="text/css">
body {padding:0; font: 11px Arial, Helvetica, Sans serif; color:#666666;}
</style>
<script type="text/javascript" src="../../../../js/cms/jquery-1.3.2.min.js" ></script> 
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=$key?>" type="text/javascript"></script>
<script type="text/javascript">

var el_name = "<?= $eName ?>";
var map = null;
var parent_element = null;
var gCenter = null;

function loadmap() {
    if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
		
		gCenter = new GLatLng( <?php echo $centerLat; ?>, <?php echo $centerLong; ?> );
		map.setCenter(gCenter, 3);
		
		geocoder = new GClientGeocoder();
		
		var marker = new GMarker(gCenter, {draggable: true});  
		map.addOverlay(marker);
		
		GEvent.addListener(marker, "dragend", function() {
			var point = marker.getPoint();
			gCenter = point;
			map.panTo(point);
        });
		GEvent.addListener(map, "moveend", function() {
			map.clearOverlays();
			gCenter = map.getCenter();
			var marker = new GMarker(gCenter, {draggable: true});
			map.addOverlay(marker);
        });
		
		if ( window.attachEvent ) {
			window.attachEvent("onresize", function() { 
				map.checkResize();
				map.setCenter( gCenter );
			});
		} else {
			window.addEventListener("resize", function() { 
				map.checkResize();
				map.setCenter( gCenter );
			}, false);
		}
		updateParentCenter( gCenter );
    }
	GEvent.addListener(map, "moveend", function() {
		map.clearOverlays();
        gCenter = map.getCenter();
		var marker = new GMarker(gCenter, {draggable: true});
		map.addOverlay(marker);
		GEvent.addListener(marker, "dragend", function() {
			var pt = marker.getPoint();
			map.panTo(pt);
			gCenter = pt;
        });
		updateParentCenter( gCenter );
    });
}

function showAddress( address ) {
	geocoder.getLatLng( address, function( center ) {
		if ( !center ) {
			alert( "This is not a valid address" );
		} else {
			gCenter = center;
			map.panTo( center, map.getZoom() );
		}
	});	
}

$( function() {
	parent_element = $("#"+el_name, opener.document );
})

function updateParent(value) {
	parent_element.val( value );
}

function updateParentCenter( center ) {
	parent_element.val( center.lat() + ", " + center.lng() );
}

</script>
<style type="text/css">    v\:* {
      behavior:url(#default#VML);
    }
    html, body {
      height: 100%;   
	  width: 100%;
	  margin: 0;
	  padding: 0;
    }
	#map {
		width: 100%;
		height: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
	}
	#search {
		background-color: #E9EFF8;
		position: absolute;
		width: 400px;
		margin-left: auto;
		margin-right: auto;
		bottom: 0px;
		z-index: 100;
		<? if ( !$searchAddress ) echo "display: none;" ?>
	}
	#address {
		width: 315px;
		display: inline;
	}
</style> 
</head>  
<body onload="loadmap()" onunload="GUnload()">
<div id="search">
	<strong>Search Address: </strong><br/>
	<form action="#" onsubmit="showAddress(this.address.value); return false">
		<input id="address" type="text" name="address" value="" />
		<input type="submit" value="Search!" />
	</form>
</div>
<div id="map"></div>
</div>




</body>
</html>