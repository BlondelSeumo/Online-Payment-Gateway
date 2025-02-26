/*!
 * jQuery throttle / debounce
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */

(function(window,undefined){
  '$:nomunge'; // Used by YUI compressor.

  var $ = window.jQuery || window.Cowboy || ( window.Cowboy = {} ),

    jq_throttle;
  
  $.throttle = jq_throttle = function( delay, no_trailing, callback, debounce_mode ) {

    var timeout_id,

      last_exec = 0;
    
    if ( typeof no_trailing !== 'boolean' ) {
      debounce_mode = callback;
      callback = no_trailing;
      no_trailing = undefined;
    }
    
    function wrapper() {
      var that = this,
        elapsed = +new Date() - last_exec,
        args = arguments;

      function exec() {
        last_exec = +new Date();
        callback.apply( that, args );
      };
      
      function clear() {
        timeout_id = undefined;
      };
      
      if ( debounce_mode && !timeout_id ) {
        exec();
      }
      
      timeout_id && clearTimeout( timeout_id );
      
      if ( debounce_mode === undefined && elapsed > delay ) {
        exec();
        
      } else if ( no_trailing !== true ) {
        timeout_id = setTimeout( debounce_mode ? clear : exec, debounce_mode === undefined ? delay - elapsed : delay );
      }
    };

    if ( $.guid ) {
      wrapper.guid = callback.guid = callback.guid || $.guid++;
    }

    return wrapper;
  };
  
  $.debounce = function( delay, at_begin, callback ) {
    return callback === undefined
      ? jq_throttle( delay, at_begin, false )
      : jq_throttle( delay, callback, at_begin !== false );
  };
  
})(this);