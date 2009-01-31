<!-- YUI Editor Rendering -->

<!-- Utility Dependencies -->
<script type="text/javascript" src="<?php echo "$siteurl"; ?>/yui/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script type="text/javascript" src="<?php echo "$siteurl"; ?>/yui/build/element/element-beta-min.js"></script> 
<!-- Needed for Menus, Buttons and Overlays used in the Toolbar -->
<script src="<?php echo "$siteurl"; ?>/yui/build/container/container_core-min.js"></script>
<script src="<?php echo "$siteurl"; ?>/yui/build/menu/menu-min.js"></script>
<script src="<?php echo "$siteurl"; ?>/yui/build/button/button-min.js"></script>
<!-- Source file for Rich Text Editor-->
<script src="<?php echo "$siteurl"; ?>/yui/build/editor/editor-min.js"></script>

<script type="text/javascript">
	var myEditor = new YAHOO.widget.Editor('body', {
	height: '400px',
	width: '540px',
	dompath: true, //Turns on the bar at the bottom
	animate: false, //Animates the opening, closing and moving of Editor windows
	handleSubmit: true
	});
	myEditor._defaultToolbar.titlebar = false;
	myEditor._defaultToolbar.buttonType = 'advanced';
	myEditor.render();
</script>

