<div class="container">
	<div class="row">
		<div class="c-lg-12"> <!-- g_12 t_48 mt_30 -->
			Edit Recomendation
		</div>
	</div>
	<div class="row">
		<div class="c-lg-4">
			<span class="t_48 mb_20" style="display: block;">
				New
			</span>
			<ul>	
				<li class='user_node'>

					<div class='new_rank'>
						01
					</div>
					<div class='user_name'>
						Cafe おっぱいさｎ
					</div>
					<button class='choose_but'>
						Change
					</button>
				</li>
				<li class='user_node'>

					<div class='new_rank'>
						02
					</div>
					<div class='user_name'>
						Cafe おっぱいさｎ
					</div>
					<button class='choose_but'>
						Change
					</button>
				</li>


			</ul>
		</div>
		<div class="c-lg-4">
			<span class="t_48 mb_20" style="display: block;">
				Top
			</span>
			<ul>	
				<li class='user_node'>
					<img src='./images/dum_pic.png' alt='' width='100'/>
					<div class='user_name'>
						rthyhgj
					</div>
					<button class='choose_but'>
						Deactivate
					</button>
				</li>
				<li class='user_node'>
					<img src='./images/dum_pic.png' alt='' width='100'/>
					<div class='user_name'>
						rthyhgj
					</div>
					<button class='choose_but'>
						Deactivate
					</button>
				</li>


			</ul>
		</div>
		<div class="c-lg-4">
			<span class="t_48 mb_20" style="display: block;">
				Trending
			</span>
			<ul>	
				<li class='user_node'>
					<img src='./images/dum_pic.png' alt='' width='100'/>
					<div class='user_name'>
						rthyhgj
					</div>
					<button class='choose_but'>
						Deactivate
					</button>
				</li>
				<li class='user_node'>
					<img src='./images/dum_pic.png' alt='' width='100'/>
					<div class='user_name'>
						rthyhgj
					</div>
					<button class='choose_but'>
						Deactivate
					</button>
				</li>


			</ul>
		</div>
	</div>
</div>

<div class='pu_00 pop_up_super_c lazy' style="display: none;">
	<a class='exit sprt close_56'></a> 
	<div class='pop_up_tbl'>
		<div class='pop_up_cell'> 
			<div class="c_12">
				<div class="push_2 g_8">
					<form>
						<input type="text" class="" style="width: 100%; margin-top: 50px;"/>
						
						<ul>
							<li class='user_node'>
								<img src='./images/dum_pic.png' alt='' width='100'/>
								<div class="cafe_info">
									<div class='cafe_list_node'>
										Nama Cafe　おっぱいですね
									</div>
									<div class='cafe_list_node'>
										Alamat Cafe　おっぱいですね
									</div>
								</div>
								
								
								<button class='choose_cafe_but'>
									Choose This
								</button>
							</li>
							<li class='user_node'>
								<img src='./images/dum_pic.png' alt='' width='100'/>
								<div class="cafe_info">
									<div class='cafe_list_node'>
										Nama Cafe　おっぱいですね
									</div>
									<div class='cafe_list_node'>
										Alamat Cafe　おっぱいですね
									</div>
								</div>
								
								
								<button class='choose_cafe_but'>
									Choose This
								</button>
							</li>
							<li class='user_node'>
								<img src='./images/dum_pic.png' alt='' width='100'/>
								<div class="cafe_info">
									<div class='cafe_list_node'>
										Nama Cafe　おっぱいですね
									</div>
									<div class='cafe_list_node'>
										Alamat Cafe　おっぱいですね
									</div>
								</div>
								
								
								<button class='choose_cafe_but'>
									Choose This
								</button>
							</li>
							
						</ul>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>
$('.choose_but').click(function (){
	$( '.pop_up_super_c' ).fadeIn( 200, function(){}); 
});


/**javascript untuk meng-close pop up**/

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

</script>