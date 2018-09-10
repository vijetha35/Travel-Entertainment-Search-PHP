<?php
// header("Pragma-directive: no-cache");
//     header("Cache-directive: no-cache");
//     header("Cache-control: no-cache");
//     header("Pragma: no-cache");
//     header("Expires: 0");
	$table_results="{1:1}";
	$latitude ="34.0223519";
	$longitude ="-118.285117";
		function getMeter($i) {
   	  		return $i*1609.344;
		}
			
			
			//$APIKey ='AIzaSyBDIaOeP0fVpdj3NiCOE-BfsZiIWn-rPoE';
			$APIKey ='AIzaSyAGbF6Cjrj0tHbq-YK5lwR3jEohYg84dXI';
			$options=array(
						"ssl"=>array(
							"verify_peer"=>false,
							"verify_peer_name"=>false,
							),
					);
			if(isset($_GET["placeid"])) {
				$photoReviewUrl = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$_GET["placeid"]."&key=".$APIKey;
				$response = file_get_contents($photoReviewUrl, false, stream_context_create($options));
				$data = json_decode($response, TRUE);
				$counter=0;
				$photoslength=0;
				if (array_key_exists('photos', $data['result']))
					$photoslength=count($data['result']['photos']);
				if($photoslength>5 )
					$photoslength=5;
				while($counter<$photoslength){
					$photoreference = $data['result']['photos'][$counter]['photo_reference'];
					$width = $data['result']['photos'][$counter]['width'];
					$photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=".$width."&photoreference=".$photoreference."&key=".$APIKey;
					$photoresp = file_get_contents($photoUrl , false, stream_context_create($options));
					file_put_contents($counter.".jpg",$photoresp); 
					$counter += 1;
				}
				echo $response;
				die();

			}
			if(isset($_POST["search"]))
			{
					$keyword = isset($_POST["keyword"])? $_POST["keyword"]:"";
					$category = isset($_POST["category"])?$_POST["category"]:"";
					$distance = isset($_POST["distance"])?$_POST["distance"]:"";
					if (empty($distance))
					{
						$distance = 10;
					}
					$distance_miles =$distance;
					$distance = getMeter($distance);
					$location = isset($_POST["place"])?$_POST["place"]:"";;
			 		$customLocUrl ='https://maps.googleapis.com/maps/api/geocode/json?address='.rawurlencode($location). '&key='.$APIKey;
			 		$hereUrl= 'http://ip-api.com/json';
					
					if(isset($_POST["place"]) && !empty($_POST["place"]))
					{
						
						$json= file_get_contents($customLocUrl ,false, stream_context_create($options));// , false,$context);
					 	$data = json_decode($json, TRUE);
					 	$latitude = $data['results'][0]['geometry']['location']['lat'];
					 	$longitude = $data['results'][0]['geometry']['location']['lng'];
					}
					else
					{

							$response = file_get_contents($hereUrl, false, stream_context_create($options));
							$data = json_decode($response, TRUE);
							$latitude = $data['lat'];
							$longitude = $data['lon'];
					}			 
					$url =  'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$latitude.','.$longitude.'&radius='.$distance.'&type='.rawurlencode($category).'&keyword='.rawurlencode($keyword).'&key='.$APIKey;
					echo "<h1>" .$url."</h1>";
					 $table_results = file_get_contents($url,false, stream_context_create($options)) ;
			}
?> 
<!DOCTYPE html>
<html>
<head>
<meta Http-Equiv="Cache-Control" Content="no-cache">
<meta Http-Equiv="Pragma" Content="no-cache">
<meta Http-Equiv="Expires" Content="0">
<meta Http-Equiv="Pragma-directive: no-cache">
<meta Http-Equiv="Cache-directive: no-cache">

	<title> 
		Travel and Entertainment Search 
	</title>

<script type="text/javascript">
APIKey ='AIzaSyBDIaOeP0fVpdj3NiCOE-BfsZiIWn-rPoE';
function getLat(data) {

			long = data.lon;
			lat= data.lat;
            
            if (long && lat)
        	{
        		document.getElementById("searchid").disabled=false;
            }
}
function loadSearchBtn()
{
		
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("GET", "http://ip-api.com/json", true);
    	xmlhttp.send(null);

    	xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
            getLat(JSON.parse(xmlhttp.responseText));
        }
    }

}
function validate()
{
		var distance = document.getElementById('distanceid');
		var keyword = document.getElementById('keywordid');
		var 
		keywordvalue =keyword.value;

		console.log("keywordvalue " +keywordvalue);
		if(Number(distance.value) >31.06856)
		{
			alert(" Please enter below 31 miles");
			clearcontents();
			
		}




}
function clearcontents()
	{
		console.log("i hate u");
		document.getElementById('answers').innerHTML="";
		document.getElementById('reviews').innerHTML="";
		document.getElementById('reviewsList').innerHTML="";
		document.getElementById('photos').innerHTML="";
		document.getElementById('photosList').innerHTML="";
		document.getElementById('GeoResults').innerHTML="";
		document.getElementById('keywordid').value="";
		document.getElementById('distanceid').value="";

		document.getElementById("keywordid").value = "";
		document.getElementById('categoryid').getElementsByTagName('option')[0].selected = 'selected';
		document.getElementById("hereid").checked = true;
		document.getElementById("distanceid").value = "";
		document.getElementById("distanceid").placeholder="10";
		document.getElementById("placeid").disabled =true;
		document.getElementById("placeid").value = "";
		document.getElementById("placeid").placeholder="location";
		
		
	}
function onclicklocation(id)
{
			console.log("here");
			if(id=="here")
			{

					document.getElementById("placeid").disabled = true;
					document.getElementById("placeid").required = false;
					document.getElementById("placeid").value="";

			}
			else if(id=="place")
			{
					document.getElementById("placeid").disabled = false;
					document.getElementById("placeid").required = true;

			}
}
</script>
     
	<style>
.head {
	font-style:italic;
	text-align:center;

}
.linebreak {
	margin-left: 30px;
	margin-right: 30px;
}
.form{
	
	border: medium solid gray;
	margin : 100px 300px 0px 150px;
	margin-top:100px;
	margin-left:300px;
	background-color:#f9f9f9;
}
.form-class{

	margin-left:10px;
}
.form-class .category{
	margin-top: 10px;
}
.form-class .from{
	margin-left: 311px;
	margin-top: 10px;
}
.form-class .search{
	margin-left: 100px;
	margin-top: 10px;
}
.form-class .roundbtn{
	

}
.reviews{
	margin-left: 580px;
	margin-top: 20px;
}
.list{
	margin-left: 630px;
}
.head{

	margin-left: 10px;
}

.directions{
        border: none;
        color: black;
        top:30px;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;

}
.directions:active{
          background-color: #aaafb7;

}
.tooltip {
    position: relative;
   
     /* If you want dots under the hoverable text */
}
.tooltip .tooltiptext {
	position :absolute;
    width: 120px;
    color:#fff;
    text-align:center;
    border-radius: 2px;
    height :400px;
    width:500px;
    top: 100%;
    left: 0%; 
     z-index: 1;
   /* margin-left: -60px;*/ /* Use half of the width (120/2 = 60), to center the tooltip */
    visibility: hidden;
}
/*.map {

	 position: absolute;
	
}*/
.floating-panel {
        position: absolute;

        top: 110%;
        left: 0%;
        z-index: 5;
       	background-color:#EFF0F1;
        padding: 0px;
        border: 1px solid #999;
        text-align: left;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
      	 visibility: hidden;
 }
table, th, td {
   border: 2px solid gray;
   border-collapse: collapse;
}

label {
	font-weight: bold;
}
</style>
</head>
<body onload="loadSearchBtn()" >
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form" id="myform">
		<h1 class="head"> Travel and Entertainment Search </h1>
		<hr class="linebreak" />
		<div class="form-class">
		<label for="keyword" > Keyword</label>
		<input name="keyword" required id="keywordid"  value="<?php echo isset($keyword)?$keyword:'';?>" />  
	</br/>
		<label for="category" > Category </label>

		<select name="category" class="category" id="categoryid">
			<option  value="default" default selected> default</option>
		<option  value="cafe" <?php if(isset($category) && $category == 'cafe') echo ' selected="selected"';?>> cafe</option>
		<option  value="bakery"  <?php if(isset($category) && $category == 'bakery') echo ' selected="selected"';?>> bakery</option>
		<option  value="restuarant" <?php if(isset($category) && $category == 'restuarant') echo ' selected="selected"';?>  >restuarant</option>
		<option  value="beauty_salon" <?php if(isset($category) && $category == 'beauty_salon') echo ' selected="selected"';?>> beauty salon</option>
		<option  value="casino" <?php if(isset($category) && $category == 'casino') echo ' selected="selected"';?>>casino</option>
		<option  value="movie_theatre" <?php if(isset($category) && $category == 'movie_theatre') echo ' selected="selected"';?>>movie theatre</option>
		<option  value="lodging" <?php if(isset($category) && $category == 'lodging') echo ' selected="selected"';?>>lodging</option>
		<option  value="airport" <?php if(isset($category) && $category == 'airport') echo ' selected="selected"';?>>airport</option>
		<option  value="train_station" <?php if(isset($category) && $category == 'train_station') echo ' selected="selected"';?>>train station</option>
		<option  value="subway_station" <?php if(isset($category) && $category == 'subway_station') echo ' selected="selected"';?>>subway station</option>
		<option  value="bus_station" <?php if(isset($category) && $category == 'bus_station') echo ' selected="selected"';?>>bus station</option>

		</select>
		<br/>
		<label for="distance"> Distance(miles) </label>
		<input class="category" name="distance" id="distanceid"  value="<?php  if (isset($distance_miles)){ echo $distance_miles; } ?>" type="text" placeholder="10" > 
		<label for="from" > from </label>

			<input type="radio" name="from" id="hereid"  <?php echo empty($location) ? ' checked="checked" ' :'' ; ?>  onclick="onclicklocation('here');" /> <label> Here </label>
			<br/>
			<input type="radio" name="from" id="customplace" <?php echo empty($location) ? '' : ' checked="checked" '; ?>   onclick="onclicklocation('place');" class="from" />
			<input name="place" id="placeid" type="text" <?php echo empty($location) ? ' disabled="disabled" ' :'' ; ?> value="<?php echo isset($location)?$location:'';?>" placeholder="location" /> 
			<br/>
		<input type="submit" name="search" id="searchid" class="search roundbtn" disabled value="search" onclick="validate();"  />
		<input type="button" value="clear" onclick="clearcontents()" />
			<br/>
			<br/>
		</div>

	</form>

	<div id="answers" class="reviews head "> </div>
<table id="GeoResults" class="GeoResults"  style="border: 2px solid gray;margin-left: 90px;margin-top: 50px;text-align:center;padding:3px; width:1200px; border-collapse: collapse;"> 

	</table>
	<div id="reviews" onclick="clickReviews();" class="reviews list"></div>
	<div id="reviewsList" > </div>
	<div id="photos" onclick="clickReviewPhotos();" class="reviews list"></div>
	<div id="photosList" > </div>
</body>
<script>
tabresults = <?php echo $table_results ;?> ;
		if(tabresults["results"] !=null && tabresults["results"].length > 0)
		{
			document.getElementById('answers').innerHTML="";
			table_element = document.getElementById('GeoResults');
			
			table_contents =" <tr><th>Category</th><th>Name</th><th>Address</th></tr>";
			
				for(var i=0;i< tabresults["results"].length; i++)
				{
							table_contents = table_contents + "<tr><td style='text-align:left;padding-left: 16px;'><img src='"
							+tabresults["results"][i].icon+"'/></td><td style='text-align:left;padding-left: 16px;' onclick='clickOnName(\""
							+tabresults["results"][i].place_id+"\")'>"
+tabresults["results"][i].name+"</td><td style='text-align:left;padding-left: 16px;' class='tooltip'> <div onclick='gotoMap("+ tabresults["results"][i].geometry.location.lat +","+tabresults["results"][i].geometry.location.lng+ "," +i +")' >" +tabresults["results"][i].vicinity+"</div> <div class='tooltiptext' id='map"+i+"'> </div>"  
+ "<div class='floating-panel' id=\"floating-panel"+i+"\"> <table ><tr> <td id='walking" +i +"' class='directions' value='WALKING'>Walking</td> </tr> <tr> <td id='driving" +i +"' " +"class='directions' value='DRIVING'>Driving</td></tr> <tr>  <td id='bicycling" +i +"' class='directions' value='BICYCLING'>Bicycling</td> </tr></table> <div>" 
    					+"</td></tr>"  ;
				}	
			table_element.innerHTML= table_contents;
		}
		else if (tabresults["1"]==null )
		{
			table_element = document.getElementById('answers');
			table_element.innerHTML = " <br/> <br/> <table width='910' style='background-color:gray; margin-left:250px; border:2px solid black;' > <tr><th>No Results found</th></tr> </table> "
			
		}


	function clickReviews()
	{
				var reviewsList = document.getElementById("reviewsList");
				if(reviewsList.style.display =="none")
				{
					document.getElementById("reviews").innerHTML = "<p>click to hide reviews</p><img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png'>";
	        		reviewsList.style.display = "block";

	        		document.getElementById("photos").innerHTML = "<p>click to show photos</p><img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png'>";
	        		document.getElementById("photosList").style.display="none";

				}
				else if(reviewsList.style.display =="block")
				{
						document.getElementById("reviews").innerHTML = "<p>click to show reviews</p><img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png'>";
	        		reviewsList.style.display = "none";

				}
	}
	function clickReviewPhotos()
	{
			var reviewsList = document.getElementById("photosList");
				if(reviewsList.style.display =="none")
				{
					document.getElementById("photos").innerHTML = "<p>click to hide photos</p><img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_up.png'>";
	        		reviewsList.style.display = "block";
	        		document.getElementById("reviews").innerHTML = "<p>click to show reviews</p><img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png'>";

	        		document.getElementById("reviewsList").style.display="none";
				}
				else if(reviewsList.style.display =="block")
				{
						document.getElementById("photos").innerHTML = "<p>click to show photos</p><img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png'>";
	        		reviewsList.style.display = "none";

				}
	}

	function clickOnName(placeid){
		
		var xhr = new XMLHttpRequest();
		xhr.open("GET", "place.php?placeid="+placeid, true);
		
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send();
		xhr.onreadystatechange = function() {
			try{
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200 && xhr.responseText != null) {
			
				document.getElementById('reviews').innerHTML = "<p>click to show reviews</p> <img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png'>";
				document.getElementById('photos').innerHTML = "<p>click to show photos </p> <img style='width:30px;height:20px;margin-left: 52px;' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png'>";
				console.log("responsetext " +xhr.responseText);
				 responseText = JSON.parse(xhr.responseText);
				
				document.getElementById('GeoResults').innerHTML ="";
				document.getElementById('answers').innerHTML = " <strong> " + responseText["result"]["name"] +"</strong>";
				var photos =" <table border='2px' style='margin-left: 300px;margin-top: 10px;text-align:center;padding:3px;width:805px;height:610px;' > ";
				var reviews ="<table border='2px' style='margin-left:300px;margin-top:10px;text-align:center;padding:3px;width:805px;height:610px';>  ";

				if(responseText["result"]!=null && ("photos" in responseText["result"]) && (responseText["result"]["photos"].length>=1))
				{
					photoslength =responseText["result"]["photos"].length;
					if (photoslength>5)
						photoslength=5;
					for(var i =0;i<photoslength ;i++)
					{
							photos +=   "<tr><td>  <image onclick='window.open(this.src)' style='height:600px;width:600px;padding:5px;' src='" +i+".jpg' > </td></tr>";
					}
					photos+="</table>";
					document.getElementById("photosList").innerHTML = photos;
					document.getElementById("photosList").style.display = "none";
				}
				else
				{
					var nophotoes = "<table style='border: 1px solid black;margin-left: 300px;margin-top: 10px;text-align:center;padding:3px;width:805px;'>";
					nophotoes = nophotoes + "<tr><td style='text-align:center'><strong>No photos found</strong></td></tr></table>";
					document.getElementById("photosList").innerHTML = nophotoes;
				}
				if(responseText["result"]!=null && ("reviews" in responseText["result"]) && (responseText["result"]["reviews"].length>=1))
				{
					for(var i =0;i<responseText["result"]["reviews"].length ;i++)
					{
							reviews +=   "<tr><td>  <img style='width:40px;height:40px' src='"
						+responseText["result"]["reviews"][i]["profile_photo_url"]+"'/><strong>"+responseText["result"]["reviews"][i]["author_name"]
						+"</strong></td></tr><tr><td style='text-align:left'>" +responseText["result"]["reviews"][i]["text"]+"</td></tr>";;
					}
					reviews+="</table>";
					document.getElementById("reviewsList").innerHTML = reviews;
					document.getElementById("reviewsList").style.display = "none";
				}
				else
				{
					var noreviews = "<table style='border: 1px solid black;margin-left: 300px;margin-top: 10px;text-align:center;padding:3px;width:805px;'>";
					noreviews = noreviews + "<tr><td style='text-align:center'><strong>No reviews found</strong></td></tr></table>";
					document.getElementById("reviewsList").innerHTML = noreviews;
				}
			}
		}
			catch(err)
			{
				console.log(" error" +err);
				document.getElementById('GeoResults').innerHTML ="";
				document.getElementById('answers').innerHTML = " <strong> " + responseText["result"]["name"] +"</strong>";
				var noreviews = "<table style='border: 1px solid black;margin-left: 300px;margin-top: 10px;text-align:center;padding:3px;width:805px;'>";
				noreviews = noreviews + "<tr><td style='text-align:center'><strong>No reviews found</strong></td></tr></table>";
				document.getElementById("reviewsList").innerHTML = noreviews;
				document.getElementById("reviewsList").style.display = "none";
				var nophotoes = "<table style='border: 1px solid black;margin-left: 300px;margin-top: 10px;text-align:center;padding:3px;width:805px;'>";
				nophotoes = nophotoes + "<tr><td style='text-align:center'><strong>No photos found</strong></td></tr></table>";
				document.getElementById("photosList").innerHTML = nophotoes;
				document.getElementById("photosList").style.display = "none";
			}
		}
	}
	
	function gotoMap(destinationLat ,destinationLong,index ) {
		
		var map = 'map'+index;
		var floating_panel ='floating-panel'+index;
		
		if( document.getElementById(map).style.visibility=='hidden' && document.getElementById(floating_panel).style.visibility=='hidden') 
		{
				document.getElementById(map).style.visibility='visible';
				document.getElementById(floating_panel).style.visibility='visible';
		}
		else
		{
			document.getElementById(map).style.visibility='hidden';
			document.getElementById(floating_panel).style.visibility='hidden';
		}


		initMap(destinationLat ,destinationLong,index );
	}
      function initMap(destinationLat ,destinationLong,index ) {
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService;

        var map = new google.maps.Map(document.getElementById('map'+index), {
          zoom: 14,
          center: {lat: destinationLat, lng: destinationLong}
        });
         marker = new google.maps.Marker({
         
          position: {lat: destinationLat, lng:  destinationLong},
          map: map
        });
        directionsDisplay.setMap(map);
        document.getElementById('driving'+index).addEventListener('click', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay, "DRIVING",destinationLat ,destinationLong);
        });
         document.getElementById('walking'+index).addEventListener('click', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay,"WALKING",destinationLat ,destinationLong);
        });
          document.getElementById('bicycling'+index).addEventListener('click', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay,"BICYCLING",destinationLat ,destinationLong);
        });
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay,mode,destinationLat ,destinationLong) {

      	marker.setMap(null);

        var selectedMode = mode;
        
         originLat = <?php echo $latitude;?>;
        originLng =<?php echo $longitude;?>;
      	console.log (" origin" +originLat);
        directionsService.route({
          origin: {lat:  originLat, lng: originLng},   // my address
          destination: {lat: destinationLat, lng:destinationLong},  // my destination
         
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
</script>
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUr_UWIE-xhUEkUf91oA4w1Rkk4JvtnJc">  </script>
</html>