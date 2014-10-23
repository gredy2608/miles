var input = document.getElementById("images");
	var formdata = false;
	var submit = document.getElementById("submit_button");
	function showUploadedItem (source) {
  		var list = document.getElementById("image-list"),
	  		li   = document.createElement("li"),
	  		img  = document.createElement("img");
		var but = document.createElement("input");
		but.setAttribute("type","button");
		but.setAttribute("id","remove_but");
		but.setAttribute("value","X");
  		img.src = source;
		if(list.childNodes[0]){
			list.removeChild(list.childNodes[0]);
			//list.removeChild(list.childNodes[0]);
		}
  		li.appendChild(img);
		li.appendChild(but);
		list.appendChild(li);
	}   
	

	if (window.FormData){
  		formdata = new FormData();
  		//document.getElementById("btn").style.display = "none";
	}
	
	
 	input.addEventListener("change", function (evt) {
 		var i = 0, len = this.files.length, img, reader, file;
		for ( ; i < len; i++ ) {
			file = this.files[i];
			if (!!file.type.match(/image.*/)) {
				if ( window.FileReader ) {
					reader = new FileReader();
					reader.onloadend = function (e) { 
						showUploadedItem(e.target.result, file.fileName);
						var remove_button = document.getElementById("remove_but");
							remove_button.addEventListener("click",function(evt){
									image = "";
									//document.getElementById("images").disabled = false;
									document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
									document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
							},false);
					};
					reader.readAsDataURL(file);
				}
				if (formdata) {
					//formdata.append("images[]", file);
					image = file;
				}
			}	
		}
	}, false);


$('body').on('click','#submit_button',function(){
		document.getElementById("submit_button").style.display= "none";
		var names = $('#location_name').val();
		var locations = $('#location_location').val();
		//upload data
		var days = $('#location_day option:selected').val();
		var address = $('#location_address').val();
		var city = $('#location_city').val();
		var phone = $('#location_phone').val();
		var email = $('#location_email').val();
		var url = $('#location_website').val();
		var masakan = $('#location_cuisine option:selected').val();
		var foto = "";
		if(image!=""){
			foto = image.name;
		}
		var range = $('#location_price').val();
		
		var obj = [];
		var counter = 0;
		$('input[class=preferensi]:checked').each(function() {
			if (!obj.hasOwnProperty(this.name)) 
				obj[counter] = this.value;
			else 
				obj[counter].push(this.value);
			counter++;
		});
		var pay = 0;
		if(document.getElementById('pay').checked==true){
		 pay = 1;
		}
		var data = {
		  place: {
			'name': names,
			'locations' : locations,
			'days' : days,
			'city' :city,
			'address' : address,
			'telp' : phone,
			'email' : email,
			'website' : url,
			'photo' : foto
			},
			feature:obj,
			price:range,
			cuisine : masakan,
			membership : pay
		};
		var input = JSON.stringify(data);
		formdata.append("images[]", image);
		formdata.append("name", names);
		formdata.append("location",locations);
		var beach = locations.substring(1, locations.length-1);
		var arr = beach.split(',');
		var lang = arr[0];
		var lat = arr[1];
		if (formdata) {
			$.ajax({
				url: "php/upload.php",
				type: "POST",
				data: formdata,
				processData: false,
				contentType: false,
				success: function (res) {
					$.ajax({
						type: 'POST',
						contentType: 'application/json',
						url: 'http://milesyourday.com/service/place',
						dataType: 'json',
						data: input,
						success: function(data){
							image = "";
							document.getElementById("submit_button").style.display= "inline";
							alert(data.status);
							reGetLocation(lang,lat);
						},
						error: function(jqXHR, textStatus, errorThrown){
							alert(errorThrown);
						}
					});				
				},
				error:function(){
					alert('No Image Found');
				}
			}).done(function(){
				$('#form_add_location').reset();
				$( '.pop_up_super_c' ).fadeOut( 200, function(){});
				$('#location_price').slider('value',0,250000);
				document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
				document.getElementById("image-list").removeChild(document.getElementById("image-list").childNodes[0]);
			});
		}
	});
