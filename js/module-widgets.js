jQuery(document).ready(function(){
    
	
    //Make sure the droppable area is the same height as the right sidebar
//    if( jQuery('div.widget-liquid-right.modules').height() > jQuery('div.widgets-left').height() ) {
//      jQuery('div.widgets-left').height(jQuery('div.widget-liquid-right.modules').height());
//      console.log('a')
//    }
//    if( jQuery('div.widgets-left').height() > jQuery('div.widget-liquid-right.modules').height()  ) {
//      jQuery('div.widget-liquid-right.modules').height(jQuery('div.widgets-left').height());
//      console.log('b')
//    }
      
   /* Set up a data object that will contain the draggable's coords as well as all of the draggables that needs to be removed on save 
    * coords is an Array with objects that contain the data for the saveCoords function
    * remove is a array containing the ids of the elements to remove
    * It will look like this:
    * var izc_draggableDataObj = {
       coords: [{
                  id	 	: 'the_id',
                  _class 	: 'the_class',
                  zindex	: 'the_zindex',
                  top	 	: 'topVal',
                  left	 	: 'leftVal',
                  width	 	: 'the_width',
                  height	: 'the_height',
                  panel	 	: 'the_panel',
                  plugin_alias: 'the_plugin alias',
                  html	 	: 'the_html',
                  user_func	: 'the_user_funct'
                }],
       to_remove: [] //ids of the draggables to remove
     }
    */
   izc_draggableDataObj = {
     coords: [],
     to_remove: [] //ids of the draggables to remove
   };				
   
    /* Set Up the saving of the panel */
    jQuery('input[name="izc_save_panel"]').click( function() {      
      var the_button = jQuery(this);
      var the_panel = jQuery(this).attr('id')
      the_button.parent().find('div.leftDrag').each(function(){
        
        var theDraggable = jQuery(this);        
        var pos = getRelativeDragPos(theDraggable, theDraggable.parent());
        
        //Populate the izc_draggableDataObj object with the current draggables' coordinates and dimensions
        saveCoords(theDraggable, pos['top'],  pos['left'], theDraggable.width(), theDraggable.height(), theDraggable.parent().attr('id'), theDraggable.attr('data-function'));        
      })
      
      var data = {
        action: 'izc_save_panel',
        panel: the_panel, //@TODO: Create a save button for each panel
		panelW: the_button.parent().width(),
		panelH: the_button.parent().height(),
        coords: JSON.stringify(izc_draggableDataObj.coords),
        to_remove: JSON.stringify(izc_draggableDataObj.to_remove)
      }
      var oldVal = the_button.val();
      the_button.val('Saving...');
      
      jQuery.post(ajaxurl, data, function(response) {
        the_button.val(oldVal);
        stamp_succesfull_save(the_button)
      })
      
      //remove/reset all the old data from izc_draggableDataObj
      izc_draggableDataObj.coords = [];
      izc_draggableDataObj.to_remove = [];
      
    });
   
	var rightDrop = jQuery('div#widgets-right div.widgets-holder-wrap div.iz-droppable');
	var rightDrag = jQuery('div#widgets-right div#widget-list .iz-draggable');
    var leftDrop  = jQuery('div#widgets-left div.widgets-holder-wrap div#primary-widget-area div.iz-droppable');
    var leftDrag  = jQuery('div#widgets-left div.widgets-holder-wrap div.leftDrag');
    
	var removeBtn = jQuery('div.removeObj');
	var grabHandle = jQuery('div.grabHandle');
	
	leftDrag.removeClass('widget-borders');
	jQuery("div#show_borders").addClass('active');
	leftDrop.children().addClass('widget-borders');
	
	jQuery( "#show_borders,#resize_children" ).click(function() {
			var btn = jQuery(this);

			if(btn.hasClass('active'))
				{
				btn.removeClass('active');
				}
			else
				{
				btn.addClass('active');
				}
			}
		);
	
	
	jQuery('div.iz-panel').resizable
		(
			{
			grid		: 10,
			containment: 'parent'
			}
		);

	leftDrag.resizable
		(
			{
			grid		: 10,
			containment: 'parent'
			}
		);
	
	
	jQuery("div#show_borders").click(
		function(){
			if(jQuery(this).hasClass('active'))
				{
				//alert(jQuery(this).closest('div.iz-panel').children());
				jQuery(this).closest('div.iz-panel').children('.leftDrag').addClass('widget-borders');
				}
			else
				{
				jQuery(this).closest('div.iz-panel').children('.leftDrag').removeClass('widget-borders');
				
				}
			}
		);
		
	jQuery('div#resize_children').click(
		function()
			{
			//Kill current resize setting
			jQuery(this).closest('div.iz-panel').resizable( "destroy" );
			jQuery(this).closest('div.iz-panel').children('div.leftDrag').resizable( "destroy" );
			
			//Resize Panel and children
			if(jQuery(this).hasClass('active'))
				{
				jQuery(this).closest('div.iz-panel').resizable
					(
						{
						grid		: 10,
						containment: 'parent',
						alsoResize: jQuery(this).closest('div.iz-panel').children('div.leftDrag')
						}
					);
				jQuery(this).closest('div.iz-panel').children('div.leftDrag').resizable
					(
						{
						grid		: 10,
						containment: 'parent'
						}
					);	
				}
			//Resize Panel only
			else
				{
				jQuery(this).closest('div.iz-panel').resizable
					(
						{
						grid		: 10,
						containment: 'parent'
						}
					);
				jQuery(this).closest('div.iz-panel').children('div.leftDrag').resizable
					(
						{
						grid		: 10,
						containment: 'parent'
						}
					);
				}
			}
		);
	
	leftDrag.live('mouseover',function() {
        var theDraggable = jQuery(this);
		theDraggable.find('div.removeObj').show();
        theDraggable.find('div.grabHandle').show();
        theDraggable.find('.ui-resizable-handle').show();
	});
    leftDrag.live('mouseout',function() {
        var theDraggable = jQuery(this);
		theDraggable.find('div.removeObj').hide();
        theDraggable.find('div.grabHandle').hide();
        theDraggable.find('.ui-resizable-handle').hide();
	})
	
    //Add unique classes to the right container's draggable items
	leftDrop.removeClass('ui-droppable');
    rightDrag.addClass	('rightDrag');
    
	leftDrag.each(function()
		{
		setUpNewDraggable(jQuery(this));
		});
	
    leftDrop.droppable(
    	{
        //Events
        drop        : function(event, ui){ 
			moveToContainer(ui.draggable, jQuery(this));
			var pos = getRelativeDragPos(ui.draggable, ui.draggable.parent());
			jQuery(this).removeClass('over');            
            
            
		},
        create      : function() { },
        activate    : function() { },
        over        : function() {jQuery(this).addClass('over')},
        out         : function() {jQuery(this).removeClass('over')},
        tolerance 	: 'fit',
        accept    	: '.rightDrag'
    	});
    
    rightDrop.droppable();    
    
    //Be draggable when it is over the droppable
    rightDrag.draggable(
        {
            //Events
            create : function() {},
            start  : function() {},
            drag   : function(event, ui){},
            stop   : function(event, ui){},
            //options
            stack  : '.draggable',
            revert : 'invalid', //Go back to where it started if not dropped on to a droppable                    
            accept : '.leftDrag',
			helper : 'clone'			
        }
    );        
})

//It should be duplicated in the designer and removed from the Elements
//And vice versa            
function moveToContainer(theObj, newContainer) {
    //Remove the element    
	     
    newObj = theObj.clone();
	newObj.attr('class', 'iz-draggable leftDrag newest');
    newObj.attr('style', '');
	
	var id = jQuery(newObj).attr('id');
	jQuery(newObj).attr('id', id + '_' + Math.round(Math.random()*99999));
	jQuery(newObj).addClass(id);
	
    newContainer.prepend(newObj);    
    setUpNewDraggable(newObj);
}

function setUpNewDraggable(newObj) {	
    
    newObj.find('div.widget-title-action').empty();
	
    var leftDrag  = jQuery('div#widgets-left div.widgets-holder-wrap div.leftDrag');
    //Be draggable and absolutely positioned when it is in the left container
    leftDrag.draggable(
        {
		//Events
		create : function(event, ui){ 
          if(jQuery(this).hasClass('newest')){
            //var pos = getRelativeDragPos(jQuery(this), jQuery(this).parent());
            //saveCoords(jQuery(this), pos['top'],  pos['left'], jQuery(this).width(), jQuery(this).height(), jQuery(this).parent().attr('id'), jQuery(this).attr('data-function'));
          }
        },
		start  : function() {  },
		drag   : function(event, ui){ 
          jQuery(this).removeClass('newest'); 
          jQuery(this).addClass('dragging')
        },
		stop   : function(event, ui){ 
          jQuery(this).removeClass('newest');
          jQuery(this).removeClass('dragging');
//          var pos = getRelativeDragPos(jQuery(this), jQuery(this).parent());
//          saveCoords(jQuery(this), pos['top'],  pos['left'], jQuery(this).width(), jQuery(this).height(), jQuery(this).parent().attr('id'), jQuery(this).attr('data-function'));
        },
		//options
		stack 				: '.leftDrag',
		containment 		: 'parent',
		handle				: '',//'.grabHandle',		
		refreshPositions 	: true,
		//snap			 	: leftDrag,
		//snapMode 		 	: 'outer',
		grid				: [10, 10]
        }
    );
      
    leftDrag.resizable
      (
          {
          grid		: 10,
          containment: 'parent'
          }
      );
      
      //Add a 'remove' button for the deletion of the object
      if(newObj.find('div.cover').length <= 0) {
        var widget_element = '<div class="cover"> </div>'; //this div is absolutely positioned to cover all the content so the user cant ineract with it
        widget_element += '<div class="grabHandle"> </div>';
        widget_element += '<div class="removeObj">';
        widget_element += '<a href="#"> </a>';
        widget_element += '</div>';

        newObj.prepend(widget_element);
      }
      
      newObj.find('div.widget-top').remove();      
	
	//Remove the object if the remove button is clicked    
    jQuery('div#widgets-left div.widgets-holder-wrap div.leftDrag div.removeObj a').unbind('.removeCoords'); //make sure only 1 click event is bound
    jQuery('div#widgets-left div.widgets-holder-wrap div.leftDrag div.removeObj a').bind('click.removeCoords',function()
		{
          var theDraggable = jQuery(this).parent().parent();
          var panel		 = jQuery(this).parent().parent().parent();

          theDraggable.remove();        
          removeCoords(theDraggable);
   		})
        
    //Show or hide the borders depending on the option
    if(jQuery('div#show_borders').hasClass('active')) {
      leftDrag.addClass('widget-borders');
    }
}

function moveToSortable(theObj, newContainer) {
    //Remove the element               
    newObj = theObj; //.clone();                
    theObj = theObj.detach();           

    newObj.attr('class', 'iz-draggable sortable');
    newObj.attr('style', '');

    if( newObj.hasClass('draggable') ){} 
	else if( newObj.hasClass('sortable') )
		{
        newObj.addClass('draggable')
    	}
    newContainer.prepend(newObj);
}

//Get the draggable (or any other) item's position relative to another DOM element
function getRelativeDragPos(theObj, relativeTo) {
	d_off 			= theObj.offset();
    c_off 			= relativeTo.offset();                
    topLeft 		= new Array();
	//Subtract the container and dropped item's positions and you have the dropped item's position relative to the container item
	topLeft['top']  = Math.round(d_off.top  - c_off.top);
    topLeft['left'] = Math.round(d_off.left - c_off.left);                
    return topLeft;
}

function saveCoords(theDraggable, top, left, width, height, panel, user_func) {  
	
   var data = 	{
				id	 		: theDraggable.attr("id"),
				class 		: theDraggable.attr("class"),
				zindex	 	: theDraggable.css('z-index'),
				top	 		: top -  ( ( theDraggable.parent().outerWidth() - theDraggable.parent().width() ) / 2 ),
				left	 	: left - ( ( theDraggable.parent().outerHeight() - theDraggable.parent().height() ) / 2 ),
				width	 	: width,
				height	 	: height,
				panel	 	: panel,
				plugin_alias: jQuery('div#plugin_alias').text(),
				html	 	: '',
				user_func	: user_func
				};
    
    var oldCoords = izc_draggableDataObj.coords;
    
    oldCoords.push(data);
    console.log(izc_draggableDataObj)
    
//   	jQuery.post(ajaxurl, data, function(response)
//		{ 			
//		if(theDraggable.hasClass('newest'))
//			{	
//			var data = 	{
//						action	  : 'create_widget_element',
//						widget_id :  theDraggable.attr("id"),
//						panel	  :  theDraggable.parent().attr("id")
//						};
//					
//			var content = theDraggable.parent().html();
//					
//			jQuery.post(ajaxurl, data, function(response)
//				{
//				if(!response)
//					{
//					jQuery('#'+theDraggable.attr("id")).html('Loading...');
//					}
//				else
//					{
//					jQuery('#'+theDraggable.attr("id")).html(response);	
//					}
//				});
//			}
//    	}
//	);
}
/*
 * Receives the izc_draggableDataObj object and loops through the remove array
 */
function removeCoords(theDraggable, panel) {
  
  //@TODO: Add the new draggable to the end of the izc_draggableDataObj
    
  var to_be_removed = izc_draggableDataObj.to_remove;
  
  
  //First check if it is in the coords array
    
  to_be_removed.push(theDraggable.attr('id'));
  
  izc_draggableDataObj.to_remove = to_be_removed;
  
  console.log(izc_draggableDataObj)
      
//  JSON.stringify(izc_draggableDataObj);
   
//   var data = 	{
//				action	: 'save_panel_element',
//				id		: theDraggable.attr("id"),
//				remove	: 'true',
//				panel	: panel.attr("id")
//				};
//   jQuery.post(ajaxurl, data, function(response) {});
   
};