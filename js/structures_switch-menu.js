/**
 * File structures--switch-about-section.js.
 *
 * Handles toggling the navigation menu and about section visibility
 */

document.addEventListener('DOMContentLoaded', function() {

	var switch_about 	= document.getElementsByClassName('menu-switch__about')[0],
			switch_menu 	= document.getElementsByClassName('menu-switch__menu')[0];

	switch_about.addEventListener('click', function(e){
		e.preventDefault();
		var parentClass = e.target.parentElement.classList;

		if (bodyClass("home")) 	{ parentClass.add('show-about');	}
		if (bodyClass("single")){ parentClass.remove('show-menu');	}

	})

	switch_menu.addEventListener('click', function(e){
		e.preventDefault();
		var parentClass = e.target.parentElement.classList;

		if (bodyClass("home")) 	{ parentClass.remove('show-about');	}
		if (bodyClass("single")){ parentClass.add('show-menu');	}

	})

	function bodyClass(className){
		return document.body.classList.contains(className) ? true : false;
	}

})
