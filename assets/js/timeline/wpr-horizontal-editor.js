jQuery(document).ready(function ($) {
    
    elementorFrontend.hooks.addAction( 'frontend/element_ready/wpr-posts-timeline.default', function($scope, $){
          
      var horizontal = $scope.find('.wpr-horizontal-bottom').length ? '.wpr-horizontal-bottom' : '.wpr-horizontal';
			var horizontalE = $scope.find('.wpr-horizontal-bottom').length ? $scope.find('.wpr-horizontal-bottom') : $scope.find('.wpr-horizontal');

      $(""+horizontal+".swiper-container").each(function(index){ 
                          
            var slidestoshow = $(this).data("slidestoshow");
            var autoplay = $(this).data("autoplay");
            var speed = +$(this).attr('data-swiper-speed');
            var delay = +$(this).attr('data-swiper-delay');
            
            var swiper = new Swiper( $(this), {
              spaceBetween: 10,
              autoplay:autoplay,
              delay: delay,
              speed: speed,
              slidesPerView: slidestoshow,
              direction: 'horizontal',
              pagination: {
                el: '.wpr-pagination',
                type: 'progressbar',
              },
              navigation: {
                nextEl: '.wpr-button-next',
                prevEl: '.wpr-button-prev',
              },
              // Responsive breakpoints
              breakpoints: {
                // when window width is >= 320px
                320: {
                  slidesPerView: 1,
                },
                // when window width is >= 480px
                480: {
                  slidesPerView: 2,
                },
                // when window width is >= 640px
                740: { // 640
                  slidesPerView: slidestoshow,
                
                }
              },
            
            });
                     

      });
      
    });
      
  });