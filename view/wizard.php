<?php
  global $post;
  $post_id = $post->ID;
  
  $conf = get_option( 'fvwpflowplayer' );
  $allow_uploads = false;

	if( isset($conf["allowuploads"]) ) {
	  $allow_uploads = $conf["allowuploads"];
	}
  ?>
<script type='text/javascript'>
jQuery(document).ready(function(){ 
  if( jQuery(".fv-wordpress-flowplayer-button").length > 0 && jQuery().colorbox ) {     
    jQuery(".fv-wordpress-flowplayer-button").colorbox( 
      { width:"600px", height:"520px", href: "#fv-wordpress-flowplayer-popup", inline: true, onComplete : fv_wp_flowplayer_edit, onClosed :fv_wp_flowplayer_on_close }
    );
  }
});




var fv_wp_flowplayer_content;
var fv_wp_flowplayer_re_edit = /\[[^\]]*?<span[^>]*?rel="FCKFVWPFlowplayerPlaceholder">.*?<\/span>[^\]]*?\]/mi;
var fv_wp_flowplayer_re_insert = /<span[^>]*?rel="FCKFVWPFlowplayerPlaceholder">.*?<\/span>/gi;
var fv_wp_flowplayer_hTinyMCE;
var fv_wp_flowplayer_oEditor;




function fv_wp_flowplayer_init() {
  if( typeof tinyMCE !== 'undefined' ) {
    fv_wp_flowplayer_hTinyMCE = tinyMCE.getInstanceById('content');
  }
  else {
    fv_wp_flowplayer_oEditor = FCKeditorAPI.GetInstance('content');    
  }
}


function fv_wp_flowplayer_insert( shortcode ) {
  if( fv_wp_flowplayer_content.match( fv_wp_flowplayer_re_edit ) ) {
    fv_wp_flowplayer_content = fv_wp_flowplayer_content.replace( fv_wp_flowplayer_re_edit, shortcode )
    fv_wp_flowplayer_set_html( fv_wp_flowplayer_content );
  }
  else {
    if ( fv_wp_flowplayer_content != '' ) {
      fv_wp_flowplayer_content = fv_wp_flowplayer_content.replace( fv_wp_flowplayer_re_insert, shortcode ) 
      fv_wp_flowplayer_set_html( fv_wp_flowplayer_content );            
    } else {
      send_to_editor( shortcode );
    }
  }
} 


function fv_wp_flowplayer_edit() {	
  
  fv_wp_flowplayer_init();
  
  jQuery("#fv-wordpress-flowplayer-popup input").each( function() { jQuery(this).val( '' ); jQuery(this).attr( 'checked', false ) } );
  jQuery("#fv-wordpress-flowplayer-popup textarea").each( function() { jQuery(this).val( '' ) } );
  jQuery('#fv_wp_flowplayer_field_autoplay').prop('selectedIndex',0);
  jQuery("#fv_wp_flowplayer_field_insert-button").attr( 'value', 'Insert' );
  
	if( fv_wp_flowplayer_hTinyMCE == undefined || tinyMCE.activeEditor.isHidden() ) {  
    fv_wp_flowplayer_content = fv_wp_flowplayer_oEditor.GetHTML();    
    if (fv_wp_flowplayer_content.match( fv_wp_flowplayer_re_insert ) == null) {
      fv_wp_flowplayer_oEditor.InsertHtml('<span rel="FCKFVWPFlowplayerPlaceholder">&shy;</span>');
      fv_wp_flowplayer_content = fv_wp_flowplayer_oEditor.GetHTML();    
    }           
	}
	else {
    fv_wp_flowplayer_content = fv_wp_flowplayer_hTinyMCE.getContent();
    fv_wp_flowplayer_hTinyMCE.settings.validate = false;
    if (fv_wp_flowplayer_content.match( fv_wp_flowplayer_re_insert ) == null) {      
      //fv_wp_flowplayer_hTinyMCE.selection.setContent('<span data-mce-bogus="1" rel="FCKFVWPFlowplayerPlaceholder"></span>');
      fv_wp_flowplayer_hTinyMCE.execCommand('mceInsertContent', false,'<span data-mce-bogus="1" rel="FCKFVWPFlowplayerPlaceholder"></span>');
      fv_wp_flowplayer_content = fv_wp_flowplayer_hTinyMCE.getContent();      
    }
    fv_wp_flowplayer_hTinyMCE.settings.validate = true;		
	}
	
  
  var content = fv_wp_flowplayer_content.replace(/\n/g, '\uffff');      
  var shortcode = content.match( fv_wp_flowplayer_re_edit );  
    
  if( shortcode != null ) {
    shortcode = shortcode.join('');
    shortcode = shortcode.replace('[', '');
    shortcode = shortcode.replace(']', '');
  	shortcode = shortcode.replace( fv_wp_flowplayer_re_insert, '' );
  	
  	shortcode = shortcode.replace( /\\'/g,'&#039;' );
  	
  	var srcurl = shortcode.match( /src=['"]([^']*?)['"]/ );
  	if( srcurl == null )
  		srcurl = shortcode.match( /src=([^,\]\s]*)/ );			
  	var iheight = shortcode.match( /height="?(\d*)"?/ );			
  	var iwidth = shortcode.match( /width="?(\d*)"?/ );
  	var sautoplay = shortcode.match( /autoplay=([^\s]+)/ );
  	var ssplash = shortcode.match( /splash='([^']*)'/ );
    var sredirect = shortcode.match( /redirect='([^']*)'/ );
  	if( ssplash == null )
  		ssplash = shortcode.match( /splash=([^,\]\s]*)/ );			
  	var spopup = shortcode.match( /popup='([^']*)'/ );
    var sloop = shortcode.match( /loop=([^\s]+)/ );
    var ssplashend = shortcode.match( /splashend=([^\s]+)/ );
    
  	if( srcurl != null && srcurl[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_src").value = srcurl[1];
  	if( iheight != null && iheight[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_height").value = iheight[1];
  	if( iwidth != null && iwidth[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_width").value = iwidth[1];
  	if( sautoplay != null && sautoplay[1] != null ) {
  		if (sautoplay[1] == 'true') 
        document.getElementById("fv_wp_flowplayer_field_autoplay").selectedIndex = 1;
      if (sautoplay[1] == 'false') 
        document.getElementById("fv_wp_flowplayer_field_autoplay").selectedIndex = 2;
    }
  	if( ssplash != null && ssplash[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_splash").value = ssplash[1];
  	if( spopup != null && spopup[1] != null ) {
  		spopup = spopup[1].replace(/&#039;/g,'\'').replace(/&quot;/g,'"').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
  		spopup = spopup.replace(/&amp;/g,'&');
  		document.getElementById("fv_wp_flowplayer_field_popup").value = spopup;
  	}
    if( sredirect != null && sredirect[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_redirect").value = sredirect[1];
    if( sloop != null && sloop[1] != null && sloop[1] == 'true' )
  		document.getElementById("fv_wp_flowplayer_field_loop").checked = 1;
    if( ssplashend != null && ssplashend[1] != null && ssplashend[1] == 'show' )
  		document.getElementById("fv_wp_flowplayer_field_splashend").checked = 1;  
  	
  	jQuery("#fv_wp_flowplayer_field_insert-button").attr( 'value', 'Update' );
	}
}


function fv_wp_flowplayer_on_close() {
  fv_wp_flowplayer_init();
  fv_wp_flowplayer_set_html( fv_wp_flowplayer_content.replace( fv_wp_flowplayer_re_insert, '' ) );
}   


function fv_wp_flowplayer_set_html( html ) {
  if( fv_wp_flowplayer_hTinyMCE == undefined || tinyMCE.activeEditor.isHidden() ) {
    fv_wp_flowplayer_oEditor.SetHTML( html );      
  }
  else {		
    fv_wp_flowplayer_hTinyMCE.setContent( html );
  }
}


function fv_wp_flowplayer_submit() {
	var shortcode = '';
  var shorttag = 'fvplayer';
	
	if(document.getElementById("fv_wp_flowplayer_field_src").value == '') {
		alert('Please enter the file name of your video file.');
		return false;
	}
	else
		shortcode = '[' + shorttag + ' src=\'' + document.getElementById("fv_wp_flowplayer_field_src").value + '\'';
    
  if ( document.getElementById("fv_wp_flowplayer_field_src_1").value != '' ) {
    shortcode += ' src1=\'' + document.getElementById("fv_wp_flowplayer_field_src_1").value + '\''; 
  }
  if ( document.getElementById("fv_wp_flowplayer_field_src_2").value != '' ) {
    shortcode += ' src2=\'' + document.getElementById("fv_wp_flowplayer_field_src_2").value + '\''; 
  }
		
	if( document.getElementById("fv_wp_flowplayer_field_width").value != '' && document.getElementById("fv_wp_flowplayer_field_width").value % 1 != 0 ) {
		alert('Please enter a valid width.');
		return false;
	}
	if( document.getElementById("fv_wp_flowplayer_field_width").value != '' )
		shortcode += ' width=' + document.getElementById("fv_wp_flowplayer_field_width").value;
		
	if( document.getElementById("fv_wp_flowplayer_field_height").value != '' && document.getElementById("fv_wp_flowplayer_field_height").value % 1 != 0 ) {
		alert('Please enter a valid height.');
		return false;
	}
	if( document.getElementById("fv_wp_flowplayer_field_height").value != '' )
		shortcode += ' height=' + document.getElementById("fv_wp_flowplayer_field_height").value;
	
  if( document.getElementById("fv_wp_flowplayer_field_autoplay").selectedIndex == 1 )
	  shortcode += ' autoplay=true';
	if( document.getElementById("fv_wp_flowplayer_field_autoplay").selectedIndex == 2 )
	  shortcode += ' autoplay=false';
    
  if( document.getElementById("fv_wp_flowplayer_field_loop").checked )
		shortcode += ' loop=true';    
		
	if( document.getElementById("fv_wp_flowplayer_field_splash").value != '' )
		shortcode += ' splash=\'' + document.getElementById("fv_wp_flowplayer_field_splash").value + '\'';
    
  if( document.getElementById("fv_wp_flowplayer_field_splashend").checked )
		shortcode += ' splashend=show';
    
  if( document.getElementById("fv_wp_flowplayer_field_redirect").value != '' )
		shortcode += ' redirect=\'' + document.getElementById("fv_wp_flowplayer_field_redirect").value + '\'';        
    
  if( document.getElementById("fv_wp_flowplayer_field_popup").value != '' ) {
		var popup = document.getElementById("fv_wp_flowplayer_field_popup").value;
		popup = popup.replace(/&/g,'&amp;');
		popup = popup.replace(/'/g,'\\\'');
		popup = popup.replace(/"/g,'&quot;');
		popup = popup.replace(/</g,'&lt;');
		popup = popup.replace(/>/g,'&gt;');
		shortcode += ' popup=\'' + popup +'\''
	}        
	
	shortcode += ']';
	/*document.cookie = "selected_video='';expires=Thu, 01-Jan-1970 00:00:01 GMT;";
  document.cookie = "selected_video1='';expires=Thu, 01-Jan-1970 00:00:01 GMT;";
  document.cookie = "selected_video2='';expires=Thu, 01-Jan-1970 00:00:01 GMT;";
	document.cookie = "selected_image='';expires=Thu, 01-Jan-1970 00:00:01 GMT;";*/
	
	fv_wp_flowplayer_insert( shortcode );
  
  jQuery(".fv-wordpress-flowplayer-button").colorbox.close();
  
}


</script>
<div style="display: none">
  <div id="fv-wordpress-flowplayer-popup">
    <form>
    	<table class="slidetoggle describe">
    		<tbody>
    			<tr>
    				<th scope="row" class="label" style="width: 10%"><label for="fv_wp_flowplayer_field_src" class="alignright">Video</label></th>
    				<td colspan="2" class="field"><input type="text" class="text" id="fv_wp_flowplayer_field_src" name="fv_wp_flowplayer_field_src" style="width: 100%" value="" /></td>
    			</tr>
    			<?php 
          if ($allow_uploads=="true") {
          ?> 
    			<tr>
      			<th></th>
      			<td colspan="2" style="width: 100%" >         
              Or <a class="thickbox" href="media-upload.php?post_id=<?php echo $post_id; ?>&amp;type=fvplayer&amp;TB_iframe=true&amp;post_mime_type=video&amp;width=640&amp;height=723">open media library</a> to upload new video.
      			</td>
    			</tr>
    			<?php }; //allow uplads video ?>
    
    			<?php if (!empty($uploaded_video)) { ?>
            <tr>
              <th></th>
              <th scope="row" class="label"><span class="alignleft">File info</span></th>
              <td>
                <?php if (!empty($file_width)) { ?>
                Video Duration: <?php echo $file_time ?><br />
                File size: <?php echo $file_size ?>MB
                <?php } else echo $file_error;  ?>
              </td>
            </tr>
          <?php }; //video has been selected ?> 
    			<tr><th></th>
    				<th scope="row" class="label" ><label for="fv_wp_flowplayer_field_width" class="alignleft">Width <small>(px)</small></label><br class='clear' /></th>
    				<td class="field"><input type="text" id="fv_wp_flowplayer_field_width" name="fv_wp_flowplayer_field_width" style="width: 100%"  value="<?php echo $file_width ?>"/></td>
    			</tr>
    			<tr><th></th>
    				<th scope="row" class="label" style="width: 12%"><label for="fv_wp_flowplayer_field_height" class="alignleft">Height <small>(px)</small></label></th>
    				<td class="field"><input type="text" id="fv_wp_flowplayer_field_height" name="fv_wp_flowplayer_field_height" style="width: 100%" value="<?php echo $file_height ?>"/></td>
    			</tr>
          
          <tr <?php if (!empty($uploaded_video) && !empty($uploaded_video1)) echo 'style="display: table-row;"'; else echo 'style="display: none;"'; ?> id="src_1_wrapper">
    				<th scope="row" class="label" style="width: 10%"><label for="fv_wp_flowplayer_field_src_1" class="alignright">Video</label></th>
    				<td colspan="2" class="field"><input type="text" class="text" id="fv_wp_flowplayer_field_src_1" name="fv_wp_flowplayer_field_src_1" style="width: 100%" value="<?php echo $uploaded_video1 ?>"/></td>
    			</tr>
          <?php 
          if ($allow_uploads=="true") {
          ?> 
    			<tr <?php if (!empty($uploaded_video) && !empty($uploaded_video1)) echo 'style="display: table-row;"'; else echo 'style="display: none;"'; ?> id="src_1_uploader">
      			<th></th>
      			<td colspan="2" style="width: 100%" >         
              Or <a class="thickbox" href="media-upload.php?post_id=<?php echo $post_id; ?>&amp;type=video&amp;TB_iframe=true&amp;width=640&amp;height=723fvplayer1">open media library</a> to upload new video.
      			</td>
    			</tr>
    			<?php }; //allow uplads video ?>
          
          <tr <?php if (!empty($uploaded_video1) && !empty($uploaded_video2)) echo 'style="display: table-row;"'; else echo 'style="display: none;"'; ?> id="src_2_wrapper">
    				<th scope="row" class="label" style="width: 10%"><label for="fv_wp_flowplayer_field_src_2" class="alignright">Video</label></th>
    				<td colspan="2" class="field"><input type="text" class="text" id="fv_wp_flowplayer_field_src_2" name="fv_wp_flowplayer_field_src_2" style="width: 100%" value="<?php echo $uploaded_video2 ?>"/></td>
    			</tr>
          <?php 
          if ($allow_uploads=="true") {
          ?> 
    			<tr <?php if (!empty($uploaded_video1) && !empty($uploaded_video2)) echo 'style="display: table-row;"'; else echo 'style="display: none;"'; ?> id="src_2_uploader">
      			<th></th>
      			<td colspan="2" style="width: 100%" >         
              Or <a href="media-upload.php?post_id=<?php echo $post_id; ?>&amp;type=video&amp;TB_iframe=true&amp;width=640&amp;height=723fvplayer2">open media library</a> to upload new video.
      			</td>
    			</tr>
    			<?php }; //allow uplads video ?>
          
          <?php if (empty($uploaded_video2)) { ?>
          <tr id="add_format_wrapper">
      			<th scope="row" class="label" style="width: 10%"></th>
    				<td colspan="2" class="field"><a href="#" onclick="add_format()" style="outline: 0"><span id="add-format" style="background: url(<?php echo plugins_url( 'images/admin-bar-sprite.png' , dirname(__FILE__) ) ?>) no-repeat -3px -205px; display: block; width: 11px; height: 11px; float: left; margin-top: 2px; padding-right: 4px;"></span>Add another format</a></td>
    			</tr>      
          <?php }; ?>
    			
          <tr>
    				<th scope="row" class="label"><label for="fv_wp_flowplayer_field_splash" class="alignright">Splash Image</label></th>
    				<td class="field" colspan="2"><input type="text" id="fv_wp_flowplayer_field_splash" name="fv_wp_flowplayer_field_splash" style="width: 100%"  value="<?php echo $uploaded_image ?>"/></td>
    			</tr>
    			<?php if ($allow_uploads=='true') { ?>
            <tr>
              <th></th>
              <td colspan="2" class="field" style="width: 100%" >
                Or <a href="media-upload.php?type=image&amp;post_id=<?php echo $post_id; ?>&amp;TB_iframe=true&amp;width=640&amp;height=723fvplayer">open media library</a> to upload new splash image.
              </td>
    			</tr>
    			<?php }; //allow uploads splash image ?>
    			<?php if (!empty($uploaded_image))
            if (($post_thumbnail=='true') && current_theme_supports( 'post-thumbnails') && isset($selected_attachment['id'])) 
              update_post_meta( $post_id, '_thumbnail_id', $selected_attachment['id'] ); ?>			
        </tbody>
      </table>
      <table>
        <tbody>      
          <tr>
            <th scope="row" colspan="2" style="text-align: left; padding: 10px 0;">Additional features</th>
          </tr>
          <tr>
    				<th valign="top" scope="row" class="label" style="width: 12%"><label for="fv_wp_flowplayer_field_popup" class="alignright">HTML Popup</label></th>
    				<td><textarea type="text" id="fv_wp_flowplayer_field_popup" name="fv_wp_flowplayer_field_popup" style="width: 100%"></textarea></td>
    			</tr>
          <tr>
    				<th scope="row" class="label"><label for="fv_wp_flowplayer_field_redirect" class="alignright">Redirect to</label></th>
    				<td class="field"><input type="text" id="fv_wp_flowplayer_field_redirect" name="fv_wp_flowplayer_field_redirect" style="width: 100%" /></td>
    			</tr>
          <tr>
    				<th scope="row" class="label"><label for="fv_wp_flowplayer_field_autoplay" class="alignright">Autoplay</label></th>
    				<td class="field">
              <select id="fv_wp_flowplayer_field_autoplay" name="fv_wp_flowplayer_field_autoplay">
                <option>Default</option>
                <option>On</option>
                <option>Off</option>
              </select>
            </td>
    			</tr>
          <tr>
    				<th scope="row" class="label"><label for="fv_wp_flowplayer_field_loop" class="alignright">Loop</label></th>
    				<td class="field"><input type="checkbox" id="fv_wp_flowplayer_field_loop" name="fv_wp_flowplayer_field_loop" /></td>
    			</tr>   
          <tr>
            <th scope="row" class="label">
              <label for="fv_wp_flowplayer_field_splashend">Splash end</label>
            </th>
            <td>
              <input type="checkbox" id="fv_wp_flowplayer_field_splashend" name="fv_wp_flowplayer_field_splashend" /> (show splash image at the end)
            </td> 
          </tr>         
    			<tr>
    				<th colspan="2" scope="row" class="label" style="padding-top: 20px;">					
              <input type="button" value="Insert" name="insert" id="fv_wp_flowplayer_field_insert-button" class="button-primary alignleft" onclick="fv_wp_flowplayer_submit();" />
    				</th>
    			</tr>
    		</tbody>
    	</table>
    </form>
  </div>
</div>