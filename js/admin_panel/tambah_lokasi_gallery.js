	//delete gallery
	
	$('body').on('click','.close_16',function(){
				//alert(this.nextSibling.value);
			var id_foto = this.nextSibling.value;
			$.ajax({
				type: 'DELETE',
				url: 'http://milesyourday.com/service/gallery/'+id_foto,
				dataType: 'json',
				success: function(data){
				alert(data.status);
				//document.getElementById('gallery').innerHTML = '<div></div>';
				left = 0;
				right = 2;
				//alert($('#store_id').val());
				getGallery($('#store_id').val());		
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
	});
	
	//end delete
	
	//add gallery
	$('#add_button').click(function(){
		$('#add_gallery').click();
	});
	
							
	$('#add_gallery').change(function (evt) {
		var i = 0, len = this.files.length, img, reader, file;
		for ( ; i < len; i++ ){
			file = this.files[i];
			image_gal = file;
		}
		//initiate
									
		right = limit_right+1;
		left = right-2;
		if(left< 0){
			left = 0;
		}
		divs='';
										
		for(var i=left;i<=right;i++){
			divs+= '<div class=gallery_image style=display:inline-block;position:relative;><span class=close_16></span><input type=hidden value ='+id_gambar_gal[i]+'><img src='+daftar_foto[i]+' width=190 height = 150/></div>';
		}
		divs+='<div class=gallery_image style=display:inline-block;position:relative;text-align:center;><img style=padding-left:45px;padding-right:45px;padding-top:25px;padding-bottom:25px; src=images/loading-transparent.gif /></div>';
		document.getElementById('gallery').innerHTML = divs;
		//end of initiation
										
		//profile_id
		var profil_id = 0;
		//place_id
		var place_id = $('#store_id').val();
		//photo
		var photo = image_gal.name;
		var up_formdata=new FormData();
		up_formdata.append('images[]', image_gal);
		up_formdata.append('name', $('#store_name').val());
		up_formdata.append('location',$('#store_location').val());
										
		var data = {
			'profile_id': profil_id,
			'place_id' : place_id,
			'photo' : photo
		};
		var input = JSON.stringify(data);
		if (up_formdata){
			$.ajax({
				url: 'php/upload.php',
				type: 'POST',
				data: up_formdata,
				processData: false,
				contentType: false,
				success: function (res) {
					$.ajax({
						url: 'http://milesyourday.com/service/gallery',
						type: 'POST',
						contentType: 'application/json',									
						dataType : 'json',
						data: input,
						dataType: 'json',
						success: function(data){
							if(data.status == 'success'){
							// upload gambar
								getGallery(place_id);
							}
						},
						error: function(jqXHR, textStatus, errorThrown){
							alert(errorThrown);
						}
					});
				}
			});
		}
	});