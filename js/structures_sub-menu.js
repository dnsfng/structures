/**
 * File structures--switch-sub-menu.js.
 *
 * Handles toggling the secondary level menu
 */

document.addEventListener('DOMContentLoaded', function() {

	var stack = document.querySelectorAll('.page_item_has_children > a');

	for (var i = 0; i < stack.length; i++) {

		var item = stack[i];
		item.addEventListener('click', function(e){
			console.log(e);
			e.preventDefault();

			var parentClass = e.target.parentElement.classList;
			if (bodyClass("home")) 	{ parentClass.toggle('show-stack');	}

		})
	}

	function bodyClass(className){
		return document.body.classList.contains(className) ? true : false;
	}

})
