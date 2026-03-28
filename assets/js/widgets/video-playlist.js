(function($) {
	"use strict";

	var handler = function($scope) {
			const wprVideoPlaylist = {
				players: {},
				isAPIReady: false,
				pendingPlayers: [],
				apiLoaded: false,
		
				init: function($widget) {
					if (!$widget || !$widget.length) return;
					// Load YouTube IFrame API only once
					if (!this.apiLoaded && typeof YT === 'undefined') {
						const tag = document.createElement('script');
						tag.src = "//www.youtube.com/iframe_api";
						const firstScriptTag = document.getElementsByTagName('script')[0];
						firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
						this.apiLoaded = true;
		
						window.onYouTubeIframeAPIReady = () => {
							wprVideoPlaylist.isAPIReady = true;
							wprVideoPlaylist.pendingPlayers.forEach(playerData => {
								wprVideoPlaylist.createPlayer(playerData.$container, playerData.videoId, playerData.$widget);
							});
						};
					}
		
					const urls = $widget.find('.wpr-vplaylist-thumbs').data('urls');
					if (urls && Array.isArray(urls)) {
						this.processVideos($widget, urls);
					}
				},
		
				processVideos: function($widget, urls) {
					const $playlistUl = $widget.find('.wpr-vplaylist-thumbs ul');
					const title_tag = $widget.find('.wpr-vplaylist-current-title').prop('tagName').toLowerCase();
		
					const videoPromises = urls.map((url, index) => {
						return new Promise(resolve => {
							const videoId = wprVideoPlaylist.extractVideoId(url);
							if (!videoId) {
								resolve(null);
								return;
							}
		
							$.get('https://www.youtube.com/oembed', {
								url: url,
								format: 'json'
							})
							.done(response => {
								resolve({
									videoId: videoId,
									title: response.title,
									index: index
								});
							})
							.fail(() => {
								resolve(null);
							});
						});
					});
		
					Promise.all(videoPromises).then(results => {
						const validResults = results.filter(r => r !== null).sort((a, b) => a.index - b.index);
		
						validResults.forEach((video, index) => {
							const $li = $('<li>').attr('data-video', video.videoId);
							const thumbnailUrl = 'https://i.ytimg.com/vi/' + video.videoId + '/maxresdefault.jpg';
		
							$li.append($('<img>').attr({
								'src': thumbnailUrl,
								'alt': video.title
							}));
		
							const $info = $('<div>').addClass('wpr-vplaylist-info');
							$info.append($('<' + title_tag + '>').addClass('wpr-vplaylist-info-title').text(video.title));
							$li.append($info);
							$playlistUl.append($li);
		
							if (index === 0) {
								$widget.find('.wpr-vplaylist-current-title').text(video.title);
								$widget.find('.wpr-vplaylist-highlight').attr('data-video', video.videoId);
		
								const $player = $widget.find('.wpr-vplaylist-main');
								if (wprVideoPlaylist.isAPIReady) {
									wprVideoPlaylist.createPlayer($player, video.videoId, $widget);
								} else {
									wprVideoPlaylist.pendingPlayers.push({
										$container: $player,
										videoId: video.videoId,
										$widget: $widget
									});
								}
							}
						});
		
						$widget.find('.wpr-vplaylist-thumbs li').on('click', function() {
							const videoId = $(this).data('video');
							const playerId = $widget.find('.wpr-vplaylist-main').attr('id');
							const videoTitle = $(this).find('.wpr-vplaylist-info-title').text();
		
							if (wprVideoPlaylist.players[playerId]) {
								wprVideoPlaylist.players[playerId].loadVideoById(videoId);
								$widget.find('.wpr-vplaylist-highlight').attr('data-video', videoId);
								$widget.find('.wpr-vplaylist-current-title').text(videoTitle);
								$widget.find('.wpr-play').hide();
								$widget.find('.wpr-pause').show();
							}
						});
					});
				},
		
				createPlayer: function($container, videoId, $widget) {
					const playerId = 'wpr-player-' + Math.random().toString(36).substr(2, 9);
					$container.attr('id', playerId);
		
					this.players[playerId] = new YT.Player(playerId, {
						height: '360',
						width: '640',
						videoId: videoId,
						playerVars: {
							'autoplay': 0,
							'controls': 1,
							'rel': 0,
							'showinfo': 0
						},
						events: {
							'onReady': function() {
								$widget.find('.wpr-vplaylist-controller').on('click', function() {
									const player = wprVideoPlaylist.players[playerId];
		
									if (player.getPlayerState() === YT.PlayerState.PLAYING) {
										player.pauseVideo();
										$(this).find('.wpr-play').show();
										$(this).find('.wpr-pause').hide();
									} else {
										player.playVideo();
										$(this).find('.wpr-play').hide();
										$(this).find('.wpr-pause').show();
									}
								});
							},
							'onStateChange': function(event) {
								const $controller = $widget.find('.wpr-vplaylist-controller');
		
								if (event.data === YT.PlayerState.PLAYING) {
									$controller.find('.wpr-play').hide();
									$controller.find('.wpr-pause').show();
								} else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
									$controller.find('.wpr-play').show();
									$controller.find('.wpr-pause').hide();
								}
							}
						}
					});
		
					return playerId;
				},
		
				extractVideoId: function(url) {
					const pattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i;
					const match = url.match(pattern);
					return match ? match[1] : null;
				}
			};
		
			wprVideoPlaylist.init($scope);		
		}

	jQuery(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-video-playlist.default', handler );
	});

})(jQuery);
