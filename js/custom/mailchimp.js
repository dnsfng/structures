/**
 * File mailchimp.js.
 *
 * Handles mailchimp widget error and success message
 * so you can disable all useless script
 */

( function() {

 var body = document.getElementsByTagName('body')[0]

 if (window.location.hash) {
    if (window.location.hash.indexOf('mc_signup') == 1) { // not 0 because # is first character of window.location.hash
				 body.classList.add('about');
    }
 }

 window.onhashchange = function() {
	 if (window.location.hash) {
	    if (window.location.hash.indexOf('hide-about') == 1) { // not 0 because # is first character of window.location.hash
					 body.classList.remove('about');
	    }
	 }
 }

document.getElementById('mc_mv_EMAIL').setAttribute('placeholder','Adresse e-mail');

} )();
