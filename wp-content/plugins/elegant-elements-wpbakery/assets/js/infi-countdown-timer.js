( function( $ ) {

	$.fn.elegantCountDownTimer = function( options, callback ) {
		var settings = $.extend( {
			date: null,
			offset: null
		}, options );

		// Throw error if date is not set.
		if ( !settings.date ) {
			$.error( 'Date is not defined.' );
		}

		// Throw error if date is set incorectly.
		if ( !Date.parse( settings.date ) ) {
			$.error( 'Incorrect date format, it should look like this, 12/24/2012 12:00:00.' );
		}

		// Save container.
		var container = this;

		/**
		 * Change client's local date to match offset timezone.
		 * @return {Object} Fixed Date object.
		 */
		var currentDate = function() {
			// Get client's current date.
			var date = new Date();

			// Turn date to utc.
			var utc = date.getTime() + ( date.getTimezoneOffset() * 60000 );

			// Set new Date object.
			var new_date = new Date( utc + ( 3600000 * settings.offset ) )

			return new_date;
		};

		/**
		 * Main elegantCountDownTimer function that calculates everything.
		 */
		function elegantCountdown() {
			var target_date = new Date( settings.date ), // Set target date
				current_date = currentDate(); // Get fixed current date.

			// Difference of dates.
			var difference = target_date - current_date;

			// If difference is negative than it's pass the target date.
			if ( difference < 0 ) {
				// Stop timer.
				clearInterval( interval );

				if ( callback && typeof callback === 'function' ) callback();

				return;
			}

			// Basic math variables.
			var _second = 1000,
				_minute = _second * 60,
				_hour = _minute * 60,
				_day = _hour * 24;

			// Calculate dates.
			var days = Math.floor( difference / _day ),
				hours = Math.floor( ( difference % _day ) / _hour ),
				minutes = Math.floor( ( difference % _hour ) / _minute ),
				seconds = Math.floor( ( difference % _minute ) / _second );

			// Fix dates so that it will show two digets.
			days = ( String( days ).length >= 2 ) ? days : '0' + days;
			hours = ( String( hours ).length >= 2 ) ? hours : '0' + hours;
			minutes = ( String( minutes ).length >= 2 ) ? minutes : '0' + minutes;
			seconds = ( String( seconds ).length >= 2 ) ? seconds : '0' + seconds;

			// Based on the date change the refrence wording.
			var ref_days = ( days === 1 ) ? 'day' : 'days',
				ref_hours = ( hours === 1 ) ? 'hour' : 'hours',
				ref_minutes = ( minutes === 1 ) ? 'minute' : 'minutes',
				ref_seconds = ( seconds === 1 ) ? 'second' : 'seconds';

			// Set to DOM.
			container.find( '.days' ).text( days );
			container.find( '.hours' ).text( hours );
			container.find( '.minutes' ).text( minutes );
			container.find( '.seconds' ).text( seconds );

			container.find( '.days-label' ).text( ref_days );
			container.find( '.hours-label' ).text( ref_hours );
			container.find( '.minutes-label' ).text( ref_minutes );
			container.find( '.seconds-label' ).text( ref_seconds );
		};

		// Start.
		var interval = setInterval( elegantCountdown, 1000 );
	};

	jQuery( document ).ready( function() {
		if ( jQuery( '.elegant-countdown-timer' ).length ) {
			jQuery( '.elegant-countdown-timer' ).each( function() {
				var elegantCountDown = jQuery( this ),
					date      = elegantCountDown.attr( 'data-date' ),
					offset    = elegantCountDown.attr( 'data-offset' );

				elegantCountDown.elegantCountDownTimer( {
					date: date,
					offset: offset
				} );
			} );
		}
	} );

	// Render countdown timer on frontend editor when settings updated.
	jQuery( document ).on ( 'renderCountdownTimer', function() {
		jQuery( 'body' ).find( '.elegant-countdown-timer' ).each( function() {
			var elegantCountDown = jQuery( this ),
				date      = elegantCountDown.attr( 'data-date' ),
				offset    = elegantCountDown.attr( 'data-offset' );

			elegantCountDown.elegantCountDownTimer( {
				date: date,
				offset: offset
			} );
		} );
	} );
} )( jQuery );
