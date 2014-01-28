/**
 * Author: Rob Audenaerde
 */
function resetTableSizes (table, change, columnIndex){
		//calculate new width;
		var tableId = table.attr('id'); 
		var myWidth = jQuery('#'+tableId+' TR TH').get(columnIndex).offsetWidth;
		var newWidth = (myWidth+change)+'px';

		jQuery('#'+tableId+' TR').each(function() 
		{
			jQuery(this).find('TD').eq(columnIndex).css('width',newWidth);
			jQuery(this).find('TH').eq(columnIndex).css('width',newWidth);
		});
		resetSliderPositions(table);
};

function resetSliderPositions(table){
		var tableId = table.attr('id'); 
		//put all sliders on the correct position
		table.find(' TR:first TH').each(function(index)
		{ 
			var td = jQuery(this);
			var newSliderPosition = td.offset().left+td.outerWidth() ;
			jQuery("#"+tableId+"_id"+(index+1)).css({  left:   newSliderPosition , height: table.height() + 'px'}  );
		});
}

function makeResizable(table){		
		//get number of columns
		var numberOfColumns = table.find('TR:first TH').size();

		//id is needed to create id's for the draghandles
		var tableId = table.attr('id'); 
		
		for (var i=0; i<=numberOfColumns; i++)
		{
			//enjoy this nice chain :)
			jQuery('<div class="draghandle" id="'+tableId+'_id'+i+'"></div>').insertBefore(table).data('tableid', tableId).data('myindex',i).draggable(
			{ axis: "x",
			  start: function () 
			  {
				var tableId = (jQuery(this).data('tableid'));
				jQuery(this).toggleClass( "dragged" );
				//set the height of the draghandle to the current height of the table, to get the vertical ruler
				jQuery(this).css({ height: jQuery('#'+tableId).height() + 'px'} );
			  },
			  stop: function (event, ui) 
			  {
				var tableId = (jQuery(this).data('tableid'));
				jQuery( this ).toggleClass( "dragged" ); 
				var oldPos  = (jQuery( this ).data("draggable").originalPosition.left);
				var newPos = ui.position.left;
				var index =  jQuery(this).data("myindex");
				resetTableSizes(jQuery('#'+tableId), newPos-oldPos, index-1);
			  }		  
			}
			);;
		};
		resetSliderPositions(table);

}
	
	
jQuery(document).ready(function()
	{
		jQuery("table.resizabletable").each(function(index) 
		{
			makeResizable(jQuery(this));
		});
		
	}
);