/* When products vary in size, their images also
vary. which often causes issues with layout.
We're tackling this by equalising all images and
products when they load in.

The alternative is to use <table> layouts. Ouch. */
jQuery( function( $ ) {

  // Waiting until the end of your browser movement, you wierdo.
  var waitForFinalEvent = (function () {
    var timers = {};
    return function ( callback, ms, uniqueId ) {
      if ( ! uniqueId ) {
        uniqueId = "Don't call this twice without a uniqueId";
      }
      if ( timers[uniqueId] ) {
        clearTimeout (timers[uniqueId]);
      }
      timers[uniqueId] = setTimeout( callback, ms );
    };
  })();

  // Unique ID for window resizes
  function uniqId() {
    return Math.round(new Date().getTime() + (Math.random() * 100));
  }

  // On that trigger, we want to do something.
  $( document ).on( 'datasetRendered', function() {
    // DO things.
    console.log( 'dataset rendered' );
    resizeElements();
  });

  /* On resize... */
  $( window ).resize( function() {
    waitForFinalEvent( function(){
      // Get a resize event notification
      // console.log( 'resizing...' + uniqId() );
      resizeElements()
    }, 240, uniqId())
  });
});



// Equalise function
function resizeElements() {
  $ = jQuery;
  // Create a rows array
  var rows = [];
  // Cycle through all products and group them into rows
  $('.aa-dataset-3.aa-suggestion,.aa-dataset-0.aa-suggestion').each(function() {
    // Get the 'top'
    var top = $( this ).position().top;
    // Find items with the same top
    $( this ).prev().add( $( this ).next() )
      .filter( function(){ return $( this ).position().top == top } )
      .alterClass( 'product--row-*', 'product--row-' + Math.round($(this).position().top ) );
      // .addClass( 'product--row-' + Math.round($(this).position().top ) );
    // Add this group to an array
    rows.push( 'product--row-' + Math.round( $(this).position().top ) ) ;
  });

  // Filter out all repeating classes / tops
  var unique_rows = rows.filter( function( itm, i, a ) {
    return i == a.indexOf( itm );
  });

  // Check the classes being called
  // console.log( unique_rows );
  $.each( unique_rows, function( key, value ) {
    var $class = '.' + value;
    // Ensure classes are being targetted
    // console.log( $class );
    $( $class ).equalise();
  });

  // All lonely and last elemnents get equalised together
  $( '.aa-dataset-3.aa-suggestion:not([class*="product--row"]), .aa-dataset-0.aa-suggestion:not([class*="product--row"])' ).each( function() {
    $( this ).equalise();
  });

}
