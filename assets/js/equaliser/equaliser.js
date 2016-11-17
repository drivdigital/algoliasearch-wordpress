(function($, w){
  // A helper function to measure heights
  // If you're using borders or outlines
  var map_height = function (index, element) {
    return $(this).outerHeight();
  };

  // Equalise heights
  var equalise = function( element, group_size ) {
    // Reset element height
    element.height('');

    // Group results
    if (group_size === undefined) { group_size = 0; }

    if (group_size == 1) {
      return;
    }
    var groups = [];
    if (group_size) {
      // Not a clone, but a different object of with the same selection
      var clone = $(element);
      while (clone.length > 0) {
        groups.push($(clone.splice(0, group_size)));
      }
    }
    else {
      groups = [element];
    }

    // Measure the heights and then apply the highest measure to all
    var heights, height;
    for (var i in groups) {
      heights = groups[i].map(map_height).get();
      height = Math.max.apply(null, heights);
      groups[i].height(height);
    }
  };

  // Add jQuery plugin
  $.fn.equalise = function(size) {
    equalise(this, size);
    return this;
  };

  // Alterclass function to change classes rather than add/remove
  $.fn.alterClass = function ( removals, additions ) {

    var self = this;

    if ( removals.indexOf( '*' ) === -1 ) {
      // Use native jQuery methods if there is no wildcard matching
      self.removeClass( removals );
      return !additions ? self : self.addClass( additions );
    }

    var patt = new RegExp( '\\s' +
        removals.
          replace( /\*/g, '[A-Za-z0-9-_]+' ).
          split( ' ' ).
          join( '\\s|\\s' ) +
        '\\s', 'g' );

    self.each( function ( i, it ) {
      var cn = ' ' + it.className + ' ';
      while ( patt.test( cn ) ) {
        cn = cn.replace( patt, ' ' );
      }
      it.className = $.trim( cn );
    });

    return !additions ? self : self.addClass( additions );
  };

  // Determine if an url is external:
  var is_external = function () {
    var url = this;
    return (url.hostname && url.hostname.replace(/^www\./, '') !== location.hostname.replace(/^www\./, ''));
  };
  // Expose:
  w.is_external = is_external;

} (jQuery, window) );
