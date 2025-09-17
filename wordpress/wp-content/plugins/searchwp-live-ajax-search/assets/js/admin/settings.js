/* global _SEARCHWP_LIVE_SEARCH */

( function ($) {

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
         * Page events.
         *
         * @since 1.8.0
         */
        events: () => {

            $( '#swp-settings-save' ).on( 'click', app.saveSettings );

            app.UIEvents();

			app.licenseEvents();
        },

        /**
         * Save form settings.
         *
         * @since 1.8.0
         */
        saveSettings: () => {

            const settings = {
				// General settings.
				'enable-live-search':               $( 'input[name=enable-live-search]' ).is( ':checked' ),
				'include-frontend-css':             $( 'select[name=include-frontend-css]' ).val(),
				'results-pane-position':            $( 'select[name=results-pane-position]' ).val(),
				'results-pane-auto-width':          $( 'input[name=results-pane-auto-width]' ).is( ':checked' ),
				'hide-announcements':               $( 'input[name=hide-announcements]' ).is( ':checked' ),
                // Theme settings.
				'swp-layout-theme':                 $( 'input[name=swp-layout-theme]:checked' ).val(),
				'swp-image-size':                   $( 'select[name=swp-image-size]' ).val(),
				'swp-no-results-message':           $( 'input[name=swp-no-results-message]' ).val(),
				'swp-description-enabled':          $( 'input[name=swp-description-enabled]' ).is( ':checked' ),
				'swp-results-per-page':             $( 'input[name=swp-results-per-page]' ).val(),
				'swp-min-chars':                    $( 'input[name=swp-min-chars]' ).val(),
				'swp-title-color':                  $( 'input[name=swp-title-color]' ).val(),
				'swp-title-font-size':              $( 'input[name=swp-title-font-size]' ).val(),
				// eCommerce settings.
				'swp-price-enabled':                $( 'input[name=swp-price-enabled]' ).is( ':checked' ),
				'swp-price-color':                  $( 'input[name=swp-price-color]' ).val(),
				'swp-price-font-size':              $( 'input[name=swp-price-font-size]' ).val(),
				'swp-add-to-cart-enabled':          $( 'input[name=swp-add-to-cart-enabled]' ).is( ':checked' ),
				'swp-add-to-cart-background-color': $( 'input[name=swp-add-to-cart-background-color]' ).val(),
				'swp-add-to-cart-font-color':       $( 'input[name=swp-add-to-cart-font-color]' ).val(),
				'swp-add-to-cart-font-size':        $( 'input[name=swp-add-to-cart-font-size]' ).val(),
            };

            const $saveButton = $( '#swp-settings-save' );

            const data = {
                _ajax_nonce: _SEARCHWP_LIVE_SEARCH.nonce,
                action: _SEARCHWP_LIVE_SEARCH.prefix + 'save_settings',
                settings: JSON.stringify( settings ),
            };

            const $enabledInputs = $( '.swp-content-container button:not([disabled]), .swp-content-container input:not([disabled])' );

            $enabledInputs.attr( 'disabled','disabled' );
            $saveButton.addClass( 'swp-button--processing' );

            $.post(
				ajaxurl,
				data,
				( response ) => {
					$enabledInputs.removeAttr( 'disabled' );
					$saveButton.removeClass( 'swp-button--processing' );

					if ( response.success ) {
						$saveButton.addClass( 'swp-button--completed' );
						setTimeout( () => { $saveButton.removeClass( 'swp-button--completed' ) }, 1500 );
					}
            	}
			);
        },

        /**
         * Page UI events.
         *
         * @since 1.8.0
         */
        UIEvents: () => {

            $( '[name="swp-layout-theme"]' ).on( 'change', (e) => {
				const theme              = e.target.value;
				const imageSizeChoicesJs = document.querySelector( 'select[name=swp-image-size]' ).data.choicesjs;
				const $description       = $( 'input[name=swp-description-enabled]' );
				const $price             = $( 'input[name=swp-price-enabled]' );
				const $addToCart         = $( 'input[name=swp-add-to-cart-enabled]' );
				const $preview           = $( '.searchwp-live-search-results-container' );
				const $imagePreview      = $( '.searchwp-live-search-result--img' );
				const $descPreview       = $( '.searchwp-live-search-result--desc' );
				const $pricePreview      = $( '.searchwp-live-search-result--price' );
				const $addToCartPreview  = $( '.searchwp-live-search-result--add-to-cart' );

				switch ( theme ) {
					case 'minimal':
						imageSizeChoicesJs.setChoiceByValue( '' );
						$description.prop( 'checked', false );
						$price.prop( 'checked', false );
						$addToCart.prop( 'checked', false );
						$descPreview.hide();
						$imagePreview.hide();
						$pricePreview.hide();
						$addToCartPreview.hide();
						$preview.removeClass( 'swp-ls--img-sm swp-ls--img-m swp-ls--img-l' );
						break;
					case 'medium':
						imageSizeChoicesJs.setChoiceByValue( '' );
						$description.prop( 'checked', true );
						$price.prop( 'checked', false );
						$addToCart.prop( 'checked', false );
						$descPreview.show();
						$imagePreview.hide();
						$pricePreview.hide();
						$addToCartPreview.hide();
						$preview.removeClass( 'swp-ls--img-sm swp-ls--img-m swp-ls--img-l' );
						break;
					case 'rich':
						imageSizeChoicesJs.setChoiceByValue( 'small' );
						$description.prop( 'checked', true );
						$price.prop( 'checked', false );
						$addToCart.prop( 'checked', false );
						$descPreview.show();
						$imagePreview.show();
						$pricePreview.hide();
						$addToCartPreview.hide();
						$preview.removeClass( 'swp-ls--img-m swp-ls--img-l' ).addClass( 'swp-ls--img-sm' );
						break;
					case 'custom':
						break;
				}
			} );

			$( '[name="swp-description-enabled"]' ).on( 'change', (e) => {
				$( '.searchwp-live-search-result--desc' ).toggle( e.target.checked );
			} );

			$( '[name="swp-image-size"]' ).on( 'change', (e) => {

				const $preview     = $( '.searchwp-live-search-results-container' );
				const $resultImage = $( '.searchwp-live-search-result--img' );

				switch ( e.target.value ) {

					case 'small':
						$preview.removeClass( 'swp-ls--img-m swp-ls--img-l' ).addClass( 'swp-ls--img-sm' );
						break;

					case 'medium':
						$preview.removeClass( 'swp-ls--img-sm swp-ls--img-l' ).addClass( 'swp-ls--img-m' );
						break;

					case 'large':
						$preview.removeClass( 'swp-ls--img-sm swp-ls--img-m' ).addClass( 'swp-ls--img-l' );
						break;
				}
				$resultImage.toggle( ! ( e.target.value === '' || e.target.value === 'none' ) );
			} );

			$('[name="swp-price-enabled"]').on('change', (e) => {
				$('.searchwp-live-search-result--price').toggle( e.target.checked );
				$( '[name="swp-layout-theme"][value="custom"]' ).prop( 'checked', true );
			} );

			$('[name="swp-add-to-cart-enabled"]').on('change', (e) => {
				$('.searchwp-live-search-result--add-to-cart').toggle( e.target.checked );
				$( '[name="swp-layout-theme"][value="custom"]' ).prop( 'checked', true );
			} );
        },

		/**
		 * License events.
		 *
		 * @since 1.8.0
		 */
		licenseEvents: () => {

			$( '#swp-license-activate' ).on( 'click', app.activateLicense );
		},

		/**
		 * Callback for clicking "Activate License" button.
		 *
		 * @since 1.8.0
		 */
		activateLicense: function (e) {

			e.preventDefault();

			$( '#swp-license-error-msg' ).hide().empty();

			$( '.swp-content-container button' ).attr( 'disabled','disabled' );
			$( '#swp-license-activate' ).addClass( 'swp-button--processing' );

			$.post(
				ajaxurl,
				{
					_ajax_nonce: _SEARCHWP_LIVE_SEARCH.nonce,
					action: _SEARCHWP_LIVE_SEARCH.prefix + 'license_activate',
					license_key: $( '#swp-license' ).val(),
				},
				app.activateLicenseProcessResponse
			);
		},

		/**
		 * Process response from activating license.
		 *
		 * @since 1.8.0
		 *
		 * @param response
		 */
		activateLicenseProcessResponse: function (response) {

			if ( response.success ) {
				if (
					response.data.swp_activated &&
					response.data.swp_activated === true &&
					response.data.redirect !== ''
				) {
					window.location.href = response.data.redirect;
				}
			} else {
				$( '#swp-license-error-msg' ).text( response.data.message ).show();
				$( '.swp-content-container button' ).removeAttr( 'disabled' );
				$( '#swp-license-activate' ).removeClass( 'swp-button--processing' );
			}
		}

	};

    app.init();

    window.searchwp = window.searchwp || {};

    window.searchwp.AdminSearchFormsPage = app;

}( jQuery ) );
