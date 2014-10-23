	var toogle = -1;
	var id_trending_place="";
	var id_new_place="";
	var id_top_place="";
	var id_tempat = [];
	var position = 0;
	$('#edit_recomendation_nav').click(function(){
			var view = "<div class='container'>";
				view+="<div class='row'>";
					view+="<div class='g-lg-12'>";
						view+="<h1>";
							view+="Edit Recomendation";
						view+="</h1>";
					view+="</div>";
				view+="</div>";

				view+="<div class='row'>";
					//new
					view+="<div class='g-lg-4'>";
						view+="<h2>New</h2>";
						view+="<ul id='content_new'>";
						view+="</ul>";
					view+="</div>";
					//top
					view+="<div class='g-lg-4'>";
						view+="<h2>Top</h2>";
						view+="<ul id='content_top'></ul>";
					view+="</div>";
					//trending
					view+="<div class='g-lg-4'>";
						view+="<h2>Trending</h2>";
						view+="<ul id='content_trending'></ul>";
					view+="</div>";
				view+="</div>";
			view+="</div>";
			
			//pop-up
			view+="<div class='pu_00 pop_up_super_c lazy' style='display: none;'>";
			view+="<a class='exit sprt close_56'></a>";
			view+="<div class='pop_up_tbl'>";
			view+="<div class='pop_up_cell'>";
			view+="<div class='c_12' style='background:#fff;'>";
			view+="<div class='push_2 g_8'>";
			//view+="<form>";
			view+="<input type='text' id='search_place' class='' style='width: 100%; margin-top: 50px;'/>";
			view+="<ul id='list_place'></ul>";
			//view+="</form>";
			view+="</div>";
			view+="</div>";
			view+="</div>";
			view+="</div>";
			view+="</div>";

			$('.function_panel').html(view);
			//new
			getNewPlace();
			//ajax top
			getTopPlace();
			//ajax trending
			getTrendPlace();
			
			//success = arrange innerHTML in function_panel or c_12
		});
		
		$('body').on('click','#new_button',function(){
			toogle = 0;
		});
		
		$('body').on('click','#trend_button',function(){
			toogle = 1;
		});
		
		$('body').on('click','#top_button',function(){
			toogle = 2;
		});
		
		// ajax change button
		$('body').on('click','.choose_but',function(){
			//prepare list
			id_tempat = [];
			var list_tempat = "";
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
							list_tempat+="<li class='user_node'>";
							list_tempat+="<img src='"+value.place.photo+"' alt='' width='100'/>";
							list_tempat+="<div class='cafe_info'>";
							list_tempat+="<div class='cafe_list_node' style='display: block; width: 100%;'>";
							list_tempat+="Nama "+ value.place.name;
							list_tempat+="</div>";
							list_tempat+="<div class='cafe_list_node' style='display: block; width: 100%;'>";
							list_tempat+="Alamat "+ value.place.address;
							list_tempat+="</div>";
							list_tempat+="</div>";
							list_tempat+="<input type='button' value='Choose This' class='choose_cafe_but edit_rec_but' />";
							list_tempat+="<input type='hidden' value="+counter2+" />";
							list_tempat+="</li>";
							counter2++;
						});
					}
					$('#list_place').html(list_tempat);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
			$( '.pop_up_super_c' ).fadeIn( 200, function(){});
			position = $(this).next().val();
			//alert(toogle);
		});
		
		$('body').on('click','.choose_cafe_but',function(){
			//alert(toogle+" "+$(this).next().val());
			//var id_trending_place="";
			//var id_new_place="";
			//var id_top_place="";
			if(toogle == 0){
				//new
			}
			else if(toogle == 1){
				//trend
				
				//var position = $(this).next().val();
				
				var newplaceid =id_tempat[$(this).next().val()];
				var up_data = {
					position : parseInt(position)+1,
					newplaceid : newplaceid
				};
				var up_input = JSON.stringify(up_data);
				//alert(up_input);
				$.ajax({
					type: 'PUT',
					contentType: 'application/json',
					url: 'http://milesyourday.com/service/trendingplace',
					data : up_input,
					dataType: 'json',
					success: function(data){
						alert("Success");
						getTrendPlace();
					},
					error: function(jqXHR, textStatus, errorThrown){
						alert(errorThrown);
					},
					complete: function(){
						$( ".pop_up_super_c" ).fadeOut( 200, function(){});
						$('html').css('overflow-y', 'auto');
					}
				});
			}
			else if(toogle == 2){
				//top
				var newplaceid =id_tempat[$(this).next().val()];
				var pos = parseInt(position)+1;
				var up_data = {
					position : pos+"",
					newplaceid : newplaceid
				};
				var up_input = JSON.stringify(up_data);
				
				//alert(up_input);
				
				$.ajax({
					type: 'PUT',
					contentType: 'application/json',
					url: 'http://milesyourday.com/service/topplace',
					data : up_input,
					dataType: 'json',
					success: function(data){
						alert("Success");
						getTopPlace();
						
					},
					error: function(jqXHR, textStatus, errorThrown){
						alert(errorThrown);
					},
					complete: function(){
						$( ".pop_up_super_c" ).fadeOut( 200, function(){});
						$('html').css('overflow-y', 'auto');
					}
				});
			}
		});
		
		/**javascript untuk meng-close pop up**/
		//$('body').on('click','.pop_up_cell',function(){
		//		$( '.pop_up_super_c' ).fadeOut( 200, function(){});
		//		$('html').css('overflow-y', 'auto');
		//});
		$('body').on('click','.pop_up_super_c',function (e)
			{
				var container = $('.pop_up_cell');

				if (container.is(e.target) )// if the target of the click is the container...
				{
					$( ".pop_up_super_c" ).fadeOut( 200, function(){});
					$('html').css('overflow-y', 'auto');
				}
			});
			
		$('body').on('keyup','#search_place',function(){
			alert($(this).val());
		
		});
			
			
	function getNewPlace(){
		$.ajax({
				type: 'GET',
				contentType: 'application/json',
				url: 'http://milesyourday.com/service//get15newplace',
				dataType: 'json',
				success: function(data){
					id_new_place = data[0].id;
					var name_new_place = data[0].name;
					var address_new_place = data[0].address;
					var length = id_new_place.length;
					var list = "";
					for(var i = 0;i<length;i++){
						list+="<li class='user_node'>";
						list+="<div class='new_rank' style='line-height:40px;'>";	
						list+= i+1;
						list+="</div>";
						list+="<div class='user_name' style='width: 79%; line-height: 40px; overflow:hidden;'>";
						list+=name_new_place[i];
						list+="<br />";
						list+=address_new_place[i];
						list+="</div>";
						list+="<input disabled type='button' value ='Change' id='new_button' class='choose_but btn btn-warning' style='cursor:default;opacity:0;margin:0px; float: left; margin-left:50px;' />";
						list+="<input type='hidden' value="+i+" />";
						list+="<div class='clear'></div>";
						list+="</li>";
					}
					$("#content_new").html(list);
					
					
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
	
	}
	
	function getTopPlace(){
		$.ajax({
			type: 'GET',
			contentType: 'application/json',
			url: 'http://milesyourday.com/service//get15topplace',
			dataType: 'json',
			success: function(data){
				if(data=='gagal'){
					return "";
				}
				id_top_place = data[0].id;
				var name_top_place = data[0].name;
				var address_top_place = data[0].address;
				var length = id_top_place.length;
				var list = "";
				for(var i = 0;i<length;i++){
					list+="<li class='user_node'>";
					list+="<div class='new_rank' style='line-height:40px;'>";	
					list+= i+1;
					list+="</div>";
					list+="<div class='user_name' style='width: 79%; line-height: 40px; overflow:hidden;'>";
					list+=name_top_place[i];
					list+="<br />";
					list+=address_top_place[i];
					list+="</div>";
					list+="<input type='button'  value ='Change' id='top_button' class='choose_but btn btn-warning' style='margin:0px; float: left; margin-left:50px;'/>";
					list+="<input type='hidden' value="+i+" />";
					list+="<div class='clear'></div>";
					list+="</li>";
				}
				$("#content_top").html(list);
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
	
	}
	
	function getTrendPlace(){
		$.ajax({
			type: 'GET',
			contentType: 'application/json',
			url: 'http://milesyourday.com/service//get15trendingplace',
			dataType: 'json',
			success: function(data){
				id_trending_place = data[0].id;
				var name_trending_place = data[0].name;
				var address_trending_place = data[0].address;
				var length = id_trending_place.length;
				var list = "";
				for(var i = 0;i<length;i++){
					list+="<li class='user_node'>";
					list+="<div class='new_rank' style='line-height:40px;'>";	
					list+= i+1;
					list+="</div>";
					list+="<div class='user_name' style='width: 79%; line-height: 40px; overflow:hidden;'>";
					list+=name_trending_place[i];
					list+="<br />";
					list+=address_trending_place[i];
					list+="</div>";
					list+="<input type='button'  value ='Change' id='trend_button' class='choose_but btn btn-warning' style='margin:0px; float: left; margin-left:50px;'/>";
					list+="<input type='hidden' value="+i+" />";
					list+="<div class='clear'></div>";
					list+="</li>";
				}
				$("#content_trending").html(list);
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
	}
	
		
