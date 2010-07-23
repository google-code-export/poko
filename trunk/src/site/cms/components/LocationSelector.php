<?
	$eName = $_REQUEST["eName"];
	$location = $_REQUEST["location"];
	$searchAddress = $_REQUEST["searchAddress"];
	$popupWidth = (int)($_REQUEST["popupWidth"]);
	$popupHeight = (int)($_REQUEST["popupWidth"]);
	$mapWidth = $popupWidth - 20;
	$mapHeight = $popupHeight - 100;
	
	$centerParts = explode( ",", $location );
	$centerLat = trim($centerParts[0]);
	$centerLong = trim($centerParts[1]);
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
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAaM2df7Rr0rd2qaRG5YdoLRTGDWPcvWBxAxLZaXjuOvw18nQzOBQrnVN-ODq1bNZA3n9PnUxV_O4c_w" type="text/javascript"></script>
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
		var center = new GLatLng( <?= $centerLat ?>, <?= $centerLong ?> );
		gCenter = center;
		map.setCenter(center, 3);
		geocoder = new GClientGeocoder();
		var marker = new GMarker(center, {draggable: true});  
		map.addOverlay(marker);
		//document.getElementById("lat").value = center.lat();
		//document.getElementById("lng").value = center.lng();
		geocoder = new GClientGeocoder();
		GEvent.addListener(marker, "dragend", function() {
			var point = marker.getPoint();
			gCenter = point;
			map.panTo(point);
			//document.getElementById("lat").value = point.lat();
			//document.getElementById("lng").value = point.lng();
        });
		GEvent.addListener(map, "moveend", function() {
			map.clearOverlays();
			var center = map.getCenter();
			gCenter = center;
			var marker = new GMarker(center, {draggable: true});
			map.addOverlay(marker);
			//document.getElementById("lat").value = center.lat();
			//document.getElementById("lng").value = center.lng();
        });
		GEvent.addListener(marker, "dragend", function() {
			var point = marker.getPoint();
			map.panTo(point);
			gCenter = point;
			//document.getElementById("lat").value = point.lat();
			//document.getElementById("lng").value = point.lng();
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
    }
	GEvent.addListener(map, "moveend", function() {
		map.clearOverlays();
        var center = map.getCenter();
		gCenter = center;
		var marker = new GMarker(center, {draggable: true});
		map.addOverlay(marker);
		//document.getElementById("lat").value = center.lat();
		//document.getElementById("lng").value = center.lng();
		GEvent.addListener(marker, "dragend", function() {
			var pt =marker.getPoint();
			map.panTo(pt);
			//document.getElementById("lat").value = pt.lat();
			//document.getElementById("lng").value = pt.lng();
        });
		updateParentCenter( center );
    });
}

function showAddress( address ) {
	geocoder.getLatLng( address, function( center ) {
		if ( !center ) {
			alert( "This is not a valid address" );
		} else {
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