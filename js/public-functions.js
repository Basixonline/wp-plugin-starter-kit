// JavaScript Document/**********************/
jQuery(document).ready(
function ()
	{
/* Core Text Scroller */
/**********************/
jQuery('div.iz-scroll').each(
	function()
		{
		if( jQuery(this).height() < jQuery(this).parent().height() )
			jQuery(this).find('div.scroll-arrow-bottom').hide();
		}
	);
	
	//Bottom arrow
	jQuery('div.iz-scroll div.scroll-arrow-bottom').hover
		(
		function()
			{
			
			jQuery(this).addClass('sfhover');
			var get_height 	= parseInt(jQuery(this).parent().height(),10);
			var set_speed 	= 15*get_height;
			jQuery(this).parent().animate({marginTop:(get_height-jQuery(this).parent().parent().height())*(-1)},set_speed,
				function()
					{
					jQuery(this).find('div.scroll-arrow-bottom').hide();
					});
			},
		function()
			{
			jQuery(this).next('div.scroll-arrow-top').show();
			jQuery(this).removeClass('sfhover');
			jQuery(this).parent().animate().stop(true);
			}
		);
	//Top arrow	
	jQuery('div.iz-scroll div.scroll-arrow-top').hover
		(
		function()
			{
			
			jQuery(this).addClass('sfhover');	
			var styleAttr 		= jQuery(this).parent().attr('style').split('margin-top:');
			var get_margin_top 	= styleAttr[1].replace('px;','');	
			var get_height 		= parseInt(get_margin_top,10);
			var set_speed 		= 20*get_height;

			jQuery(this).parent().animate
				(
				{marginTop:0},set_speed*(-1),
				function()
					{
					jQuery(this).find('div.scroll-arrow-top').hide();
					}
				);
			},
		function()
			{
			jQuery(this).prev('div.scroll-arrow-bottom').show();
			jQuery(this).removeClass('sfhover');		
			jQuery(this).parent().animate().stop(true);
			}
		);
		
		jQuery('input[type="file"]').trigger('focus');
/**********************/
	}
);