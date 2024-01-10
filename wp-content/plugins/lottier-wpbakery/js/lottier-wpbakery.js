/**
 * Lottier for Wpbakery
 * Lottie animations in just a few clicks without writing a single line of code
 * Exclusively on https://1.envato.market/lottier-wpbakery
 *
 * @encoding        UTF-8
 * @version         1.1.5
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

window.addEventListener( "DOMContentLoaded", ( event ) => {

    /**
     * Get player on screen state
     * @returns {boolean}
     */
    function playerOnScreen ( lottierPlayer ) {

        let playerClientRect = lottierPlayer.getBoundingClientRect();
        let is_top_visible = window.innerHeight - playerClientRect.top > 0;
        let is_bot_visible = playerClientRect.bottom > 0;

        return is_top_visible && is_bot_visible

    }

    /**
     * Run or stop playback and return playback state
     * @param lottierPlayer
     * @returns {boolean}
     */
    function playbackOnScreen( lottierPlayer ) {

        let playState = false;
        if ( playerOnScreen( lottierPlayer ) ) {

            lottierPlayer.play();
            playState = true;

        } else {

            lottierPlayer.pause();
            playState = false;

        }

        return playState;

    }

    /**
     * Set playback position during page scrolling
     * @param lottierPlayer
     * @param playMode
     */
    function playbackPercent( lottierPlayer, playMode ) {

        const playerClientRect = lottierPlayer.getBoundingClientRect();
        let percent = ( playerClientRect.y + playerClientRect.height)  * 100 / ( window.innerHeight + playerClientRect.height);
        percent = playMode === "scroll_forward" ? Math.abs( 100 - Math.floor( percent ) ) : Math.floor( percent );
        lottierPlayer.seek( `${ percent }%` );

    }

    function runPlayers(){
        const lottiers = document.querySelectorAll( ".mdp-lottier-player" );

        for ( const lottier of lottiers ) {

            const lottierPlayer = lottier.querySelector( "#mdp-lottier-" + lottier.getAttribute( "data-id" ) );
            const playMode = lottier.getAttribute( "data-autoplay" );
            const finishAnimationBeforePause = lottier.getAttribute( "data-finish-before-pause" );

            lottierPlayer.setSpeed( parseInt( lottier.getAttribute( "data-speed" ) ));
            lottierPlayer.loop = "true" === lottier.getAttribute( "data-loop" );
            lottierPlayer.controls = "true" === lottier.getAttribute( "data-controls" );
            lottierPlayer.mode = lottier.getAttribute( "data-mode" );

            if ( "autoplay" === playMode ) {
                lottierPlayer.autoplay = true;
                lottierPlayer.play();
            }

            if ( [ "scroll_forward", "scroll_backward" ].includes( playMode ) ) {

                playbackPercent( lottierPlayer, playMode );

                window.addEventListener( 'scroll', ( e ) => {

                    playbackPercent( lottierPlayer, playMode );

                } );

            }

            if ( "visible" === playMode ) {

                let playState = false;
                if ( playerOnScreen( lottierPlayer ) !== playState ) {

                    playState = playbackOnScreen( lottierPlayer );

                }

                window.addEventListener( 'scroll', ( e ) => {

                    if ( playerOnScreen( lottierPlayer ) === playState ) { return; }
                    playState = playbackOnScreen( lottierPlayer );

                } );

            }

            if ( "section" === lottier.getAttribute( "data-autoplay") || "hover" === playMode ) {


                function pauseOnLoopEndHandler() {
                    lottierPlayer.pause();
                }

                lottierPlayer.addEventListener( "mouseover", function() {

                    lottierPlayer.play();

                    if ( finishAnimationBeforePause === 'yes' ) {
                        lottierPlayer.removeEventListener( 'loop', pauseOnLoopEndHandler );
                    }

                } );

                lottierPlayer.addEventListener( "mouseout", function() {

                    if ( finishAnimationBeforePause === 'yes' ) {
                        lottierPlayer.addEventListener( 'loop', pauseOnLoopEndHandler );
                    } else  {
                        lottierPlayer.pause();
                    }

                } );

            }

            if ( "click" === playMode ){

                let startPlay = 0;
                lottierPlayer.addEventListener( "click", function() {

                    if ( startPlay === 0 ) {

                        lottierPlayer.play();
                        startPlay = 1;

                    } else {

                        lottierPlayer.pause();
                        startPlay = 0;

                    }

                } );

            }

        }
    }

    setTimeout( runPlayers, 300 );

} );
