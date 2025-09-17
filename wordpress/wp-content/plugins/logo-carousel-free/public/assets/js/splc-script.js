jQuery(document).ready(function ($) {

	// The swiper carousel starts to play when  the carousel comes to viewport of screen.
	function carousel_starts_on_screen(splc_container_id, splcSwiper) {
		splcSwiper.autoplay.stop();

		// Get the section to trigger the script
		const $spLogoCarouselSection = $('#' + splc_container_id + '.logo-carousel-free-area');
		$(window).on('scroll', function () {
			// Get the position of the section relative to the document
			const sectionOffset = $spLogoCarouselSection.offset();
			const sectionHeight = $spLogoCarouselSection.height();

			// Calculate the position of the section relative to the viewport
			const sectionTop = sectionOffset.top - $(window).scrollTop();
			const sectionBottom = sectionTop + sectionHeight;

			// Check if the section is within the viewport
			const isSectionVisible = sectionTop <= $(window).height() && sectionBottom >= 0;

			if (isSectionVisible) {
				splcSwiper.autoplay.start();
			} else {
				splcSwiper.autoplay.stop();
			}
		});
	}

	$('.sp-lc-container').each(function (index) {
		var splc_container = $(this),
			splc_container_id = splc_container.attr('id'),
			spLogoCarousel = $('#' + splc_container_id + ' .sp-logo-carousel'),
			spLogoCarouselData = spLogoCarousel.data('carousel'),
			spLogoCarouselStartsOnscreen = spLogoCarousel.data('carousel-starts-onscreen');
		// Check the length of carousel settings and already initialized or not.
		if (spLogoCarousel.length > 0 && !$('#' + splc_container_id + ' .sp-logo-carousel[class*="-initialized"]').length > 0) {
			// Carousel Swiper for Standard mode.
			var splcSwiper = new Swiper('#' + splc_container_id + ' .sp-logo-carousel', {
				speed: spLogoCarouselData.speed,
				slidesPerView: spLogoCarouselData.slidesPerView.mobile,
				spaceBetween: spLogoCarouselData.spaceBetween,
				loop: spLogoCarouselData.infinite,
				loopFillGroupWithBlank: true,
				simulateTouch: spLogoCarouselData.simulateTouch,
				allowTouchMove: spLogoCarouselData.allowTouchMove,
				freeMode: spLogoCarouselData.freeMode,
				autoHeight: spLogoCarouselData.adaptiveHeight,
				keyboard: {
					enabled: spLogoCarouselData.carousel_accessibility,
				},
				mousewheel: spLogoCarouselData.swipeToSlide,
				pagination:
					spLogoCarouselData.pagination == true
						? {
							el: '#' + splc_container_id + ' .swiper-pagination',
							clickable: true,
							renderBullet: function (index, className) {
								return '<span class="' + className + '"></span>';
							}
						}
						: false,
				autoplay: {
					delay: spLogoCarouselData.autoplay_speed
				},
				navigation:
					spLogoCarouselData.navigation == true
						? {
							nextEl: '#' + splc_container_id + ' .sp-lc-button-next',
							prevEl: '#' + splc_container_id + ' .sp-lc-button-prev'
						}
						: false,
				breakpoints: {
					576: {
						slidesPerView: spLogoCarouselData.slidesPerView.mobile_landscape,
					},
					768: {
						slidesPerView: spLogoCarouselData.slidesPerView.tablet,
					},
					992: {
						slidesPerView: spLogoCarouselData.slidesPerView.desktop,
					},
					1200: {
						slidesPerView: spLogoCarouselData.slidesPerView.lg_desktop,
					},
				},
				fadeEffect: {
					crossFade: true
				},
			});
			if (spLogoCarouselData.autoplay === false) {
				splcSwiper.autoplay.stop();
			}
			if (spLogoCarouselData.stop_onHover && spLogoCarouselData.autoplay) {
				$(spLogoCarousel).on({
					mouseenter: function () {
						splcSwiper.autoplay.stop();
					},
					mouseleave: function () {
						splcSwiper.autoplay.start();
					}
				});
			}

			// Run the function before checking the option is true.
			if (spLogoCarouselStartsOnscreen) {
				carousel_starts_on_screen(splc_container_id, splcSwiper);
			}

			$(window).on("resize", function () {
				splcSwiper.update();
			});
			$(window).trigger("resize");
		}

	});

	jQuery('body').find('.sp-logo-carousel.lcp-preloader').each(function () {
		var logo_carousel_id = $(this).attr('id'),
			parents_class = jQuery('#' + logo_carousel_id).parent('.logo-carousel-free-area'),
			parents_siblings_id = parents_class.find('.sp-logo-carousel-preloader').attr('id');
		$(document).ready(function () {
			$('#' + parents_siblings_id).animate({ opacity: 0 }, 600).remove();
		});
	});

	// Add class for gutenberg block.
	$('.logo-carousel-free-area').addClass('splc-logo-carousel-loaded');

});
