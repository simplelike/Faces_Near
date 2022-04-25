// jQuery extend functions for popup
(function($) {
    $.fn.openPopup = function( settings ) {
      var elem = $(this);
      // Establish our default settings
      var settings = $.extend({
        anim: 'fade'
      }, settings);
      elem.show();
      elem.find('.popup-content').addClass(settings.anim+'In');
    }
    
    $.fn.closePopup = function( settings ) {
      var elem = $(this);
      // Establish our default settings
      var settings = $.extend({
        anim: 'fade'
      }, settings);
      // elem.find('.popup-content').removeClass(settings.anim+'In').addClass(settings.anim+'Out');
      elem.hide();
          //elem.find('.popup-content').removeClass(settings.anim+'Out')
      
      // setTimeout(function(){
      //     elem.hide();
      //     elem.find('.popup-content').removeClass(settings.anim+'Out')
      //   }, 500);
    }
      
  }(jQuery));
  
  // Click functions for popup
  $('.open-popup').click(function(){
    $('#'+$(this).data('id')).openPopup({
      anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'fade' : $(this).data('animation')
    });
  });
  $('.close-popup').click(function(){
    $('#popup_default').closePopup({
      anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'fade' : $(this).data('animation')
    });
  });
  
  // To open/close the popup at any functions call the below
  // $('#popup_default').openPopup();
  // $('#popup_default').closePopup();
  
  function openPopup(cfg = () => {}) {
    if (cfg != "") {
      cfg()
    }
    $('#popup_default').openPopup({
      anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'fade' : $(this).data('animation')
    })
  }

  function closePopup() {
    $('#popup_default').closePopup();
  }