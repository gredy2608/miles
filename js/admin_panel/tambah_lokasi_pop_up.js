//update
	$('#update_button').click(function(){
		var up_id = $('#store_id').val();
		var up_names = $('#store_name').val();
		var up_location = $('#store_location').val();
		var up_days = $('#store_day option:selected').val();
		var up_address = $('#address').val();
		var up_phone = $('#phone_number').val();
		var up_email = $('#store_email').val();
		var up_url = $('#store_website').val();
		var up_foto = '';
		if(image2){
			up_foto = image2.name;
		}
		else{
			var temp = $('#pop_up_image').attr('src').split('/');
			up_foto = "";
		}
									
		var up_price = $('#store_price').val();
		var up_city = $('#store_city').val();
		var up_cuisine = $('#store_cuisine option:selected').val();
		var up_pay = 0;
		if(document.getElementById('store_pay').checked==true){
			up_pay = 1;
		}
									
		var up_obj = [];
		var counter = 0;
		$('input[class=store_preferensi]:checked').each(function() {
			if (!up_obj.hasOwnProperty(this.name)) 
				up_obj[counter] = this.value;
			else 
				up_obj[counter].push(this.value);
			counter++;
		});
			var up_data = {
				place: {
					'name': up_names,
					'days' : up_days,
					'location' : up_location,
					'address' : up_address,
					'city': up_city,
					'telp' : up_phone,
					'email' : up_email,
					'website' : up_url,
					'photo' : up_foto
				},
				feature:up_obj,
				price : up_price,
				cuisine : up_cuisine,
				membership : up_pay
			};
			var up_input = JSON.stringify(up_data);
			//alert(up_input);
			var up_formdata=new FormData();
			up_formdata.append('images[]', image2);
			up_formdata.append('name', up_names);
			up_formdata.append('location',up_location);
			//alert(up_foto);
			$.ajax({
				url: 'php/upload.php',
				type: 'POST',
				data: up_formdata,
				processData: false,
				contentType: false,
				success: function (res) {
					$.ajax({
						url: 'http://milesyourday.com/service/place/'+up_id,
						type: 'PUT',
						contentType: 'application/json',
						dataType : 'json',
						data: up_input,
						dataType: 'json',
						success: function(data){
							//alert(data.status);
							//location.reload();
							alert('Update Data Success');
							var beach = up_location.substring(1, up_location.length-1);
							var arr = beach.split(',');
							
							reGetLocation(arr[0], arr[1]);
						},
						error: function(jqXHR, textStatus, errorThrown){
							alert(errorThrown);
						},
						complete: function(){
							$( '.pop_up_super_c' ).fadeOut( 200, function(){});
						}
					});
				},
				error : function(errorThrown){
					alert(errorThrown);
				}
			});						
	});
//update end
	
	//delete
	$('#remove_button').click(function(){
		var store_id = $('#store_id').val();
		$.ajax({
			type: 'DELETE',
			url: 'http://milesyourday.com/service/place/'+store_id,
			dataType: 'json',
			success: function(data){
				alert(data.status);
				location.reload();
				//reGetLocation(-6.917114, 107.609662);
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
	});
	//delete end
	
	$('#update_file').change(function (evt) {
		var i = 0, len = this.files.length, img, reader, file;
		for ( ; i < len; i++ ) {
			file = this.files[i];
			if (!!file.type.match(/image.*/)) {
				if ( window.FileReader ) {
					reader = new FileReader();
					reader.onloadend = function (e) { 
						changeImage(e.target.result, file.fileName);
					};
					reader.readAsDataURL(file);
					}
			}
			image2 = file;
		}
	});
		
	function changeImage(source){
		$('#pop_up_image').attr('src',source);
		$('#pop_up_image').attr('width',200);
		$('#pop_up_image').attr('height',100);
	}