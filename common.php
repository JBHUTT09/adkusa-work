
  function deleteSpin( spin_id ) {
    postData = {}
    postData[ 'method' ] = 'delete_spin';
    postData[ 'delete' ] = 'spin';
    postData[ 'spin_id' ] = spin_id;
    $.post( '/xhr', postData, function(  ) {
    }, 'text' ).done( function( data ) {
        $( '#spin-' + spin_id ).remove();
      } );
  }
  
  function openAddSpinDialog( location_id, location_title ) {
    var add_dialog = $( '#add-spin-dialog' ).dialog( {
      autoOpen: false,
      height: 180,
      width: 500,
      modal: true,
      position: { my: 'center', at: 'center', of: window }
    } );
    $( '#add-spin-title' ).text( 'Add New Spin at ' + location_title );
    $( '#location-id-input' ).val( location_id );
    $( '#spin-date' ).datepicker( {
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy/mm/dd'
    } );
    add_dialog.dialog( 'open' );
  }
  
  function openAddLocationDialog() {
    var add_location_dialog = $( '#add-location-dialog' ).dialog( {
      autoOpen: false,
      height: 200,
      width: 500,
      modal: true,
      position: { my: 'center', at: 'center', of: window }
    } );
    add_location_dialog.dialog( 'open' );
  }
  
  function closeAddSpinDialog() {
    $( '#add-spin-title' ).empty();
    $( '#spin-version' ).val( '' );
    $( '#spin-version' ).keyup();
    $( '#spin-date' ).val( '' );
    $( '#add-spin-dialog' ).dialog( 'close' );
  }
  
  function closeAddLocationDialog() {
    $( '#location-title' ).val( '' );
    $( '#location-title' ).keyup();
    $( '#location-latitude' ).val( '' );
    $( '#location-longitude' ).val( '' );
    $( '#add-location-dialog' ).dialog( 'close' );
  }
  
  function addNewSpin() {
    var location_id = $( '#location-id-input' ).val().trim();
    var postData = { 'method': 'add_spin',
                             'spin_version': $( '#spin-version' ).val(),
                             'system_version': $( '#spin-version-system' ).val(),
                             'location_id': location_id,
                             'datetime': $( '#spin-date' ).val() };
    $.post( '/xhr', postData, function( ) {
    }, 'text' ).done( function( data ) {
      if ( data != 'failed' ) {
        window.location.replace( 'http://dev.adirondacksusa.com/admin/' + location_id + '/spin/' + data );
      }
    } );
  }
  
  function addNewLocation() {
    var postData = { 'method': 'add_location',
                            'title': $( '#location-title' ).val(),
                            'sys_title': $( '#location-title-system' ).val(),
                            'latitude': $( '#location-latitude' ).val(),
                            'longitude': $( '#location-longitude' ).val() };
    $.post( '/xhr', postData, function( ) {
    }, 'text' ).done( function( data ) {
      if ( data != 'failed' ) {
        window.location.replace( 'http://dev.adirondacksusa.com/admin/' + data + '/location/' + data );
      }
    } );
  }
  
  function updateLocation( location_id ) {
    var postData = { 'method': 'update_location',
                            'title': $( '#location-title' ).val(),
                            'location_id': location_id,
                            'sys_title': $( '#location-title-system' ).val(),
                            'latitude': $( '#location_lat' ).val(),
                            'longitude': $( '#location_lon' ).val() };
    $.post( '/xhr', postData, function( data ) {
    }, 'text' ).done( function( data ) {
    } );
  }
  
  function deleteLocation( location_id ) {
    var postData = { 'method': 'delete_location', 'location_id': location_id };
    $.post( '/xhr', postData, function( data ) {
    }, 'text' ).done( function( data ) {
      if ( data == 'success' ) {
        $( '#location-' + location_id ).remove();
      }
    } );
  }
