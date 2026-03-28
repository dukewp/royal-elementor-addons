( function( $, elementor ) {

	"use strict";

	var actionTargetProductId;
	var finalURL = window.location.href;
	var gridLoadMoreItems;
	var initialItems;
	var paramsObj = {};


	var WprElements = {

		gridInitialItems: {},

		init: function() {
			// Widget handlers are self-registered from per-widget JS files
			// Only the global section handler is registered here
			window.elementorFrontend.hooks.addAction( 'frontend/element_ready/global', WprElements.widgetSection );

			// Remove Mega Menu Templates from "Edit with Elementor"
			WprElements.changeAdminBarMenu();
		},

		widgetSection: function( $scope ) {

			if ( $scope.attr('data-wpr-particles') || $scope.find('.wpr-particle-wrapper').attr('data-wpr-particles-editor') ) {
				particlesEffect();
            }

			if ( $scope.hasClass('wpr-jarallax') || $scope.hasClass('wpr-jarallax-yes') ) {
				observeParallax($scope);
			}

			if ( $scope.hasClass('wpr-parallax-yes') ) {
				parallaxMultiLayer();
			}

			if ( $scope.hasClass('wpr-sticky-section-yes') && typeof WprConfig !== 'undefined' && WprConfig.sticky_section === 'on'  ) {
				$(document).ready(function() {
					stickySection();
				});
			}

			function stickySection() {
			    var positionType = !WprElements.editorCheck() ? $scope.attr('data-wpr-position-type') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-position-type'),
				    positionLocation = !WprElements.editorCheck() ? $scope.attr('data-wpr-position-location') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-position-location'),
				    positionOffset = !WprElements.editorCheck() ? $scope.attr('data-wpr-position-offset') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-position-offset'),
				    viewportWidth = $('body').prop('clientWidth') + 17,
				    availableDevices = !WprElements.editorCheck() ? $scope.attr('data-wpr-sticky-devices') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-devices'),
				    activeDevices = !WprElements.editorCheck() ? $scope.attr('data-wpr-active-breakpoints') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-active-breakpoints'),
				    stickySectionExists = $scope.hasClass('wpr-sticky-section-yes') || $scope.find('.wpr-sticky-section-yes-editor') ? true : false,
				    positionStyle,
                    adminBarHeight,
					stickyEffectsOffset = $(window).scrollTop(),
					stickyHideDistance = 0,
					$window = $(window),
					prevScrollPos = $window.scrollTop(),
				    stickyHeaderFooter = '',
					stickyAnimation = 'none',
					stickyAnimationHide = '',
					headerFooterZIndex = !WprElements.editorCheck() ? $scope.attr('data-wpr-z-index') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-z-index'),
					stickType = !WprElements.editorCheck() ? $scope.attr('data-wpr-sticky-type') : $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-type'),
                    hiddenHeaderClass = $scope.next().hasClass('e-con') ? 'wpr-hidden-header-flex' : 'wpr-hidden-header',
					distanceFromTop = $scope.offset().top,
					windowHeight = $(window).height(),
					elementHeight = $scope.outerHeight(true),
					distanceFromBottom = $(document).height() - (distanceFromTop + elementHeight),
					offsetTop;


					if ( $scope.data('settings') && $scope.data('settings').sticky_animation ) {
						stickyAnimation = $scope.data('settings').sticky_animation;
					} else {
						stickyAnimation = $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-animation');
					}

					var stickyAnimDuration = $scope.attr('data-wpr-animation-duration') ? $scope.attr('data-wpr-animation-duration') + 's' : 500 + 's';

				if ( $scope.closest('div[data-elementor-type="wp-post"]').length > 0 ) {
					stickyHeaderFooter = $scope.closest('div[data-elementor-type="wp-post"]');
				} else if ( $scope.closest('div[data-elementor-type="header"]').length > 0 ) {
					stickyHeaderFooter = $scope.closest('div[data-elementor-type="header"]');
				} else if ( $scope.closest('div[data-elementor-type="footer"]').length > 0 ) {
					stickyHeaderFooter = $scope.closest('div[data-elementor-type="footer"]');
				}

			    if ( !$scope.find('.wpr-sticky-section-yes-editor').length) {
			        positionType = $scope.attr('data-wpr-position-type');
			        positionLocation = $scope.attr('data-wpr-position-location');
			        positionOffset = $scope.attr('data-wpr-position-offset');
			        availableDevices = $scope.attr('data-wpr-sticky-devices');
			        activeDevices = $scope.attr('data-wpr-active-breakpoints');
					headerFooterZIndex = $scope.attr('data-wpr-z-index');
			    }

                if ( 'top' === positionLocation && 'auto' === $scope.css('top') ) {
                    var offsetTop = $scope.data('wpr-position-offset');
                    $scope.css('top', offsetTop);
                } else {
                    var offsetTop = $scope.data('wpr-position-offset');
                }

			    if ( 0 == availableDevices.length ) {
			        positionType = 'relative';
			    }

			    if ( WprElements.editorCheck() && availableDevices ) {
			        var attributes = $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-sticky-devices');
			        $scope.attr('data-wpr-sticky-devices', attributes);
			        availableDevices = $scope.attr('data-wpr-sticky-devices');
			    }

			    changePositionType();
			    changeAdminBarOffset();

			    $(window).smartresize(function() {
					recalculateVariables();
			    });

				// Debounce function
				function debounce(func, wait) {
					let timeout;
					return function(...args) {
						const context = this;
						clearTimeout(timeout);
						timeout = setTimeout(() => func.apply(context, args), wait);
					};
				}// Adjust the debounce delay as needed

				// Create a MutationObserver instance
				if ( !WprElements.editorCheck() ) {
					if ( 'yes' !== $scope.data('wpr-replace-header') && 'yes' !== $scope.data('wpr-sticky-hide') ) {
						// Function to be called when mutations are observed
						const handleMutations = debounce(function(mutationsList) {
							// if (!$scope[0] || !document.body.contains($scope[0])) return;
							// var isGTranslate = function(node) {
							// 	if (!node || !node.getAttribute) return false;
							// 	var id = node.getAttribute('id') || '';
							// 	if (id.indexOf('google_translate') !== -1 || id.indexOf('gtranslate') !== -1) return true;
							// 	return node.closest && (node.closest('#google_translate_element') || node.closest('.gtranslate'));
							// };
							for (let mutation of mutationsList) {
								// if (mutation.type === 'childList' && !isGTranslate(mutation.target)) {
								if (mutation.type === 'childList') {
									$(window).trigger('scroll');
									recalculateVariables();
									break;
								}
							}
						}, 100); 
	
						const observer = new MutationObserver(handleMutations);
						observer.observe(document.body, { childList: true, subtree: true });
					}
				}

				$(window).scroll(function() {
					if ($scope && $scope.css('position') === 'relative') {
						recalculateVariables();
					}
				});
			    
			    if (!stickySectionExists) {
			        positionStyle = 'relative';
			    }

				function recalculateVariables() {
					distanceFromTop = $scope.offset().top;
					windowHeight = $(window).height(),
					elementHeight = $scope.outerHeight(true),
					distanceFromBottom = $(document).height() - (distanceFromTop + elementHeight);
					
			        viewportWidth = $('body').prop('clientWidth') + 17;
					
					changePositionType();
				}

			    function changePositionType() {
			        if ( !$scope.hasClass('wpr-sticky-section-yes') && !$scope.find('.wpr-sticky-section-yes-editor') ) {
			            positionStyle = 'relative';
			            return;
			        }

                    var desktopDimension = activeDevices.includes('widescreen_sticky') ? 2400 : 4000
			        var checkDevices = [['mobile_sticky', 768], ['mobile_extra_sticky', 881], ['tablet_sticky', 1025], ['tablet_extra_sticky', 1201], ['laptop_sticky', 1216],  ['desktop_sticky', 4000], ['widescreen_sticky', 4000]];
			        var emptyVariables = [];

			        var checkedDevices = checkDevices.filter((item, index) => {
			            return activeDevices.indexOf(item[0]) != -1;
			        }).reverse();
			        
			        checkedDevices.forEach((device, index) => {
						if ( device[1] > viewportWidth ) {
							var deviceName = device[0].replace("_sticky", "");

							if ( 'desktop' == deviceName ) {
								if ( $scope.data('settings') ) {
									stickyEffectsOffset = distanceFromTop + $scope.data('settings').wpr_sticky_effects_offset;
								} else {
									stickyEffectsOffset = distanceFromTop + $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-offset-settings');
								}
							} else {
								if ( $scope.data('settings') ) {
									stickyEffectsOffset = distanceFromTop + $scope.data('settings')['wpr_sticky_effects_offset_' + deviceName];
								} else {
									stickyEffectsOffset = distanceFromTop + $scope.find('.wpr-sticky-section-yes-editor').attr('data-wpr-offset-settings');
								}
							}

							if ( availableDevices.indexOf(device[0]) === -1 ) {
								positionStyle = activeDevices?.indexOf(device[0]) !== -1 ? 'relative' : (emptyVariables[index - 1] ? emptyVariables[index - 1] : positionType);
								// positionStyle = activeDevices && activeDevices.indexOf(device[0]) !== -1 ? 'static' : (emptyVariables[index - 1] ? emptyVariables[index - 1] : positionType);
								emptyVariables[index] = positionStyle;
							} else if ( availableDevices.indexOf(device[0]) !== -1 ) {
								positionStyle = positionType;
							}
						}
			        });

					var handleScroll = function() {
						let scrollPos = $window.scrollTop();
						
						if ( 'fixed' != positionStyle ) {
							if ( 'top' === positionLocation ) {
								if ( scrollPos > distanceFromTop) {
									applyPosition();
								} else if ( scrollPos <= distanceFromTop ) {
									$scope.css({'position': 'relative' });
								}
							}

							if ( 'bottom' === positionLocation ) {
								if ( scrollPos + windowHeight <= $(document).height() - distanceFromBottom ) {
									applyPosition();
								} else {
									$scope.css({'position': 'relative' });
								}
							}
						}

						if ( 'relative' !== positionStyle ) {
							if ( scrollPos > stickyEffectsOffset ) {
								if ( 'yes' == $scope.data('wpr-replace-header') ) {

									if ( 'yes' === $scope.data('wpr-sticky-hide') ) {

										if ( scrollPos >= distanceFromTop ) {
											$scope.addClass('wpr-visibility-hidden');
										}

										if ( scrollPos < prevScrollPos) {
											$scope.next().addClass(hiddenHeaderClass).addClass('wpr-' + stickyAnimation + '-in');
										}
									} else {
										$scope.addClass('wpr-visibility-hidden');
										$scope.next().addClass(hiddenHeaderClass).addClass('wpr-' + stickyAnimation + '-in');
									}
								} else {
									$scope.addClass('wpr-sticky-header');
								}
							} else if ( scrollPos <= stickyEffectsOffset ) {
								if ( 'yes' == $scope.data('wpr-replace-header') ) {
									$scope.next().removeClass(hiddenHeaderClass);
									$scope.removeClass('wpr-visibility-hidden');
									$scope.next().removeClass('wpr-' + stickyAnimation + '-in');
								} else {
									$scope.removeClass('wpr-sticky-header');
								}
							}
						}

						if ( 'yes' === $scope.data('wpr-sticky-hide') ) {
							if ( scrollPos >= distanceFromTop ) {
								if ( scrollPos < prevScrollPos ) {
									// Scrolling up
									if ( 'yes' === $scope.data('wpr-replace-header') ) {
										$scope.next().removeClass('wpr-' + stickyAnimation + '-out');
										$scope.next().addClass('wpr-' + stickyAnimation + '-in');
									} else {
										$scope.removeClass('wpr-' + stickyAnimation + '-out');
										$scope.addClass('wpr-' + stickyAnimation + '-in');
									}
								} else {
									// Scrolling down or no direction change
									if ( 'yes' === $scope.data('wpr-replace-header') ) {
										$scope.next().removeClass('wpr-' + stickyAnimation + '-in');
										$scope.next().addClass('wpr-' + stickyAnimation + '-out');
									} else {
										$scope.removeClass('wpr-' + stickyAnimation + '-in');
										$scope.addClass('wpr-' + stickyAnimation + '-out');
									}
								}
							}

							if ( 0 === $(window).scrollTop() && 'sticky' === positionType ) {
								$scope.css('position', 'relative');
							}
						}

						// Clear any previous timeout
						clearTimeout(scrollEndTimeout);
					  
						// Set a new timeout to update prevScrollPos after 150 milliseconds (adjust the delay as needed)
						scrollEndTimeout = setTimeout(() => {
						  prevScrollPos = scrollPos;
						}, 10);
					}
					
					if ( 'sticky' == positionStyle ) {
						$(window).scroll(handleScroll);
					} else if ( 'fixed' == positionStyle ) {
						applyPosition();
						$(window).scroll(handleScroll);
					}

					if ( 'yes' == $scope.data('wpr-replace-header') ) {
                        if ( 0 != $scope.next().length ) {
                            $scope.next().get(0).style.setProperty('--wpr-animation-duration', stickyAnimDuration);
                        }
					}

					let scrollEndTimeout;
			    }
			    
			    function applyPosition() {
			        var bottom = +window.innerHeight - (+$scope.css('top').slice(0, -2) + $scope.height());
			        var top = +window.innerHeight - (+$scope.css('bottom').slice(0, -2) + $scope.height());

					if ( 'yes' === $scope.data('wpr-sticky-hide') && prevScrollPos < $window.scrollTop() ) {
						return;
					}

					if ( '' == stickType ) {
						stickType = 'fixed';
					}
					
					$scope.css({'position': stickType });

                    if ( $('.wpr-close-cart span').length && !WprElements.editorCheck() ) {
                        WprElements.closeSideBarOnReplace($scope, 200);
                    }
			    }

			    function changeAdminBarOffset() {
			        if ( $('#wpadminbar').length ) {
			            adminBarHeight = $('#wpadminbar').css('height').slice(0, $('#wpadminbar').css('height').length - 2);

			            if ( 'top'  ===  positionLocation && ( 'fixed' == $scope.css('position') ) ) {
			                $scope.css('top', +adminBarHeight + offsetTop + 'px');
			                $scope.css('bottom', 'auto');
			            }
			        }
			    }
			}

			function particlesEffect() {
				var elementType = $scope.data('element_type'),
					sectionID = encodeURIComponent($scope.data('id')),
					particlesJSON = ! WprElements.editorCheck() ? $scope.attr('data-wpr-particles') : $scope.find('.wpr-particle-wrapper').attr('data-wpr-particles-editor');

				if ( ('section' === elementType || 'container' === elementType) && undefined !== particlesJSON ) {
					// Frontend
					if ( ! WprElements.editorCheck() ) {
						$scope.prepend('<div class="wpr-particle-wrapper" id="wpr-particle-'+ sectionID +'"></div>');
	
						particlesJS('wpr-particle-'+ sectionID, $scope.attr('particle-source') == 'wpr_particle_json_custom' ? JSON.parse(particlesJSON) : modifyJSON(particlesJSON));

						setTimeout(function() {
							window.dispatchEvent(new Event('resize'));
						}, 500);

						setTimeout(function() {
							window.dispatchEvent(new Event('resize'));
						}, 1500);
					// Editor
					} else {
						if ( $scope.hasClass('wpr-particle-yes') ) {
							particlesJS( 'wpr-particle-'+ sectionID, $scope.find('.wpr-particle-wrapper').attr('particle-source') == 'wpr_particle_json_custom' ? JSON.parse(particlesJSON) : modifyJSON(particlesJSON));
	
							$scope.find('.elementor-column').css('z-index', 9);
	
							setTimeout(function() {
								window.dispatchEvent(new Event('resize'));
							}, 500);

							setTimeout(function() {
								window.dispatchEvent(new Event('resize'));
							}, 1500);
						} else {
							$scope.find('.wpr-particle-wrapper').remove();
						}
					}
				}
			}

			function modifyJSON(json) {
				var wpJson = JSON.parse(json),
					particles_quantity = ! WprElements.editorCheck() ? $scope.attr('wpr-quantity') : $scope.find('.wpr-particle-wrapper').attr('wpr-quantity'),
					particles_color = ! WprElements.editorCheck() ? $scope.attr('wpr-color') || '#000000' : $scope.find('.wpr-particle-wrapper').attr('wpr-color') ? $scope.find('.wpr-particle-wrapper').attr('wpr-color') : '#000000',
					particles_speed = ! WprElements.editorCheck() ? $scope.attr('wpr-speed') : $scope.find('.wpr-particle-wrapper').attr('wpr-speed'),
					particles_shape = ! WprElements.editorCheck() ? $scope.attr('wpr-shape') : $scope.find('.wpr-particle-wrapper').attr('wpr-shape'),
					particles_size = ! WprElements.editorCheck() ? $scope.attr('wpr-size')  : $scope.find('.wpr-particle-wrapper').attr('wpr-size');
				
				wpJson.particles.size.value = particles_size;
				wpJson.particles.number.value = particles_quantity;
				wpJson.particles.color.value = particles_color;
				wpJson.particles.shape.type = particles_shape;
				wpJson.particles.line_linked.color = particles_color;
				wpJson.particles.move.speed = particles_speed;
				
				return wpJson;
			}

			function parallaxBackground() {
				if ( ! WprElements.editorCheck() && $scope.hasClass('wpr-jarallax') ) {
					$scope.css('background-image', 'url("' + $scope.attr('bg-image') + '")');
					$scope.jarallax({
						type: $scope.attr('scroll-effect'),
						speed: $scope.attr('speed-data'),
					});
				} else if ( WprElements.editorCheck() ) {
					$scope.css('background-image', 'url("' + $scope.find('> .wpr-jarallax').attr('bg-image-editor') + '")');
					$scope.jarallax({
						type: $scope.find('> .wpr-jarallax').attr('scroll-effect-editor'),
						speed: $scope.find('> .wpr-jarallax').attr('speed-data-editor')
					});									
				}
			}

			function observeParallax($scope) {
				const observer = new IntersectionObserver((entries, obs) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							// Call your original function once
							parallaxBackground.call(this, $scope);
							obs.unobserve(entry.target); // Only trigger once
						}
					});
				}, {
					rootMargin: '0px',
					threshold: 0.1
				});
			
				observer.observe($scope[0]);
			}

			function parallaxMultiLayer() {
				if ( $scope.hasClass('wpr-parallax-yes') ) {
					var scene = document.getElementsByClassName('wpr-parallax-multi-layer');

					var parallaxInstance = Array.from(scene).map(item => {
						return new Parallax(item, {
							invertY: item.getAttribute('direction') == 'yes' ? true : false,
							invertX: item.getAttribute('direction') == 'yes' ? true : false,
							scalarX: item.getAttribute('scalar-speed'),
							scalarY: item.getAttribute('scalar-speed'),
							hoverOnly: true,
							pointerEvents: true
						});
					});
	
					parallaxInstance.forEach(parallax => {
						parallax.friction(0.2, 0.2);
					});
				}
				if ( ! WprElements.editorCheck() ) {						
					var newScene = [];

					document.querySelectorAll('.wpr-parallax-multi-layer').forEach((element, index) => {
						element.parentElement.style.position = "relative";
						element.style.position = "absolute";
						newScene.push(element);
						element.remove();
					});

					document.querySelectorAll('.wpr-parallax-ml-children').forEach((element, index) => {
						element.style.position = "absolute";
						element.style.top = element.getAttribute('style-top');
						element.style.left = element.getAttribute('style-left');
					});

					$('.wpr-parallax-yes').each(function(index) {
						$(this).append(newScene[index]);
					});
				}
			}
		}, // end widgetSection

		// Editor Check
		editorCheck: function() {
			return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
		},

		// Edith with Elementor - Admin Bar Menu
		changeAdminBarMenu: function() {
			let editLinks = $('#wp-admin-bar-elementor_edit_page-default');

			editLinks.children('li').each(function(){
				let $this = $(this),
					template = $this.children('a').children('span').first().text();

				if ( 0 === template.indexOf('wpr-mega-menu-item') ) {
					$this.remove();
				}
			});
		},

		changeActionTargetProductId: function(productId) {
			actionTargetProductId = productId;
		},

		changeFinalURL: function(url) {
			finalURL = url;
		},

		changeInitialItems: function(items, scopeId) {
			if (scopeId) {
				WprElements.gridInitialItems[scopeId] = items;
			} else {
				initialItems = items;
			}
		},

		getInitialItems: function(scopeId) {
			return (scopeId && WprElements.gridInitialItems[scopeId] !== undefined) ? WprElements.gridInitialItems[scopeId] : initialItems;
		},

		// Isotope Layout
		isotopeLayout: function ( settings, $response = '', $altScope = '', $scope = '' ) {
			if ( '' != $altScope ) {
				$scope = $altScope;
			}

			var grid = $scope.find( '.wpr-grid' ),
				item = grid.find( '.wpr-grid-item' ),
				itemVisible = item.filter( ':visible' ),
				layout = settings.layout,
				defaultLayout = settings.layout,
				mediaAlign = settings.media_align,
				mediaWidth = settings.media_width,
				mediaDistance = settings.media_distance,
				columns = 3,
				columnsMobile = 1,
				columnsMobileExtra,
				columnsTablet = 2,
				columnsTabletExtra,
				columnsDesktop = parseInt(settings.columns_desktop, 10),
				columnsLaptop,
				columnsWideScreen,
				gutterHr = settings.gutter_hr,
				gutterVr = settings.gutter_vr,
				gutterHrMobile = settings.gutter_hr_mobile,
				gutterVrMobile = settings.gutter_vr_mobile,
				gutterHrMobileExtra = settings.gutter_hr_mobile_extra,
				gutterVrMobileExtra = settings.gutter_vr_mobile_extra,
				gutterHrTablet = settings.gutter_hr_tablet,
				gutterVrTablet = settings.gutter_vr_tablet,
				gutterHrTabletExtra = settings.gutter_hr_tablet_extra,
				gutterVrTabletExtra = settings.gutter_vr_tablet_extra,
				gutterHrWideScreen = settings.gutter_hr_widescreen,
				gutterVrWideScreen = settings.gutter_vr_widescreen,
				gutterHrLaptop = settings.gutter_hr_laptop,
				gutterVrLaptop = settings.gutter_vr_laptop,
				contWidth = grid.width() + gutterHr - 0.3,
				// viewportWidth = $( 'body' ).prop( 'clientWidth' ),
				viewportWidth = $(window).outerWidth(),
				defaultLayout,
				transDuration = 400;

				if ( $response != '' ) {
					item = $response
				}

			// Get Responsive Columns
			var prefixClass = $scope.attr('class'),
				prefixClass = prefixClass.split(' ');

			for ( var i=0; i < prefixClass.length - 1; i++ ) {

				if ( -1 !== prefixClass[i].search(/mobile\d/) ) {
					columnsMobile = prefixClass[i].slice(-1);
				}

				if ( -1 !== prefixClass[i].search(/mobile_extra\d/) ) {
					columnsMobileExtra = prefixClass[i].slice(-1);
				}

				if ( -1 !== prefixClass[i].search(/tablet\d/) ) {
					columnsTablet = prefixClass[i].slice(-1);
				}

				if ( -1 !== prefixClass[i].search(/tablet_extra\d/) ) {
					columnsTabletExtra = prefixClass[i].slice(-1);
				}

				if ( -1 !== prefixClass[i].search(/widescreen\d/) ) {
					columnsWideScreen = prefixClass[i].slice(-1);
				}

				if ( -1 !== prefixClass[i].search(/laptop\d/) ) {
					columnsLaptop = prefixClass[i].slice(-1);
				}
			}

			var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
			var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
			var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
			var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
			var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
			var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

			var activeBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;

			// Mobile
			if ( MobileResp >= viewportWidth && activeBreakpoints.mobile != null ) {
				columns = columnsMobile;
				gutterHr = gutterHrMobile;
				gutterVr = gutterVrMobile;

			// Mobile Extra
			} else if ( MobileExtraResp >= viewportWidth && activeBreakpoints.mobile_extra != null ) {
				columns = (columnsMobileExtra) ? columnsMobileExtra : columnsTablet;
				gutterHr = gutterHrMobileExtra;
				gutterVr = gutterVrMobileExtra;

			// Tablet
			} else if ( TabletResp >= viewportWidth && activeBreakpoints.tablet != null ) {
				columns = columnsTablet;
				gutterHr = gutterHrTablet;
				gutterVr = gutterVrTablet;

			// Tablet Extra
			} else if ( TabletExtraResp >= viewportWidth && activeBreakpoints.tablet_extra != null ) {
				columns = (columnsTabletExtra) ? columnsTabletExtra : columnsTablet;
				gutterHr = gutterHrTabletExtra;
				gutterVr = gutterVrTabletExtra;

			// Laptop
			} else if ( LaptopResp >= viewportWidth && activeBreakpoints.laptop != null ) {
				columns = (columnsLaptop) ? columnsLaptop : columnsDesktop;
				gutterHr = gutterHrLaptop;
				gutterVr = gutterVrLaptop;

			// Desktop
			} else if ( wideScreenResp > viewportWidth ) {
				columns = columnsDesktop;
				gutterHr = settings.gutter_hr;
				gutterVr = settings.gutter_vr;
			}  else {
				columns = (columnsWideScreen) ? columnsWideScreen : columnsDesktop;
				gutterHr = gutterHrWideScreen;
				gutterVr = gutterVrWideScreen;
			}

			// Limit Columns for Higher Screens
			if ( columns > 8 ) {
				columns = 8;
			}

			if ( 'string' == typeof(columns) && -1 !== columns.indexOf('pro') ) {
				columns = 3;
			}

			contWidth = grid.width() + gutterHr - 0.3;

			// Calculate Item Width
			item.outerWidth( Math.floor( contWidth / columns - gutterHr ) );

			// Set Vertical Gutter
			item.css( 'margin-bottom', gutterVr +'px' );

			// Reset Vertical Gutter for 1 Column Layout
			if ( 1 === columns ) {
				item.last().css( 'margin-bottom', '0' );
			}

			// add last row & make all post equal height
			var maxTop = -1;
			itemVisible.each(function ( index ) {

				// define
				var thisHieght = $(this).outerHeight(),
					thisTop = parseInt( $(this).css( 'top' ) , 10 );

				// determine last row
				if ( thisTop > maxTop ) {
					maxTop = thisTop;
				}
				
			});

			if ( 'fitRows' === layout ) {
				itemVisible.each(function() {
					if ( parseInt( $(this).css( 'top' ) ) === maxTop  ) {
						$(this).addClass( 'rf-last-row' );
					}
				});
			}

			// List Layout
			if ( 'list' === layout ) {
				var imageHeight = item.find( '.wpr-grid-image-wrap' ).outerHeight();
					item.find( '.wpr-grid-item-below-content' ).css( 'min-height', imageHeight +'px' );

				if ( $( 'body' ).prop( 'clientWidth' ) < 480 ) {

					item.find( '.wpr-grid-media-wrap' ).css({
						'float' : 'none',
						'width' : '100%'
					});

					item.find( '.wpr-grid-item-below-content' ).css({
						'float' : 'none',
						'width' : '100%',
					});

					item.find( '.wpr-grid-image-wrap' ).css( 'padding', '0' );

					item.find( '.wpr-grid-item-below-content' ).css( 'min-height', '0' );

					if ( 'zigzag' === mediaAlign ) {
						item.find( '[class*="elementor-repeater-item"]' ).css( 'text-align', 'center' );
					}

				} else {

					if ( 'zigzag' !== mediaAlign ) {

						item.find( '.wpr-grid-media-wrap' ).css({
							'float' : mediaAlign,
							'width' : mediaWidth +'%'
						});

						var listGutter = 'left' === mediaAlign ? 'margin-right' : 'margin-left';
							item.find( '.wpr-grid-media-wrap' ).css( listGutter, mediaDistance +'px' );

						item.find( '.wpr-grid-item-below-content' ).css({
							'float' : mediaAlign,
							'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
						});

					// Zig-zag
					} else {
						// Even
						item.filter(':even').find( '.wpr-grid-media-wrap' ).css({
							'float' : 'left',
							'width' : mediaWidth +'%'
						});
						item.filter(':even').find( '.wpr-grid-item-below-content' ).css({
							'float' : 'left',
							'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
						});
						item.filter(':even').find( '.wpr-grid-media-wrap' ).css( 'margin-right', mediaDistance +'px' );

						// Odd
						item.filter(':odd').find( '.wpr-grid-media-wrap' ).css({
							'float' : 'right',
							'width' : mediaWidth +'%'
						});
						item.filter(':odd').find( '.wpr-grid-item-below-content' ).css({
							'float' : 'right',
							'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
						});
						item.filter(':odd').find( '.wpr-grid-media-wrap' ).css( 'margin-left', mediaDistance +'px' );

						// Fix Elements Align
						if ( ! grid.hasClass( 'wpr-grid-list-ready' ) ) {
							item.each( function( index ) {
								var element = $(this).find( '[class*="elementor-repeater-item"]' );

								if ( index % 2 === 0 ) {
									element.each(function() {
										if ( ! $(this).hasClass( 'wpr-grid-item-align-center' ) ) {
											if ( 'none' === $(this).css( 'float' ) ) {
												$(this).css( 'text-align', 'left' );
											} else {
												$(this).css( 'float', 'left' );
											}

											var inner = $(this).find( '.inner-block' );
										}
									});
								} else {
									element.each(function( index ) {
										if ( ! $(this).hasClass( 'wpr-grid-item-align-center' ) ) {
											if ( 'none' === $(this).css( 'float' ) ) {
												$(this).css( 'text-align', 'right' );
											} else {
												$(this).css( 'float', 'right' );
											}

											var inner = $(this).find( '.inner-block' );

											if ( '0px' !== inner.css( 'margin-left' ) ) {
												inner.css( 'margin-right', inner.css( 'margin-left' ) );
												inner.css( 'margin-left', '0' );
											}

											// First Item
											if ( 0 === index ) {
												if ( '0px' !== inner.css( 'margin-right' ) ) {
													inner.css( 'margin-left', inner.css( 'margin-right' ) );
													inner.css( 'margin-right', '0' );
												}
											}
										}
									});
								}
							});

						}

						setTimeout(function() {
							if ( ! grid.hasClass( 'wpr-grid-list-ready' ) ) {
								grid.addClass( 'wpr-grid-list-ready' );
							}
						}, 500 );
					}

				}
			}

			// Set Layout
			defaultLayout = layout;
			if ( 'list' === layout ) {
				layout = 'fitRows';
			}

			// No Transition
			if ( 'default' !== settings.filters_animation ) {
				transDuration = 0;
			}

			// Run Isotope
			var iGrid = grid.isotopewpr({
				layoutMode: layout,
				masonry: {
					// columnWidth: contWidth / columns,
					gutter: gutterHr
				},
				fitRows: {
					// columnWidth: contWidth / columns,
					gutter: gutterHr
				},
				transitionDuration: transDuration,
				  percentPosition: true
			});
		},

		// Media Hover Link
		mediaHoverLink: function ($scope, iGrid) {
			var img;
			var thisImgSrc;
			let secondaryImg;
			
			iGrid.find('.wpr-grid-media-wrap').on('mouseover', function() {
				if ( 'yes' === $(this).find('.wpr-grid-image-wrap').attr('data-img-on-hover') ) {
					// img = $(this).find( 'img' );
					// thisImgSrc = img.attr('src');
					
					// secondaryImg = $(this).find('.wpr-grid-image-wrap').data('src-secondary');
					
					// if ( isValidHttpUrl(secondaryImg) ) {
					// 	img.attr( 'src', secondaryImg );
					// }
					
					if ( $(this).find('img:nth-of-type(2)').attr('src') !== undefined && $(this).find('img:nth-of-type(2)').attr('src') !== '' ) {
						// $(this).find('img:first-of-type').fadeOut(0).addClass('wpr-hidden-img');
						// $(this).find('img:nth-of-type(2)').fadeIn(500).removeClass('wpr-hidden-img');
						$(this).find('img:first-of-type').addClass('wpr-hidden-img');
						$(this).find('img:nth-of-type(2)').removeClass('wpr-hidden-img');
					}
				}
			});

			iGrid.find('.wpr-grid-media-wrap').on('mouseleave', function() {
				if ( 'yes' === $(this).find('.wpr-grid-image-wrap').attr('data-img-on-hover') ) {
					// if ( secondaryImg == img.attr('src') ) {
					// 	img.attr('src', thisImgSrc);
					// }

					if ( $(this).find('img:nth-of-type(2)').attr('src') !== undefined && $(this).find('img:nth-of-type(2)').attr('src') !== '' ) {
						// $(this).find('img:nth-of-type(2)').fadeOut(0).addClass('wpr-hidden-img');
						// $(this).find('img:first-of-type').fadeIn(500).removeClass('wpr-hidden-img');
						$(this).find('img:nth-of-type(2)').addClass('wpr-hidden-img');
						$(this).find('img:first-of-type').removeClass('wpr-hidden-img');
					}
				}
			});

			if ( 'yes' === iGrid.find( '.wpr-grid-media-wrap' ).attr( 'data-overlay-link' ) && ! WprElements.editorCheck() ) {
				iGrid.find( '.wpr-grid-media-wrap' ).css('cursor', 'pointer');

				iGrid.find( '.wpr-grid-media-wrap' ).on( 'click', function( event ) {

					var targetClass = event.target.className;

					if ( -1 !== targetClass.indexOf( 'inner-block' ) || -1 !== targetClass.indexOf( 'wpr-cv-inner' ) || 
							-1 !== targetClass.indexOf( 'wpr-grid-media-hover' ) ) {
							event.preventDefault();
							event.stopPropagation();

						var itemUrl = $(this).find( '.wpr-grid-media-hover-bg' ).attr( 'data-url' ),
							itemUrl = itemUrl.replace('#new_tab', '');

						if (itemUrl) {
							try {
								// Create a URL object to validate the URL
								var url = new URL(itemUrl);

								// Define a list of allowed protocols
								var allowedProtocols = ['http:', 'https:'];

								// Check if the URL's protocol is allowed
								if (allowedProtocols.includes(url.protocol)) {
									// Safe to use the URL
									var safeUrl = url.href;

									if ('_blank' === iGrid.find('.wpr-grid-item-title a').attr('target')) {
										window.open(safeUrl, '_blank').focus();
									} else {
										window.location.href = safeUrl;
									}
								} else {
									console.error('Invalid URL scheme:', url.protocol);
								}
							} catch (e) {
								console.error('Invalid URL:', itemUrl);
							}
						}
					}
				});
			}				
		},

		// Lightbox Popup
		lightboxPopup: function ( settings, $scope, iGrid ) {
			if ( -1 === iGrid.find( '.wpr-grid-item-lightbox' ).length ) {
				return;
			}

			var lightbox = iGrid.find( '.wpr-grid-item-lightbox' ),
				lightboxOverlay = lightbox.find( '.wpr-grid-lightbox-overlay' ).first();

			// Set Src Attributes
			lightbox.each(function() {
				var source = $(this).find('.inner-block > span').attr( 'data-src' ),
					gridItem = $(this).closest( 'article' ).not('.slick-cloned');

				if ( ! iGrid.hasClass( 'wpr-media-grid' ) ) {
					gridItem.find( '.wpr-grid-image-wrap' ).attr( 'data-src', source );
				}

				var dataSource = gridItem.find( '.wpr-grid-image-wrap' ).attr( 'data-src' );

				if ( typeof dataSource !== typeof undefined && dataSource !== false ) {
					if ( -1 === dataSource.indexOf( 'wp-content' ) ) {
						gridItem.find( '.wpr-grid-image-wrap' ).attr( 'data-iframe', 'true' );
					}
				}
			});

			// Init Lightbox
			iGrid.lightGallery( settings.lightbox );

			// Fix LightGallery Thumbnails
			iGrid.on('onAfterOpen.lg',function() {
				if ( $('.lg-outer').find('.lg-thumb-item').length ) {
					$('.lg-outer').find('.lg-thumb-item').each(function() {
						var imgSrc = $(this).find('img').attr('src'),
							newImgSrc = imgSrc,
							extIndex = imgSrc.lastIndexOf('.'),
							imgExt = imgSrc.slice(extIndex),
							cropIndex = imgSrc.lastIndexOf('-'),
							cropSize = /\d{3,}x\d{3,}/.test(imgSrc.substring(extIndex,cropIndex)) ? imgSrc.substring(extIndex,cropIndex) : false;
						
						if ( 42 <= imgSrc.substring(extIndex,cropIndex).length ) {
							cropSize = '';
						}

						if ( cropSize !== '' ) {
							if ( false !== cropSize ) {
								newImgSrc = imgSrc.replace(cropSize, '-150x150');
							} else {
								newImgSrc = [imgSrc.slice(0, extIndex), '-150x150', imgSrc.slice(extIndex)].join('');
							}
						}

						// Change SRC
						$(this).find('img').attr('src', newImgSrc);
					});
				}
			});

			// Show/Hide Controls
			iGrid.find( '.wpr-grid' ).on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
				var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
					lightboxDownload = $( '#lg-download' ).attr( 'href' );

				if ( $( '#lg-download' ).length ) {
					if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
						lightboxControls.addClass( 'wpr-hidden-element' );
					} else {
						lightboxControls.removeClass( 'wpr-hidden-element' );
					}
				}

				// Autoplay Button
				if ( '' === settings.lightbox.autoplay ) {
					$( '.lg-autoplay-button' ).css({
							'width' : '0',
							'height' : '0',
							'overflow' : 'hidden'
					});
				}
			});

			// Overlay
			if ( lightboxOverlay.length ) {
				iGrid.find( '.wpr-grid-media-hover-bg' ).after( lightboxOverlay.remove() );

				iGrid.find( '.wpr-grid-lightbox-overlay' ).on( 'click', function() {
					if ( ! WprElements.editorCheck() ) {
						$(this).closest( 'article' ).find( '.wpr-grid-image-wrap' ).trigger( 'click' );
					} else {
						alert( 'Lightbox is Disabled in the Editor! Please Preview this Page to see it in action.' );
					}
				});
			} else {
				lightbox.find( '.inner-block > span' ).on( 'click', function() {
					if ( ! WprElements.editorCheck() ) {
						var imageWrap = $(this).closest( 'article' ).find( '.wpr-grid-image-wrap' );
							imageWrap.trigger( 'click' );
					} else {
						alert( 'Lightbox is Disabled in the Editor! Please Preview this Page to see it in action.' );
					}
				});
			}
		},

		// Add this helper function to sanitize HTML content
		sanitizeHTMLContent: function(html) {
			// Create a temporary DOM element
			var tempDiv = document.createElement('div');
			tempDiv.innerHTML = html;
			
			// Remove all script tags
			var scripts = tempDiv.getElementsByTagName('script');
			while(scripts.length > 0) {
				scripts[0].parentNode.removeChild(scripts[0]);
			}

			// Remove all iframe tags
			var iframes = tempDiv.getElementsByTagName('iframe');
			while(iframes.length > 0) {
				iframes[0].parentNode.removeChild(iframes[0]);
			}
			
			// Find all elements to remove potential malicious attributes
			var allElements = tempDiv.getElementsByTagName('*');
			for (var i = 0; i < allElements.length; i++) {
				// Remove event handler attributes
				var attrs = allElements[i].attributes;
				for (var j = attrs.length - 1; j >= 0; j--) {
					var attrName = attrs[j].name;
					// Remove all on* event handlers
					if (attrName.substring(0, 2) === 'on') {
						allElements[i].removeAttribute(attrName);
					}
					// Remove javascript: URLs
					if (attrName === 'href' || attrName === 'src') {
						var value = attrs[j].value;
						if (value.toLowerCase().indexOf('javascript:') === 0) {
							allElements[i].removeAttribute(attrName);
						}
					}
				}
			}
			
			return tempDiv.innerHTML;
		},

		sanitizeURL: function(dirtyURL) {
			if (!dirtyURL || typeof dirtyURL !== 'string') return null;
			
			// Sanitize the raw string (strip tags, attrs)
			const cleaned = DOMPurify.sanitize(dirtyURL, { ALLOWED_TAGS: [], ALLOWED_ATTR: [] }).trim();
			const lower = cleaned.toLowerCase();
			
			// Block dangerous schemes
			if (/^(javascript|data|vbscript):/.test(lower)) return null;
			
			// Parse URL (supports protocol-relative with base)
			let url;
			try {
				url = new URL(cleaned, window.location.origin);
			} catch {
				return null;
			}
			
			// Only http/https
			if (!/^https?:$/.test(url.protocol)) return null;
			
			// Whitelist domains (allow subdomains)
			const allowedDomains = ['youtube.com', 'youtu.be', 'vimeo.com', 'yourdomain.com'];
			const host = url.hostname.toLowerCase();
			const hostAllowed = allowedDomains.some(d => host === d || host.endsWith('.' + d));
			
			// Also allow direct video files anywhere
			const isDirectVideo = /\.(mp4|webm|ogg)(?:$|\?)/i.test(url.pathname);
			
			if (!hostAllowed && !isDirectVideo) return null;
			
			return url.toString();
		},
		  
		
		sanitizeDataAttr: function(value) {
			const raw = String(value || '').trim();
		
			// If value contains anything suspicious, block it
			const blacklisted = [
				'onerror', 'onclick', 'onload', 'script', '<', '>', '=', 'javascript:', 'data:', 'vbscript:', '&', '%3C', '%3E'
			];
		
			for (const keyword of blacklisted) {
				if (raw.toLowerCase().includes(keyword)) {
					return null;
				}
			}
		
			// Fallback DOMPurify to clean even benign input
			return DOMPurify.sanitize(raw, {
				SAFE_FOR_TEMPLATES: true,
				ALLOWED_TAGS: [],
				ALLOWED_ATTR: [],
			});
		},

		closeSideBarOnReplace: function($scope = null, animationSpeed = 200) {
			// $scope.find('.widget_shopping_cart_content').addClass('wpr-mini-cart-slide-out');
			$scope.find('.wpr-shopping-cart-inner-wrap').addClass('wpr-mini-cart-slide-out');
			$scope.find('.wpr-mini-cart-slide-out').css('animation-speed', animationSpeed);
			$scope.find('.wpr-shopping-cart-wrap').fadeOut(animationSpeed);
			$('body').removeClass('wpr-mini-cart-sidebar-body');
			setTimeout(function() {
				// $scope.find('.widget_shopping_cart_content').removeClass('wpr-mini-cart-slide-out');
				$scope.find('.wpr-shopping-cart-inner-wrap').removeClass('wpr-mini-cart-slide-out');
				$scope.find('.wpr-mini-cart').css({"display": "none"});
			}, animationSpeed + 100);
		}

	} // End WprElements

	// Expose WprElements globally for per-widget JS files
	window.WprElements = WprElements;
	
	$( window ).on( 'elementor/frontend/init', WprElements.init );

}( jQuery, window.elementorFrontend ) );

// Resize Function - Debounce
(function($,sr){

  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');