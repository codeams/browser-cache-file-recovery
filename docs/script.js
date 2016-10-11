
$(function() {

  var $head = $('head');
  var $body = $('body');
  var $inputTextarea = $('.input-textarea');
  var $recoverButton = $('.recover-button');

  var UiLocker = new function() {

    var _this = this;

    _this.lock = function() {
      _this.unlock();
      $body.append("<div class='ui-locker'></div>");
    }

    _this.unlock = function() {
      $body.find('.ui-locker').remove();
    }

    var uiLockerStylesTag = '<style>.ui-locker{ width: 100%; height: 100%; position: fixed; z-index: 999; top: 0; left: 0; }</style>';
    $head.append( uiLockerStylesTag );

  }

  var OutputWindow = new function() {

    var _this = this;

    _this.$container = $('.output-container');
    _this.$window = $('.output-window');

    _this.show = function() {
      _this.$container.addClass('visible');
    };

    _this.hide = function() {
      _this.$container.removeClass('visible');
    };

    _this.showTextareaOutput = function( textarea ) {

      _this.hide();

      _this.$window.find( 'textarea' ).remove();
      _this.$window.append( textarea );

      _this.show();

    };

    _this.$container.mouseup( function( event ) {

      var isntWindowTheTarget = !$( _this.$window ).is( event.target );
      var isntADecendantTheTarget = $( _this.$window ).has( event.target ).length === 0;

      var isClickOutsideOfWindow = isntWindowTheTarget && isntADecendantTheTarget;

      if ( isClickOutsideOfWindow ) _this.hide();

    });

  };

  var callRecuperator = function( cacheString ) {

    var recoverButtonOriginalValue = $recoverButton.attr('value');

    $.ajax({

      'type': 'GET',
      'url': 'recuperator.php',
      'dataType': 'html',

      'data': {
        'cacheString' : cacheString
      },

      beforeSend: function() {
        UiLocker.lock();
        $recoverButton.attr( 'value', 'Loading...' );
      },

      success: function( textarea ) {
        OutputWindow.showTextareaOutput( textarea );
      },

      error: function( xhr, textStatus ) {
        var errorDescription = 'An error has ocurred ('+ textStatus +'): ' + xhr;
        alert( errorDescription );
      },

      complete: function() {
        $inputTextarea.val('');
        $recoverButton.attr( 'value', recoverButtonOriginalValue );
        UiLocker.unlock();
      }

    });

  }

  $recoverButton.on( 'click', function() {

    var cacheString = $inputTextarea.val();
    callRecuperator( cacheString );

  });

});
