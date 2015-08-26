<div id = "spin_info">
  <form id = "spin-info-form">
    <label class = "text-input" for = "spin_version">
      Version<br />
      <input class = "text-input" type = "text" id = "spin_version" name = "spin_version" maxlength = "128" size = "303" value = "<?php/*proprietary*/?>" onkeyup = "createSystemValue( 'spin_version' )"/>
    </label>
    <label class = "text-input" for = "spin_version-system">
      System Version<br />
      <input class = "text-input" type = "text" id = "spin_version-system" name = "spin_version-system" maxlength = "128" size = "303" value = "<?php/*proprietary*/?>" disabled = "disabled"/>
    </label>
    <label class = "select-label" for = "spin-status">
      Status<br />
      <?php/*proprietary*/?>
    </label>
    <div class = "button-container" style = "margin-top:50px;">
      <a id = "update-spin" class = "dialog-button" href = "javascript:void(0)" onclick = "updateSpin( '<?php/*proprietary*/?>' )">Update Spin</a>
      <a class = "dialog-button" href = "javascript:void(0)" onclick = "history.go(-2);">Cancel</a>
    </div>
  </form>
  <div class = "tile-container">
    <form id = "tile-form" class = "hidden-form" enctype = "multipart/form-data">
      <input id="tile-upload" type="file" name="file"/>
      <input type = "hidden" id = "tile-num" value = "" />
      <input id = "submit-tile" class = "hidden-button" type = "button" value = "Upload" />
    </form>
    <div class = "tile-row">
      <div class = "tile tile-fill">
      </div>
      <div id = "tile-4" class = "tile tile-picture">
        <?php/*proprietary*/?>
      </div>
      <div class = "tile tile-fill">
      </div>
      <div class = "tile tile-fill">
      </div>
    </div>
    <div class = "tile-row">
      <div id = "tile-3" class = "tile tile-picture">
        <?php/*proprietary*/?>
      </div>
      <div id = "tile-0" class = "tile tile-picture">
        <?php/*proprietary*/?>
      </div>
      <div id = "tile-1" class = "tile tile-picture">
        <?php/*proprietary*/?>
      </div>
      <div id = "tile-2" class = "tile tile-picture">
        <?php/*proprietary*/?>
      </div>
    </div>
    <div class = "tile-row">
      <div class = "tile tile-fill">
      </div>
      <div id = "tile-5" class = "tile tile-picture">
        <?php/*proprietary*/?>
      </div>
      <div class = "tile tile-fill">
      </div>
      <div class = "tile tile-fill">
      </div>
    </div>
  </div>
</div>
<div id = "spin-container"  ondblclick = "handleClick('spin-container', event);">
  
</div>

<script type="text/javascript">
  this_spin = <?php/*proprietary*/?>;
  pano_init = false;
  
  window.URL = window.URL || window.webkitURL;
  $( '#tile-upload' ).change( function( ) {
    var file = this.files[ 0 ];
    var img = new Image();
    img.src = window.URL.createObjectURL( file );
    img.onload = function() {
      var width = img.naturalWidth;
      var height = img.naturalHeight;
      // console.log( width + ' x ' + height );
      window.URL.revokeObjectURL( img.src );
      if ( ( width == height  ) || ( width >= 1440 ) ) {
        var type = file.type;
        if ( type == 'image/jpeg' ) {
          $( '#submit-tile' ).click();
        }
        else {
          alert( 'invalid file format' );
        }
      }
      else {
        alert( 'invalid file dimensions' );
      }
    }
  } );

  $( '#submit-tile' ).click( function( ) {
    var tile = $( '#tile-num' ).val();
    var formData = new FormData( $( '#tile-form' )[ 0 ] );
    formData.append( 'method', 'upload_tile' );
    formData.append( 'spin_id', this_spin );
    formData.append( 'tile', tile );
    $.ajax( {
      url: '/xhr',
      type: 'POST',
      data: formData,
      dataType: 'text',
      cache: false,
      contentType: false,
      processData: false,
      complete: function( data ) {
        $( '#tile-' + tile ).empty();
        $( '#tile-' + tile ).append( '<img src = "/' + '<?php/*proprietary*/?>' + '_preview_' + tile + '.jpg"  onclick = "openFileDialog( \'' + tile + '\' )"></img>' );
        // reloads spin if there are 6 tiles.
        // spin xml seems to be cached so it doesn't reflect changed tiles
        // if ( <?php echo $num_tiles; ?> == 6 ) {
          // if ( pano_init ) {
            // $( "#spin-container" ).empty();
          // }
          // initPano();
        // }
      }
    } );
  } );

  function openFileDialog( tile_in ) {
    $( '#tile-num' ).val( tile_in );
    $( '#tile-upload' ).click();
  }

  function initPano() {
    if ( <?php echo $num_tiles; ?> == 6 ) {
      pano = new pano2vrPlayer("spin-container");
      skin = new pano2vrSkin(pano);
      loadSpin( this_spin, true );
      pano_init = true;
    }
  }
  
  function loadSpin(id, firstrun){
    window.location.hash = id;
    pano.readConfigUrl("/xml/admin_"+id+".xml"); // load the configuration
    $( '.ggskin_svg' ).each( function( i, obj ) {
      $( obj ).on( 'click', function( ) {
        hotspot_element = obj;
      } );
    } );
    $( '#spin-container' ).append( '<div id = "overlay-button-container"><a class = "pano-overlay-button" href = "javascript:void(0)" onclick = "setPanTilt()">Set Starting Pan & Tilt</a><a class = "pano-overlay-button" href = "javascript:void(0)" onclick = "setNorth()">Set North</a></div>' );
  }

  function checkKey( event ) {
    if ( event.keyCode == 13 ) {
      event.preventDefault();
      searchSpins();
    }
  }
  
  function handleClick( id, event ) {
    var pan = pano.getPan();
    var tilt = pano.getTilt();
    var fov = pano.getFov();
    var stageWidth = document.getElementById( id ).offsetWidth;
    var stageHeight = document.getElementById( id ).offsetHeight;
    var mouseX = event.offsetX?(event.offsetX):event.pageX-document.getElementById(id).offsetLeft - 140;
    var mouseY = event.offsetY?(event.offsetY):event.pageY-document.getElementById(id).offsetTop;
    console.log( mouseX + ', '+mouseY );
    console.log( stageWidth/2 + ', '+stageHeight/2 );
    getClickLocation( pan, tilt, fov, stageWidth, stageHeight, mouseX, mouseY );
    pano.setPan( postData[ 'pan' ] );
    pano.setTilt( postData[ 'tilt' ] );
    var position = $( "#spin-container" ).position();
    var hs_top = position.top + ( stageHeight / 2 ) - 16;
    var hs_left = position.left + ( stageWidth / 2 ) - 16;
    $( "#hotspot-img" ).css( { top: hs_top, left: hs_left, visibility: "visible" } );
    this.openDialog();
  }
  
  function getClickLocation( pan, tilt, fov, stageWidth, stageHeight, mouseX, mouseY ) {
    if ( mouseX < stageWidth/2 ) {
      postData[ 'pan' ] = pan + ( [ 0.5 - ( mouseX/stageWidth ) ] * fov );
    }
    else if ( mouseX > stageWidth/2 ) {
      postData[ 'pan' ] = pan - ( [ ( mouseX/stageWidth ) - 0.5 ] * fov );
    }
    if ( mouseY < stageHeight/2 ) {
      postData[ 'tilt' ] = tilt + ( [ 0.5 - ( mouseY/stageHeight ) ] * fov );
    }
    else if ( mouseY > stageHeight/2 ) {
      postData[ 'tilt' ] = tilt - ( [ ( mouseY/stageHeight ) - 0.5 ] * fov );
    }
    console.log( postData[ 'pan' ] + ', ' + postData[ 'tilt' ] );
  }

  
  function addHotspot( spin_id, event ){  
    postData[ 'method' ] = 'add_hotspot';
    postData[ 'dest_location_id' ] = spin_id;
    postData[ 'src_spin_id' ] = this_spin;
    postXHR();
    clearAddDialog();
  }
  
  function editHotspot( hotspot_id ) {
    var edit_dialog = $( "#edit-hotspot-dialog" ).dialog( {
      autoOpen: false,
      height: 60,
      width: 10,
      modal: true,
      position: { my: "top", at: "bottom", of: $( hotspot_element ) },
    } );
    $( "#delete-hotspot-button" ).off();
    $( "#delete-hotspot-button" ).on( 'click', function(){
    $( "#edit-hotspot-dialog" ).dialog( 'close' );
      deleteHotspot( hotspot_id );
    } );
    edit_dialog.dialog( 'open' );
  }
  
  function clearAddDialog() {
    $( "#add-hotspot-dialog" ).dialog( "close" );
    $( "#hotspot-img" ).css( { visibility: "hidden" } );
    $( "#results-container" ).css( { visibility: "hidden" } );
    $( "#results-container" ).empty();
    $( "#jump-location-search" ).val( "" );
  }
  
  function openDialog() {
    var hotspot_dialog = $( "#add-hotspot-dialog" ).dialog( {
      autoOpen: false,
      height: 500,
      width: 420,
      modal: true,
      position: { my: "left", at: "right", of: $( "#spin-container" ) }
    } );
    hotspot_dialog.dialog( "open" );
  }
  
  function deleteHotspot( hotspot_id ) {
    postData[ 'method' ] = 'delete_hotspot';
    postData[ 'hotspot_id' ] = hotspot_id;
    postXHR();
  }
  
  function cancelDelete() {
    $( "#edit-hotspot-dialog" ).dialog( 'close' );
  }
    
    function setPanTilt() {
      postData[ 'method' ] = 'set_pan_tilt';
      postData[ 'start_pan' ] = pano.getPan();
      postData[ 'start_tilt' ] = pano.getTilt();
      postData[ 'spin_id' ] = this_spin;
      postXHR( false );
    }
    
    function setNorth() {
      postData[ 'method' ] = 'set_north';
      postData[ 'pan_north' ] = pano.getPan();
      postData[ 'spin_id' ] = this_spin;
      postXHR( false );
    }
    
    // pass 'false' to not reload spin. pass nothing to reload spin
    function postXHR( reload_spin ) {
      $.post( '/xhr', postData,function( data ) {
      }, 'text' ).done( function( data ) {
        if ( typeof reload_spin === 'undefined' ) {
          reloadSpin( );
        }
        else {
          $( '#test' ).empty();
          $( '#test' ).append( data );
        }
        postData = {};
      } );
    }
    
    function reloadSpin( ) {
      var pan = pano.getPan();
      var tilt = pano.getTilt();
      var fov = pano.getFov();
      $( "#spin-container" ).empty();
      pano_init = false;
      initPano();
      pano.setPan( pan );
    }
    
    function searchSpins() {
      $( "#results-container" ).empty();
      var search = $( "#jump-location-search" ).val();
      if ( search != '' ) {
        searchData = {};
        searchData[ 'method' ] = 'search';
        searchData[ "search" ] = search;
        $.post( '/xhr', searchData,function( data ) {
        }, 'text' ).done( function( data ) {
          $( "#results-container" ).append( data );
        } );
        $( "#results-container" ).css( { visibility: "visible" } );
      }
    }
  
    
    initPano();
    postData = {};
 </script>
  <img id = "hotspot-img" src = "/i/pano-skin/hsimage.png"></img>
  <div id = "add-hotspot-dialog">
    <span style = "padding:2px 0 2px 0; text-align: center; display: block; position: relative; width: 100%;">Add New Hotspot</span>
    <input id = "jump-location-search" type = "text" onkeyup = "checkKey( event )"></input>
    <div class = "button-container">
      <a id = "spin-search" class = "dialog-button" href = "javascript:void(0)" onclick = "searchSpins()">Search Spins</a>
      <a class = "dialog-button" href = "javascript:void(0)" onclick = "clearAddDialog()">Cancel</a>
    </div>
    <div id = "results-container"></div>
  </div>
  <div id = "edit-hotspot-dialog">
    <div style = "width:108px; height: 56px; margin-left:auto;margin-right:auto;display:block;position:relative;">
    <a id = "delete-hotspot-button" class = "dialog-button" href = "javascript:void(0)">Delete</a>
    <a class = "dialog-button" href = "javascript:void(0)" onclick = "cancelDelete()">Cancel</a>
    </div>
  </div>
  <div id = "test" style = "position:absolute; top: 1000px; left: 0px; background-color: white; display: block; overflow: scroll; width: 500px; height: 500px;"></div>
