
<style>

html, body{
	height: 100%;
	margin: 0px;
	padding: 0px
}

#map-canvas {
	height: 50%;
	/*width:75%;*/
	margin-left:auto;
	margin-right:auto;
}

</style>

   <!--<script>
		function loadScript() {
		  var script = document.createElement('script');
		  script.type = 'text/javascript';
		  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
		      'callback=initialize';
		  document.body.appendChild(script);
		}

		window.onload = loadScript;
	</script>-->
	<!--Slider-->
	<script type='text/javascript' src='js/slider/jshashtable-2.1_src.js'></script>
	<script type='text/javascript' src='js/slider/jquery.numberformatter-1.2.3.js'></script>
	<script type='text/javascript' src='js/slider/tmpl.js'></script>
	<script type='text/javascript' src='js/slider/jquery.dependClass-0.1.js'></script>
	<script type='text/javascript' src='js/slider/draggable-0.1.js'></script>
	<script type='text/javascript' src='js/slider/jquery.slider.js'></script> 

	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
	<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp">
</script>-->

<script>
var max = 10;
var image2='';
var image_gal = '';
var left = 0;
var right = 2;
var daftar_foto = [];
var id_gambar_gal = [];
var limit_right=0;
var map="";

function show_map(str){
	var mapOptions = {
		zoom: 20,
		center: new google.maps.LatLng(-6.917114, 107.609662)
	};

	map = new google.maps.Map(document.getElementById(str),mapOptions);
}

	function initialize(){
			//alert("showing map");
			show_map('map-canvas');
		var image = {
			url: 'images/icon/marker.png',
			scaledSize: new google.maps.Size(30, 45)
		};
		var marker = new google.maps.Marker({
			position: map.getCenter(),
			icon: image,
			map: map,
			title: 'Drag Me!',
			draggable:true
		});
		$('#location_location').val(marker.getPosition());
		setMarkers(map,lokasi);
		google.maps.event.addListener(map, 'click', function (e) {
			var latLng = e.latLng;
			marker.setPosition(latLng);
			$('#location_location').val(marker.getPosition());
		});
		google.maps.event.addListener(marker, 'mouseup', function() {
			$('#location_location').val(marker.getPosition());
		});
	}
	
	function reinitialize(lang, lat){
		  show_map('map-canvas');
		  
		  map.setCenter(new google.maps.LatLng(lang, lat));
		  var image = {
		  	url: 'images/icon/marker.png',
			// This marker is 20 pixels wide by 32 pixels tall.
			scaledSize: new google.maps.Size(30, 45)
		};
		var marker = new google.maps.Marker({
			position: map.getCenter(),
			icon: image,
			map: map,
			title: 'Drag Me!',
			draggable:true
		});
		$('#location_location').val(marker.getPosition());
		setMarkers(map,lokasi);
		google.maps.event.addListener(map, 'click', function (e) {
			var latLng = e.latLng;
			marker.setPosition(latLng);
			$('#location_location').val(marker.getPosition());
		});
		google.maps.event.addListener(marker, 'mouseup', function() {
			$('#location_location').val(marker.getPosition());
		});
	}
	
	function setMarkers(map, locations) {
		for (var i = 0; i < locations.length; i++) {
			var beach = locations[i].substring(1, locations[i].length-1);
			var arr = beach.split(',');
			var myLatLng = new google.maps.LatLng(arr[0], arr[1]);
			var spot = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: nama[i]
			});
			bindListener(spot,map,id_tempat[i],locations[i],gambar[i],nama[i],daynight[i],alamat[i],telepon[i],harga[i],website[i],email[i],fitur[i],masakan[i],keanggotaan[i],kota[i]);
		}
	}
		
	function bindListener(marker, map,id,loc,gambar, name, day, address,phone,price,web,email,fitur,masakan,anggota,kota) {
		google.maps.event.addListener(marker, 'click', function(){
			$('#store_id').val(id);
			$('#store_location').val(loc);
			$('#pop_up_image').attr('src',gambar);
			$('#pop_up_image').attr('width',200);
			$('#pop_up_image').attr('height',100);
			$('#store_name').val(name);
			$('#store_day').val(day);
			$('#address').val(address);
			$('#phone_number').val(phone);
			$('#store_website').val(web);
			$('#store_email').val(email);
			$('#store_cuisine').val(masakan);
			$('#store_city').val(kota);
			if(anggota==1){
				document.getElementById('store_pay').checked = true;
			}
			else{
				document.getElementById('store_pay').checked = false;
			}
			var arr_harga = price.split(' ');
			var low = 0;
			var high = 500000;
			if(arr_harga[0] == 'Below'){
				high = arr_harga[1];
			}
			else if(arr_harga[0] == 'Above'){
				low = arr_harga[1];
			}
			else{
				arr_harga = price.split('-');
				low = arr_harga[0];
				high = arr_harga[1];
			}
			
			for(var i=0;i<fitur.length;i++){
				$('#store_'+fitur[i]).prop('checked',true);
			}
			
			var current = $('.store_preferensi').filter(':checked').length;
			$('.store_preferensi').filter(':not(:checked)').prop('disabled', current >= max);
			
			
			//galery
			
			getGallery(id);		
			
			//end of galery
			
			$( '.pu_00' ).fadeIn( 277, function(){});
			left = 0;
			right = 2;
			$('#store_price').slider('value',low,high);
		});
}
		</script>

		<script>
		var id_tempat = [];
		var gambar = [];
		var nama = [];
		var daynight = [];
		var lokasi = [];
		var alamat = [];
		var telepon = [];
		var harga = [];
		var website = [];
		var email = [];
		var fitur = [];
		var masakan = [];
		var keanggotaan = [];
		var kota = [];
		var gambar_gallery;
		
		
		function getGallery(id){
			document.getElementById('gallery').innerHTML ="";
			$.ajax({
				type: 'GET',
				contentType: 'application/json',
				url: 'http://milesyourday.com/service/gallery/'+id,
				dataType: 'json',
				success: function(data){
					//gambar_gallery =  data;
					if(data.status == 'error'){
						return ;
					}
					daftar_foto = [];
					var gal_count = 0;
					$(data).each(function(index,value){
						//alert(value.photo);
						daftar_foto[gal_count] = encodeURI(value.photo);
						id_gambar_gal[gal_count] = value.id;
						gal_count++;
					});
					var divs ='';
					limit_right = daftar_foto.length-1;
					if(limit_right <= right){
						right = limit_right;
					}
					for(var i=left;i<=right;i++){
						
						divs+= '<div class="gallery_image" style="display:inline-block;position:relative;width:190px;">';
						divs+= '<span class=close_16></span>';
						divs+= '<input type=hidden value ='+id_gambar_gal[i]+'>';
						divs+= '<img src='+daftar_foto[i]+' alt="Image Not Found" width="190" height="150" />';
						divs+= '</div>';
					}
					document.getElementById('gallery').innerHTML = divs;
					
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
		}

	function getLocation(){
		$.ajax({
			type: 'GET',
			contentType: 'application/json',
			url: 'http://milesyourday.com/service/place',
			dataType: 'json',
			success: function(data){
				var counter2 = 0;
				if(data.length > 0){
					$(data).each(function(index,value){
						id_tempat[counter2] = value.place.id;
						gambar[counter2] = value.place.photo;
						nama[counter2]=value.place.name;
						daynight[counter2]=value.place.day_life;
						lokasi[counter2]=value.place.location;
						alamat[counter2]=value.place.address;
						telepon[counter2]=value.place.telp;
						harga[counter2]=value.price;
						website[counter2]=value.place.website;
						email[counter2]= value.place.email;
						masakan[counter2] = value.cuisine;
						keanggotaan[counter2] = value.membership;
						fitur[counter2] =value.feature;
						kota[counter2] = value.place.city;
						counter2++;
					});
				}
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			},
			complete:function(){
				initialize();
			}
		});
	}

	function reGetLocation(lang,lat){
		$.ajax({
			type: 'GET',
			contentType: 'application/json',
			url: 'http://milesyourday.com/service/place',
			dataType: 'json',
			success: function(data){
				var counter2 = 0;
				if(data.length > 0){
					$(data).each(function(index,value){
						id_tempat[counter2] = value.place.id;
						gambar[counter2] = value.place.photo;
						nama[counter2]=value.place.name;
						daynight[counter2]=value.place.day_life;
						lokasi[counter2]=value.place.location;
						alamat[counter2]=value.place.address;
						telepon[counter2]=value.place.telp;
						harga[counter2]=value.price;
						website[counter2]=value.place.website;
						email[counter2]= value.place.email;
						masakan[counter2] = value.cuisine;
						keanggotaan[counter2] = value.membership;
						fitur[counter2] =value.feature;
						kota[counter2] = value.place.city;
						counter2++;
					});
				}
					//google.maps.event.addDomListener(window, 'load', initialize);
					reinitialize(lang,lat);
					$('.lazy').css('display','none');
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
	}


	$(document).ready(function(){
		//setTimeout(getLocation,1);
		getLocation();
			//alert(lokasi.length);
	});

</script>

</head>
<body>
	
	<div id='map-canvas' class='map_height'>
		
	</div>
	<script type='text/javascript'>
	
	function updateSize(){
		// Get the dimensions of the viewport
		var width = $(window).width();
		var height = $(window).height();
		
		var tambah_lokasi = $('.tambah_lokasi').height();
		var tambah_lokasi_70 = tambah_lokasi + 70;
		$('.map_height').height(height - tambah_lokasi_70);
		//alert(tambah_lokasi_70);
	};
	$(document).ready(
		updateSize
		);
	$(window).resize(updateSize); 
	
	</script>


	<div id='main' class='tambah_lokasi'>
		<!--<h1>Upload Your Images</h1>-->
		<form method='post' enctype='multipart/form-data' action='php/upload.php' id='form_add_location' class="form-horizontal">
			<div class="container-fluid">
				<div class='form_1 g-lg-3'>
					<div class="form-group">
						<label class="g-sm-4 control-label">Image</label>
						<div class="g-sm-8">
							<input type='file' name='images' id='images'/>			
						</div>
					</div>
					<div id='response'></div>
					<ul id='image-list'>

					</ul>
				</div>
				<div class='form_1 g-lg-6'>
					<div class="row">
						<div class="g-lg-6">
							<div class="form-group">
								<label class="g-sm-4 control-label">Name</label>
								<div class="g-sm-8">
									<input type='text' id='location_name' class="form-control">
									<input type='hidden' id='location_location' />			
								</div>
							</div>
							<div class="form-group">
								<label class="g-sm-4 control-label">Day-life</label>
								<div class="g-sm-8">
									<select id='location_day' class="form-control">
										<option value='0'>Day</option>
										<option value='1'>Night</option>
										<option value='2'>Day-Night</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="g-sm-4 control-label">Address</label>
								<div class="g-sm-8">
									<input type='text' id='location_address' class="form-control">
									<input type='hidden' id='location_location' />			
								</div>
							</div>
							<div class="form-group">
								<label class="g-sm-4 control-label">City</label>
								<div class="g-sm-8">
									<input type='text' id='location_city' class="form-control">
									<input type='hidden' id='location_location' />			
								</div>
							</div>
						</div>

						<div class="g-lg-6">
							<div class="form-group">
								<label class="g-sm-4 control-label">Phone</label>
								<div class="g-sm-8">
									<input type='text' id='location_phone' class="form-control">
									<input type='hidden' id='location_location' />			
								</div>
							</div>
							<div class="form-group">
								<label class="g-sm-4 control-label">Website</label>
								<div class="g-sm-8">
									<input type='url' id='location_website' class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="g-sm-4 control-label">E-mail</label>
								<div class="g-sm-8">
									<input type='email' name='email' id='location_email' class="form-control">		
								</div>
							</div>
							<div class="form-group">
								<label class="g-sm-4 control-label">Cuisine</label>
								<div class="g-sm-8">
									<select id='location_cuisine' class="form-control">
										<option value='0'>Western</option>
										<option value='1'>Eastern</option>
										<option value='2'>Western-Eastern</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="g-sm-2 control-label">Preferensi</label>
							<div class="g-sm-10">
								<label class="checkbox-inline" style="margin-left: 10px;">
									<input type='checkbox' class='preferensi' id='wine' value='wine'>Wine
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='beer' value='beer'>Beer
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='liquor' value='liquor'>Liquor
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='coffee' value='coffee'>Coffee
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='wifi' value='wifi'>Wifi
								</label>

								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='breakfast' value='breakfast'>Breakfast
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='hightea' value='hightea'>High Tea
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='eatery' value='eatery'>Eatery
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='nightonly' value='nightonly'>Night Only
								</label>

								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='club' value='club'>Club
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='fastfood' value='fastfood'>Fast Food
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='vegan' value='vegan'>Vegan
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='streetfood' value='streetfood'>Streetfood
								</label>

								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='dessert' value='dessert'>Dessert
								</label>
								<label class="checkbox-inline">
									<input type='checkbox' class='preferensi' id='patisserie' value='patisserie' disabled>Patisserie
								</label>

							</div>
						</div>
					</div>







				</div>
				<div class='form_1 g-lg-3'>

					<div class="form-group">
						<label class="g-sm-4 control-label">Price Range</label>
						<label class="g-sm-8 control-label">&nbsp;</label>
						<div class="g-sm-12">
							<div class='layout-slider' style="margin-top: 20px;">
								<input class='slider_price' id='location_price' type='slider' name='location_area' value='0;250000' />
							</div>		
						</div>
					</div> 
						<div class="form-group" style="margin-top: 85px;">
							<label class="g-sm-6 control-label">Type</label>
							<label class="checkbox-inline g-sm-6">
								<input type='checkbox' id='pay' value='paid'> Pay
							</label>
						</div>
						
						<script type='text/javascript' charset='utf-8'>
						jQuery('#location_price').slider({ 
							from: 0, 
							to: 500000,
							heterogeneity: ['0/0','60/300000'],
							scale: [0,'|',100000,'|',200000,'|',300000,'|',400000,'|',500000],
							limits: false,
							step: 1000,
							dimension: '',
							skin: 'blue',
							callback: function( value ){ console.dir( this ); } });
						</script>


						<div class="form-group" style="">
							<label class="g-sm-4 control-label">&nbsp;</label>
							<label class="checkbox-inline g-sm-8">
								<!--<button type='submit' id='btn'>Upload Files!</button>-->
								<input type='reset' id='submit_button' value='Submit' class="btn btn-success"/>
							</label>
						</div>
					</div>
					
					<!--<input type='button' id='logout_button' value ='Log Out' />-->
				</div>
			</form>

		</div>

		<!--<div class="modal fade pop_up_super_c_hapus_berkas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						Perhatian!
					</div>-->


					<!-- div pop-up -->
					<div class='modal pu_00 pop_up_super_c lazy' style='display:none;'  tabindex="-1" role="dialog">
						<a class='exit sprt close_56'></a>
						<div class='pop_up_tbl'>
									<div class='pop_up_cell'>
						<div class="modal-dialog modal-lg">
							<div class='modal-content'>
								

										<div class='container-fluid'>
											<div class='g-lg-12 radius_5' style='background: #fff; padfing-top: 20px; padding-bottom: 20px;'>
												<input type='hidden' id='store_id' value='' />
												<input type='hidden' id='store_location' value='' />

												<div id='navigator-left' class='arrow_left_def sprt' style='display:inline-block; cursor:pointer; float: left; margin-top: 59px; margin-left: -10px;'></div>
												<div id='navigator-right' class='arrow_right_def sprt' style='display:inline-block; cursor:pointer; float:right; margin-top: 59px; margin-right: -10px;'></div>
												<div class = 'div_gallery' style='overflow: hidden;'>
													<div class ='gallery' id='gallery' style='display:inline; width:auto; height:auto; overflow-x: hidden;'>
													</div>
													
													
													<script>
													$('#navigator-left').click(function(){
														if(left>0){
															left-=1;
															right-=1;
															divs='';
															for(var i=left;i<=right;i++){
																divs+= '<div class="gallery_image" style="display:inline-block;position:relative;width:190px;">';
																divs+= '<span class=close_16></span>';
																divs+= '<input type=hidden value ='+id_gambar_gal[i]+'>';
																divs+= '<img src='+daftar_foto[i]+' width="190" height="150" />';
																divs+= '</div>';
															}
															document.getElementById('gallery').innerHTML = divs;
														}
													});
													
													
													$('#navigator-right').click(function(){
														if(right<limit_right){
															left+=1;
															right+=1;
															divs='';
															for(var i=left;i<=right;i++){
																divs+= '<div class="gallery_image" style="display:inline-block;position:relative;width:190px;" >';
																divs+= '<span class=close_16></span>';
																divs+= '<input type=hidden value ='+id_gambar_gal[i]+'>';
																divs+= '<img src='+daftar_foto[i]+' width="190" height="150" />';
																divs+= '</div>';
															}
															document.getElementById('gallery').innerHTML = divs;
														}
													});
													</script>
													
												</div>
												<span class='clear' style='height: 10px;'></span>

												<div style='width:60%; display:block; float:left;' class="form-horizontal">

													
														<div class="form-group">
															<label class=" control-label g-sm-3">Store Name</label>
															<div class=" g-sm-7">
																<input type='text' id='store_name' class="form-control g-sm-5"/>
															</div>
														</div>
														
														<div class="form-group">
															<label class=" control-label g-sm-3">Day Life</label>
															<div class=" g-sm-7">
																<select id='store_day' class="form-control">
																	<option value='0'>Day</option>
																	<option value='1'>Night</option>
																	<option value='2'>Day-Night</option>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class=" control-label g-sm-3">Address</label>
															<div class=" g-sm-7">
																<input type='text' id='address' class="form-control g-sm-5"/>
															</div>
														</div>

														<div class="form-group">
															<label class=" control-label g-sm-3">City</label>
															<div class=" g-sm-7">
																<input type='text' id='store_city' class="form-control g-sm-5"/>
															</div>
														</div>

														<div class="form-group">
															<label class=" control-label g-sm-3">Phone</label>
															<div class=" g-sm-7">
																<input type='text' id='phone_number' class="form-control g-sm-5"/>
															</div>
														</div>

														<div class="form-group">
															<label class=" control-label g-sm-3">Website</label>
															<div class=" g-sm-7">
																<input type='text' id='store_website' class="form-control g-sm-5"/>
															</div>
														</div>

														<div class="form-group">
															<label class=" control-label g-sm-3">E-mail</label>
															<div class=" g-sm-7">
																<input type='text' id='store_email' class="form-control g-sm-5"/>
															</div>
														</div>

														<div class="form-group">
															<label class=" control-label g-sm-3">Cuisine</label>
															<div class=" g-sm-7">
																<select id='store_cuisine' class="form-control">
																	<option value='0'>Western</option>
																	<option value='1'>Eastern</option>
																	<option value='2'>Western-Eastern</option>
																</select>
															</div>
														</div>


													

												</div>

												<div style='width:40%; display:block; float:left;'>

													<span style='display: block; font-weight: bold;'>
														Add Photo to Gallery
													</span>
													<input type='button' id='add_button' value='Choose File' />
													<input type='file' id='add_gallery' style='display:none;' />

													<span style='display: block; font-weight: bold; margin-top:20px;'>
														Change Profile Picture
													</span>
													<img id='pop_up_image' src='' width='190' height='150'/><br/>
													<input type='file' id='update_file' />
												</div>

												<div style='float: left;'>
													<style>
													.check_container {
														display: block;
														float: left;
														width: 100px;
													}
													</style>
													<label>Preferensi</label>
													<span class='clear' style='height: 10px;'></span>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_wine' value='wine'>Wine
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_beer' value='beer'>Beer
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_liquor' value='liquor'>Liquor
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_coffee' value='coffee'>Coffee
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_wifi' value='wifi'>Wifi
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_breakfast' value='breakfast'>Breakfast
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_hightea' value='hightea'>High Tea
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_eatery' value='eatery'>Eatery
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_nightonly' value='nightonly'>Night Only
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_club' value='club'>Club
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_fastfood' value='fastfood'>Fast Food
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_vegan' value='vegan'>Vegan
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_streetfood' value='streetfood'>Streetfood
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_dessert' value='dessert'>Dessert
													</div>
													<div class='check_container'>
														<input type='checkbox' class='store_preferensi' id='store_patisserie' value='patisserie'>Patisserie
													</div>
													<div class='check_container'>
														<input type='checkbox' id='store_pay' value='store_pay'>Pay
													</div>

												</div>

												<span class='clear' style='height: 10px;'></span>

												<span class='clear' style='height: 10px;'></span>
												<label>Price Range</label>
												<span class='clear' style='height: 10px;'></span>
												<span class='clear' style='height: 10px;'></span>
												<div class='layout-slider'>
													<input id='store_price' type='slider' name='area' value='0;0' />
													<script type='text/javascript' charset='utf-8'>
														jQuery('#store_price').slider({ 
															from: 0, 
															to: 500000,
															heterogeneity: ['0/0','60/300000'],
															scale: [0,'|',100000,'|',200000,'|',300000,'|',400000,'|',500000],
															limits: false,
															step: 1000,
															dimension: '',
															skin: 'blue',
															callback: function( value ){ console.dir( this ); } 
														});
													</script>
												</div>

												<input type='button' id='update_button' value='Confirm' class="btn btn-success" style="margin-top: 40px;"/>
												<input type='button' id='remove_button' value='Remove' class="btn btn-danger" style="margin-top: 40px;"/>

												<input type='button' id='update_location_trigger' value='Update Location' class="btn btn-primary" style='float: right;margin-top: 40px;'/>
												<script>
													$('#logout_button').click(function(){
														$.ajax({
															type: 'POST',
															url: 'php/logout.php',
															success: function(data){
																window.location.href = 'http://milesyourday.com';
															},
															error: function(jqXHR, textStatus, errorThrown){
																alert(errorThrown);
															}
														});
													});
												</script>
											</div>
										</div>	
									</div>
								</div>
							</div>
						</div>
					</div>


					<div id="update_location_pop" class="pop_up_super" style="display: none;">
			
						<a class='exit sprt close_56'></a> 
						<div class='pop_up_tbl'>
							<div class='pop_up_cell'> 
								<div class="c_12">
									<div class="g_12">
										<div id='update_location_canvas' style="display: block; height: 600px; width: 100%; background: #fff;">
											<!-- isi dengan js google map -->
										</div>
										<input type='hidden' id='update_new_location' />
										<input type="submit" id='set_location' class="" value="Set Location" style="width: 200px; margin-left: auto; margin-right: auto;"/>
									</div>
								</div>
							</div> 
						</div>
					</div>

					<div id="sesuatu" class="pop_up_super" style="display: none;">
						ewrwerwerw class="pu_00 pop_up_super lazy" 
					</div>

					<script src='js/upload/upload.js'></script>
					<script src='js/admin_panel/tambah_lokasi_gallery.js'></script>
					<script src='js/admin_panel/tambah_lokasi_pop_up.js'></script>

					<script type='text/javascript'>
					 
					$('body').on('click','#set_location',function(){
						var up_id = $('#store_id').val();
						var up_location = $('#update_new_location').val();
						var up_data = {
							location: up_location 
						};
						var up_input = JSON.stringify(up_data);
						
						$.ajax({
							url: 'http://milesyourday.com/service/updateLocation/'+up_id,
							type: 'PUT',
							data: up_input,
							success: function(response){
								alert('Update Location Success');
								var beach = up_location.substring(1, up_location.length-1);
								var arr = beach.split(',');
								reGetLocation(arr[0], arr[1]);
							},
							error: function(jqXHR, textStatus, errorThrown){
								alert(errorThrown);
							},
							complete: function(){
								$( '#update_location_pop' ).fadeOut( 200, function(){});
								$( '.pop_up_super_c' ).fadeOut( 200, function(){});
							}
						},'json');

					});
					
					$('.preferensi').change(function(){
						var current = $('.preferensi').filter(':checked').length;
						$('.preferensi').filter(':not(:checked)').prop('disabled', current >= max);
					});
					
					$('.store_preferensi').change(function(){
						var current = $('.store_preferensi').filter(':checked').length;
						$('.store_preferensi').filter(':not(:checked)').prop('disabled', current >= max);
					});
					
					$('.exit').click(function(){
						$( '.pop_up_super_c' ).fadeOut( 200, function(){});
						document.getElementById('gallery').innerHTML = '<div></div>'
					});	

					function show_pop() {
						$( '.pu_00' ).fadeIn( 277, function(){});
						left = 0;
						right = 2;
						;}

					$('body').on('click','#update_location_trigger',function (){
						$( '#update_location_pop' ).fadeIn( 200, function(){});
						var longlat = $('#store_location').val();
						var beach = longlat.substring(1, longlat.length-1);
						var arr = beach.split(',');
						var myLatLng = new google.maps.LatLng(arr[0], arr[1]);
						open_update_location(myLatLng);
					});
					
					function open_update_location(langlad){
						show_map('update_location_canvas');
						map.setCenter(langlad);
						var image = {
							url: 'images/icon/marker.png',
							scaledSize: new google.maps.Size(30, 45)
						};
						var marker = new google.maps.Marker({
							position: langlad,
							icon: image,
							map: map,
							title: 'Drag Me!',
							draggable:true
						});
						
						$('#update_new_location').val(marker.getPosition());
						
						google.maps.event.addListener(map, 'click', function (e) {
							var latLng = e.latLng;
							marker.setPosition(latLng);$('#update_new_location').val(marker.getPosition());
						});
						google.maps.event.addListener(marker, 'mouseup', function() {
							$('#update_new_location').val(marker.getPosition());
						});
					}

					$('.pop_up_super_c').click(function (e)
					{
						var container = $('.pop_up_cell');
						if (container.is(e.target) )// if the target of the click is the container...
						{
							$( '.pop_up_super_c' ).fadeOut( 200, function(){});
							$('html').css('overflow-y', 'auto');
							document.getElementById('gallery').innerHTML = '<div></div>'
						}
					});	

						$('.pop_up_super').click(function (e)
						{
							var container = $('.pop_up_cell');
							if (container.is(e.target) )// if the target of the click is the container...
							{
								$( '.pop_up_super' ).fadeOut( 200, function(){});
								$('html').css('overflow-y', 'auto');
								document.getElementById('gallery').innerHTML = '<div></div>'
							}
						});
						</script>

						<script>

						$( 'body' ).on( "click",'#hyperion', function() {
							//var tambah_lokasi_cur = $('.tambah_lokasi').height();
							$('.tambah_lokasi').css('height','0px').css('display','none');
							
							$('.map_height').height($(window).height()-70);
						});



						</script>
