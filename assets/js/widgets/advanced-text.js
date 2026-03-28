(function($) {
	"use strict";

	var handler = function($scope) {

			if ( $scope.hasClass('wpr-advanced-text-style-animated') ) {
				var animText = $scope.find( '.wpr-anim-text' ),
					animLetters = $scope.find( '.wpr-anim-text-letters' ),
					animDuration = animText.attr( 'data-anim-duration' ),
					animDurationData = animDuration.split( ',' ),
					animLoop = animText.attr( 'data-anim-loop' ),
					animTextLength = animText.find('b').length,
					animTextCount = 0;

				animText.find('b').first().addClass('wpr-anim-text-visible');
					
				// set animation timing
				var animDuration = parseInt( animDurationData[0], 10),
					animDelay = parseInt( animDurationData[1], 10),
					//type effect
					selectionDuration = 500,
					typeAnimationDelay = selectionDuration + 800;

				initHeadline();
			}

			function loadLongShadow() {

				var $clippedText = $scope.find( '.wpr-clipped-text' ),
					clippedOption = $clippedText.data('clipped-options'),
					currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

				if ( clippedOption ) {
					var longShadowSize = clippedOption.longShadowSize,
						longShadowSizeTablet = clippedOption.longShadowSizeTablet,
						longShadowSizeMobile = clippedOption.longShadowSizeMobile;

					if ('desktop' === currentDeviceMode ) {
					   longShadowSize = clippedOption.longShadowSize;
					}

					if ('tablet' === currentDeviceMode && longShadowSizeTablet ) {
					   longShadowSize = longShadowSizeTablet;
					}

					if ('mobile' === currentDeviceMode && longShadowSizeMobile ) {
					   longShadowSize = longShadowSizeMobile;
					}

					$clippedText.find('.wpr-clipped-text-long-shadow').attr('style','text-shadow:'+longShadow( clippedOption.longShadowColor, longShadowSize, clippedOption.longShadowDirection ));
				}
			}

			loadLongShadow();

			$(window).on('resize', function() {
				loadLongShadow();
			});

			function initHeadline() {
				//insert <i> element for each letter of a changing word
				singleLetters(animLetters.find('b'));
				//initialise headline animation
				animateHeadline(animText);
			}

			function singleLetters($words) {
				$words.each(function() {
					var word = $(this),
						letters = word.text().split(''),
						selected = word.hasClass('wpr-anim-text-visible');
					for (var i in letters) {
						var letter = letters[i].replace(/ /g, '&nbsp;');
					
						letters[i] = (selected) ? '<i class="wpr-anim-text-in">' + letter + '</i>': '<i>' + letter + '</i>';
					}
					var newLetters = letters.join('');
					word.html(newLetters).css('opacity', 1);
				});
			}

			function animateHeadline($headlines) {
				var duration = animDelay;
				$headlines.each(function(){
					var headline = $(this),
						spanWrapper = headline.find('.wpr-anim-text-inner');
					
					if (headline.hasClass('wpr-anim-text-type-clip')){
						var newWidth = spanWrapper.outerWidth();
							spanWrapper.css('width', newWidth);
					}

					//trigger animation
					setTimeout(function(){
						hideWord( headline.find('.wpr-anim-text-visible').eq(0) );
					}, duration);

					// Fix Bigger Words Flip
					if ( headline.hasClass( 'wpr-anim-text-type-rotate-1' ) ) {
						spanWrapper.find( 'b' ).each(function() {
							if ( $(this).outerWidth() > spanWrapper.outerWidth() ) {
								spanWrapper.css( 'width', $(this).outerWidth() );
							}
						});
					}
				});
			}

			function hideWord($word) {
				var nextWord = takeNext($word);
				
				if ( animLoop !== 'yes' ) {

					animTextCount++;
					if ( animTextCount === animTextLength ) {
						return;
					}

				}
			   
				if ( $word.parents('.wpr-anim-text').hasClass('wpr-anim-text-type-typing') ) {
					var parentSpan = $word.parent('.wpr-anim-text-inner');
					parentSpan.addClass('wpr-anim-text-selected').removeClass('waiting'); 
					setTimeout(function(){ 
						parentSpan.removeClass('wpr-anim-text-selected'); 
						$word.removeClass('wpr-anim-text-visible').addClass('wpr-anim-text-hidden').children('i').removeClass('wpr-anim-text-in').addClass('wpr-anim-text-out');
					}, selectionDuration);
					setTimeout(function(){ showWord(nextWord, animDuration) }, typeAnimationDelay);
				
				} else if ( $word.parents('.wpr-anim-text').hasClass('wpr-anim-text-letters') ) {

					var bool = ( $word.children( 'i' ).length >= nextWord.children( 'i' ).length ) ? true : false;
						hideLetter($word.find('i').eq(0), $word, bool, animDuration);
						showLetter(nextWord.find('i').eq(0), nextWord, bool, animDuration);

				}  else if ( $word.parents('.wpr-anim-text').hasClass('wpr-anim-text-type-clip') ) {
					$word.parents('.wpr-anim-text-inner').animate({ width : '2px' }, animDuration, function(){
						switchWord($word, nextWord);
						showWord(nextWord);
					});

				} else {
					switchWord($word, nextWord);
					setTimeout(function(){ hideWord(nextWord) }, animDelay);
				}

			}

			function showWord($word, $duration) {
				if ( $word.parents( '.wpr-anim-text' ).hasClass( 'wpr-anim-text-type-typing' ) ) {
					showLetter( $word.find( 'i' ).eq(0), $word, false, $duration );
					$word.addClass( 'wpr-anim-text-visible' ).removeClass( 'wpr-anim-text-hidden' );

				} else if ( $word.parents( '.wpr-anim-text' ).hasClass( 'wpr-anim-text-type-clip' ) ) {
					$word.parents( '.wpr-anim-text-inner' ).animate({ 'width' : $word.outerWidth() }, animDuration, function() { 
						setTimeout( function() {
							hideWord($word);
						}, animDelay ); 
					});
				}
			}

			function hideLetter($letter, $word, $bool, $duration) {
				$letter.removeClass('wpr-anim-text-in').addClass('wpr-anim-text-out');
				
				if ( !$letter.is(':last-child') ) {
					setTimeout(function(){ hideLetter($letter.next(), $word, $bool, $duration); }, $duration);  
				} else if ( $bool ) { 
					setTimeout(function(){ hideWord(takeNext($word)) }, animDelay);
				}

				if ( $letter.is(':last-child') ) {
					var nextWord = takeNext($word);
					switchWord($word, nextWord);
				} 
			}

			function showLetter($letter, $word, $bool, $duration) {
				$letter.addClass('wpr-anim-text-in').removeClass('wpr-anim-text-out');
				
				if(!$letter.is(':last-child')) { 
					setTimeout(function(){ showLetter($letter.next(), $word, $bool, $duration); }, $duration); 
				} else { 
					if($word.parents('.wpr-anim-text').hasClass('wpr-anim-text-type-typing')) { setTimeout(function(){ $word.parents('.wpr-anim-text-inner').addClass('waiting'); }, 200);}
					if(!$bool) { setTimeout(function(){ hideWord($word) }, animDelay) }
				}
			}

			function takeNext($word) {
				return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
			}

			function takePrev($word) {
				return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
			}

			function switchWord($oldWord, $newWord) {
				$oldWord.removeClass('wpr-anim-text-visible').addClass('wpr-anim-text-hidden');
				$newWord.removeClass('wpr-anim-text-hidden').addClass('wpr-anim-text-visible');
			}

			function longShadow( shadowColor, shadowSize, shadowDirection ) {
			 
				var textshadow = '';

				for ( var i = 0, len = shadowSize; i < len; i++ ) {
					switch ( shadowDirection ) {
						case 'top':
							textshadow += '0 -'+ i +'px 0 '+ shadowColor +',';
						break;

						case 'right':
							textshadow += i +'px 0 0 '+ shadowColor +',';
						break;

						case 'bottom':
							textshadow += '0 '+ i +'px 0 '+ shadowColor +',';
						break;

						case 'left':
							textshadow += '-'+ i +'px 0 0 '+ shadowColor +',';
						break;

						case 'top-left':
							textshadow += '-'+ i +'px -'+ i +'px 0 '+ shadowColor +',';
						break;

						case 'top-right':
							textshadow += i +'px -'+ i +'px 0 '+ shadowColor +',';
						break;

						case 'bottom-left':
							textshadow += '-'+ i +'px '+ i +'px 0 '+ shadowColor +',';
						break;

						case 'bottom-right':
							textshadow += i +'px '+ i +'px 0 '+ shadowColor +',';
						break;

						default:
							textshadow += i +'px '+ i +'px 0 '+ shadowColor +',';
						break;
					}
				}

				textshadow = textshadow.slice(0, -1);

				return textshadow;
			}

		};

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-advanced-text.default', handler );
	});

})(jQuery);
