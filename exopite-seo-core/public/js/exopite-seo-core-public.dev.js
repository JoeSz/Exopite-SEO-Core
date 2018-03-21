;(function( $ ) {
	'use strict';

    $( document ).ready(function() {

        if ( $( '.cookie-container' ).length ) {

            function createCookie(name, value, days) {
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    var expires = "; expires=" + date.toGMTString();
                }
                else var expires = "";

                document.cookie = name + "=" + value + expires + "; path=/";
            }

            function readCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            function eraseCookie(name) {
                createCookie(name, "", -1);
            }

            var cookie = readCookie( 'accept-cookies' );

            if ( readCookie( 'accept-cookies' ) === null ) {
                $( '.cookie-container' ).show( 300 );
            }

            $( '.accept-cookies-js' ).on('click', function(event) {
                event.preventDefault();
                createCookie( 'accept-cookies', 'accepted', 30 );
                $( '.cookie-container' ).hide( 300 );
            });

        }

    });

})( jQuery );
