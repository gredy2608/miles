
	$('#view_user_nav').click(function(){
			//alert('asdf');
			
			//get all user ajax
			$.ajax({
			type: 'GET',
			contentType: 'application/json',
			url: 'http://milesyourday.com/service/user',
			dataType: 'json',
			success: function(data){
				//alert(data.first_name[0]);
				id = data.id;
				var active = data.active;
				var first_name = data.first_name;
				var last_name = data.last_name;
				var photo = data.photo;
				var length = active.length;
				var view = "<div class='c_12'>";
				view+="<div class='g_12 t_48 mt_30'>";
				view+="View Users"
				view+="</div>"
				
				view+="<div class='push_3 g_3'>"
				view+="<div class='total_reg_user_label'>";
				view+="Total Users";
				view+="</div>";
					
				view+="</div>";
				view+="<div class='push_3 g_3'>";
				view+="<div class='total_reg_user_value'>";
				view+=length;
				view+="</div>";
					
				view+="</div>";
				
				view+="<div class='seperate_line g_12'>";
				view+="</div>";
				view+="<div class='g_12 t_48 mt_30'>";
				view+="User List";
				view+="</div>";
				view+="<div class='g_12 holder'></div>";
				view+="<div class='clear'></div>";
				view+="<ul id='content'></ul>";
				//view+="</div>";
				view+="</div>";
			
				$('.function_panel').html(view);
				
				var list = "";
				
				for(var i=0;i<length;i++){
					//alert(photo[i]+" "+first_name[i]+" "+last_name[i]+" "+active[i]);
					list += "<li class='user_node'>";
					if(photo[i]==""){
						list += "<img src='null' alt='' height='75'/>";
					}
					else{
						list += "<img src='"+photo[i]+"' alt='' height='75'/>";
					}
					//list += "<img src='"+photo[i]+"' alt='' height='75'/>";
					list += "<div class='user_name'>"
					list +=	first_name[i]+" "+last_name[i];
					list += "</div>"
					list += "<button class='deactivate_but btn btn-danger'>";
					if(active[i] == 1){
							list += "Deactivate";
					}
					else{
						list += "Activate";
					}
					list += "</button>";
					list += "<input type='hidden' value='"+i+"' />";
					list += "</li>";
				}
				
				$('#content').html(list);
				$("div.holder").jPages({
					containerID : "content",
					perPage: 5
				});
				//slicing data
	
				// make html format for inner HTML
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}
			});
			
			//success = arrange innerHTML in function_panel or c_12
		});
		
		
		
		
		$('body').on('click','.deactivate_but',function(){
			var idx = $(this).next().val();
			//alert(id[idx]);
			$.ajax({
				type: 'PUT',
				contentType: 'application/json',
				url: 'http://milesyourday.com/service/account',
				data:{
					'id':id[idx],
					'active':0
				},
				dataType: 'json',
				success: function(response){
					alert(response.status);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
				});
			});
