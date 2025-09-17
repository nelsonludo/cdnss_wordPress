/* global _SEARCHWP_LIVE_SEARCH */

( function($) {

    'use strict';

    const app = {

        /**
         * Init.
         *
         * @since 1.8.0
         */
        init: () => {

            $( app.ready );
        },

        /**
         * Document ready
         *
         * @since 1.8.0
         */
        ready: () => {

            app.events();
        },

        /**
         * Extension page events.
         *
         * @since 1.8.0
         */
        events: () => {

            $( '.swp-collapse--header .swp-arrow' ).on( 'click', app.clickToggle );
        },

        /**
         * Callback for clicking on a collapse toggling element.
         *
         * @since 1.8.0
         */
        clickToggle: (e) => {
            $( e.target ).closest( '.swp-collapse' ).toggleClass( 'swp-closed' );
        },
    };

    app.init();

    window.searchwp = window.searchwp || {};

    window.searchwp.Collapse = app;

}( jQuery ) );
