<link rel="stylesheet" type="text/css" href="/js/datetimepicker/jquery.datetimepicker.css"/ >
<script src="/js/datetimepicker/jquery.js"></script>
<script src="/js/datetimepicker/jquery.datetimepicker.js"></script>

<script>
	var location_alphabet = true;
	var event_image="";
	var images = [];
	var images_source =[];
	var count_source =0;
	var count = 0;
	var left =0;
	var right = 2;
	
	function initialize_event(){
		var mapOptions = {
			zoom: 20,
			center: new google.maps.LatLng(-6.917114, 107.609662)
		};

		var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
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
		//$('#location_location').val(marker.getPosition());
		//setMarkers(map,lokasi);
		google.maps.event.addListener(map, 'click', function (e){
			var latLng = e.latLng;
			marker.setPosition(latLng);
		});
		google.maps.event.addListener(marker, 'mouseup', function() {
			//$('#location_location').val(marker.getPosition());
			$('#event_location').val(marker.getPosition());
			$('#event_location_hidden').val(marker.getPosition());
		 });
	}
	
	function refresh_gallery(){
			var inner_gallery="";
			if(count_source<=2){
				for($i=0;$i<count_source;$i++){
					inner_gallery+="<div>";
					inner_gallery+="<img style='width:120px;height:152px;font-size:32px; color:#FFF;' src='"+images_source[$i]+"' alt=''></div>";
				}
			}
			else{
				
				for($i=left;$i<images_source.length;$i++){
					inner_gallery+="<div>";
					inner_gallery+="<img style='width:120px;height:152px;font-size:32px; color:#FFF;' src='"+images_source[$i]+"' alt=''></div>";
				}
				left++;
				right++;
			}
			$('#gallery').html(inner_gallery);
		}
</script>

<div class='c_12'>
	<div class='g_12 t_48 mt_30'>
	Add Event
	</div>
	
	<div class='push_3 g_3'>
		<div class='add_event_label'>
			Add New Event
		</div>
		
	</div>
	<div class='push_3 g_3'>
		<a id="add_event_pop_trigger" class='add_event_value' href="javascript:void(0)">
			+
		</a>
		
	</div>
	<div class='seperate_line g_12'>
	</div>
	<div class='g_12 t_48 mt_30'>
	User List
	</div>
	<div class='g_12'>
		<ul>
		
			<li class='user_node'>
				<img src='./images/dum_pic.png' alt='' width='100'/>
				<div class="event_info">
					<div class='event_list_node'>
					Nama Event　エヴェントオナマエ
					</div>
					<div class='event_list_node'>
					22.59 - 13.59 31/12/2077
					</div>
				</div>
				<div class="event_info">
					<div class='event_list_node'>
					Nama Cafe　おっぱいですね
					</div>
					<div class='event_list_node'>
					Alamat Cafe　おっぱいですね
					</div>
				</div>
				
				
				<button class='choose_cafe_but'>
				Delete
				</button>
			</li>
			
			
		</ul>
	</div>
</div>

<div class='pu_00 pop_up_super lazy' id="pop_up_super_event" style="display: none;">
		<a class='exit sprt close_56'></a> 
		<div class='pop_up_tbl'>
			<div class='pop_up_cell'> 
				<div class="c_12">
					<div class="g_10">
						<form id='new_event_form'>
							<div class="form_node_add_event">
								<label>Event Name</label>
								<input type="text" id='event_name' name='name' class="" style=""/>
							</div>
							
							<div class="form_node_add_event">
								<label>Location</label>
								<input type="text" name='location' id='event_location' class="" style=""/>
								<input type='hidden' id='event_location_hidden' />
								<div id="livesearch"></div>
								<a class="button_pop" id="add_map_pop_trigger" href="javascript:void(0)"></a>
							</div>
							<div class="form_node_add_event">
								<label>Address</label>
								<input type="text" name='address' id='event_address' class="" style=""/>
							</div>
							<div class="form_node_add_event">
								<label>Date Start</label>
								<input type="text" name='date_start' id="datepicker1" class="" style="width: 13%;"/>
								<label style="margin-left: 15px; width: 49px;">
									Time
								</label>
								<input type="text" name='time_start' id="timepickerstart" class="" style="width: 10%; margin-left: "/>
								
								
							</div>
							<div class="form_node_add_event">
								<label>Date End</label>
								
								<input type="text" name='date_end' id="datepicker2" class="" style="width: 13%;"/>
								
								<label style="margin-left: 15px; width: 49px;">
									Time
								</label>
								<input type="text" name='time_end' id="timepickerend" class="" style="width: 10%;"/>
								
							</div>
							<div class="form_node_add_event">
								<label>Description</label>
								
								<textarea id='event_description' name='description'></textarea>
							</div>
							<div class="form_node_add_event">
								<label>Contact Person</label>
								<input type="text" id='event_contact' name='contact' class="" style=""/>
							</div>
							<div class="form_node_add_event">
								<label>Website</label>
								<input name='website' id='event_website' type="text" class="" style=""/>
							</div>
							
							<div class="form_node_add_event">
								<label>Photo Gallery</label>
								<div id='navigator-left' class='arrow_left_def sprt' style='display:inline-block; cursor:pointer; float: left; margin-top: 59px; margin-left: -10px;'></div>
								<div id='navigator-right' class='arrow_right_def sprt' style='display:inline-block; cursor:pointer; float:right; margin-top: 59px; margin-right: -10px;'></div>
								
								<div class = 'div_gallery' style=''>
									<div class ='gallery' id='gallery' style='display:inline; width:auto; height:auto; overflow-x: hidden;'>
										<!--<div style='display:inline-block;position:relative;'>
											<img src='#' style='width:120px;height:152px;font-size:32px; color:#FFF;'>
										</div>
										<div style='display:inline-block;position:relative;'>
											<img src='#' style='width:120px;height:152px;font-size:32px; color:#FFF;'>
										</div>-->
									</div>
									<a href='javascript:void(0)' id='open_file_input' style='color:#FFF;float:left;'>
										<div class='event_gallery_add' style="display:inline-block;position:relative;border:1px; border-style:dashed;color:#FFF;text-align:center;padding-top:25px;width:115px;height:125px;font-size:64px;">
										+
										</div>
									</a>
									<input type='file' id='add_to_gallery' style='display:none'/>
									<script>
										$('#navigator-left').click(function(){
											if(left!=0){
												left-=1;
												right-=1;
												var inner_gallery ="";
												for($i=left;$i<=right;$i++){
													inner_gallery+="<div>";
													inner_gallery+="<img style='width:120px;height:152px;font-size:32px; color:#FFF;' src='"+images_source[$i]+"'></div>";
												}
												$('#gallery').html(inner_gallery);
											}
											
											
										});
										$('#navigator-right').click(function(){
											if(right!=count-1){
												left+=1;
												right+=1;
												var inner_gallery ="";
												for($i=left;$i<=right;$i++){
													inner_gallery+="<div>";
													inner_gallery+="<img style='width:120px;height:152px;font-size:32px; color:#FFF;' src='"+images_source[$i]+"'></div>";
												}
												$('#gallery').html(inner_gallery);
											}
										});
									</script>
								</div>
								
							</div>
							<div class="form_node_add_event">
								<label>Photo Event</label>
								<img src="#" id='event_photo' width="150" height="170"/>
								<input type='file' id='event_photo_change' />
								
							</div>
							<div class="form_node_add_event">
								<label>Dresscode</label>
								<input type="text" id='event_dresscode' name='dresscode' class="" style=""/>
							</div>
							<div class="form_node_add_event">
								<label>Entry Price</label>
								<input type="text" id='event_entry' name='entry_price' class="" style=""/>
							</div>
							<div class="form_node_add_event">
								<label>Type</label>
								<input type="text" id='event_type' name='type' class="" style=""/>
							</div>
							
							<div class="form_node_add_event">
								<input id='ok_button' type="button" class="" value="Add Event" style="width: 200px; margin-left: auto; margin-right: auto;"/>
							</div>
							
							<script>
										jQuery('#datepicker1').datetimepicker({
										 lang:'en',
										 i18n:{
										  en:{
										   months:[
											'January','February','March','April',
											'May','June','July','August',
											'September','October','November','December',
										   ],
										   dayOfWeek:[
											"Sun.", "Mon", "Tue", "Wed", 
											"Thu", "Fri", "Sa.",
										   ]
										  }
										 },
										 timepicker:false,
										 format:'d.m.Y'
										});
										
										jQuery('#datepicker2').datetimepicker({
										 lang:'en',
										 i18n:{
										  en:{
										   months:[
											'January','February','March','April',
											'May','June','July','August',
											'September','October','November','December',
										   ],
										   dayOfWeek:[
											"Sun.", "Mon", "Tue", "Wed", 
											"Thu", "Fri", "Sa.",
										   ]
										  }
										 },
										 timepicker:false,
										 format:'d.m.Y'
										});
										
										jQuery('#timepickerstart').datetimepicker({
										  datepicker:false,
										  format:'H:i'
										});
										jQuery('#timepickerend').datetimepicker({
										  datepicker:false,
										  format:'H:i'
										});
									</script>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
<div class='pu_00 pop_up_super lazy' id="pop_up_super_map" style="display: none;">
	<a class='exit sprt close_56'></a> 
	<div class='pop_up_tbl'>
		<div class='pop_up_cell'> 
			<div class="c_12">
				<div class="g_12">
					<div id='map-canvas' style="display: block; height: 600px; width: 100%; background: #fff;">
						<!-- isi dengan js google map -->	
					</div>
					<input type="submit" id='set_location' class="" value="Set Location" style="width: 200px; margin-left: auto; margin-right: auto;"/>
				</div>
			</div>
		</div>
	</div>
</div>
	
	<script type='text/javascript'>

		$('#add_event_pop_trigger').click(function (){
			
			$( '#pop_up_super_event' ).fadeIn( 200, function(){}); 
		});
		
	
		$('#add_map_pop_trigger').click(function (){
			
			$( '#pop_up_super_map' ).fadeIn( 200, function(){});
			initialize_event();
		});
		
	
		/**javascript untuk meng-close pop up**/
		
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
	
		$('body').on('keyup','#event_location',function(){
			alert($(this).val());
		});
	
		$('body').on('click','#open_file_input',function(){
			$('#add_to_gallery').click();
		});	
		
		$('body').on('click','#set_location',function(){
			$( '#pop_up_super_map' ).fadeOut( 200, function(){});
			location_alphabet = false;
		});
		
		$('#add_to_gallery').change(function(){
			var i = 0, len = this.files.length, img, reader, file;
			for ( ; i < len; i++ ) {
				file = this.files[i];
				if (!!file.type.match(/image.*/)) {
					if ( window.FileReader ) {
						reader = new FileReader();
						reader.onloadend = function (e) { 
							showUploadedItem(e.target.result, file.fileName);
							/*var remove_button = document.getElementById("remove_but");
								remove_button.addEventListener("click",function(evt){
										image = "";
										//document.getElementById("images").disabled = false;
										document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
										document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
								},false);*/
						};
						reader.readAsDataURL(file);
					}
				}
			}
			images[count] = file;
			count++;
		});
		
		$('#event_photo_change').change(function(){
			var i = 0, len = this.files.length, img, reader, file;
			for ( ; i < len; i++ ) {
				file = this.files[i];
				event_image=file;
				if (!!file.type.match(/image.*/)) {
					if ( window.FileReader ) {
						reader = new FileReader();
						reader.onloadend = function (e) { 
							//showUploadedItem(e.target.result, file.fileName);
							$('#event_photo').attr('src',e.target.result);
						};
						reader.readAsDataURL(file);
					}
				}
			}
		});
		
		function showUploadedItem (source) {
			images_source[count_source] = source;
			count_source++;
			refresh_gallery();
			/*var container = document.getElementById("gallery");
			var div = document.createElement('div');
			var img = document.createElement('img');
			div.setAttribute("style","display:inline-block;position:relative;");
			img.setAttribute("style","");
			img.src = source;
			div.appendChild(img);
			container.appendChild(div);*/
		} 
		
		
		
		$('body').on('click','#ok_button',function(){
			$name = $('#event_name').val();
			$address = $('#event_address').val();
			$location = $('#event_location_hidden').val();
			$date_start = $('#datepicker1').val();
			$time_start = $('#timepickerstart').val();
			$date_end = $('#datepicker2').val();
			$time_end = $('#timepickerend').val();
			$description = $('#event_description').val();
			$contact = $('#event_contact').val();
			$website = $('#event_website').val();
			$dresscode = $('#event_dresscode').val();
			$entry_price = $('#event_entry').val();
			$type= $('#event_type').val();
			
			var data = {
				'name':$name,
				'cp':$contact,
				'start_time':$date_start,
				'end_time':$date_end,
				'description':$description,
				'photo':event_image,
				'dresscode':$dresscode,
				'price':$entry_price,
				'type':$type,
				'location':$location,
				'address':$address
			}
			
			var up_input = JSON.stringify(data);
		});
	</script>
	
	
