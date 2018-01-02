/**
 * File structures--switch-sub-menu.js.
 *
 * Handles toggling the secondary level menu
 */

document.addEventListener('DOMContentLoaded', function() {

	var open_about = document.getElementsByClassName('menu-toggle-about')[0],
			close_about = document.getElementsByClassName('panel-close-about')[0];

	open_about.addEventListener('click', function(e){
		document.body.classList.add("noscroll");
	})

	close_about.addEventListener('click', function(e){
		document.body.classList.remove("noscroll");
	})

})
