jQuery(document).ready(function(){ 
  if( jQuery(".fv-wordpress-flowplayer-button").length > 0 && jQuery().colorbox ) {     
    jQuery(".fv-wordpress-flowplayer-button").colorbox( 
      { width:"600px", height:"620px", href: "#fv-wordpress-flowplayer-popup", inline: true, onComplete : fv_wp_flowplayer_edit, onClosed : fv_wp_flowplayer_on_close }
    );
    
    jQuery(".fv-wordpress-flowplayer-button").click( function() {
      if( jQuery('#wp-content-wrap').hasClass('html-active') ) {
        jQuery(".fv-wordpress-flowplayer-button").after( ' <strong class="fv-wordpress-flowplayer-error">Please use the Visual editor</strong>' );
		    jQuery(".fv-wordpress-flowplayer-error").delay(2000).fadeOut( 500,function() { jQuery(this).remove(); } );
        return false;
      }
    } );
  }
});




var fv_wp_flowplayer_content;
var fv_wp_flowplayer_hTinyMCE;
var fv_wp_flowplayer_oEditor;
var fv_wp_fp_shortcode_remains;




function fv_wp_flowplayer_init() {
  if( typeof tinyMCE !== 'undefined' ) {
    fv_wp_flowplayer_hTinyMCE = tinyMCE.getInstanceById('content');
  }
  else {
    fv_wp_flowplayer_oEditor = FCKeditorAPI.GetInstance('content');    
  }
  jQuery('#fv_wp_flowplayer_file_info').hide();
  jQuery("#fv_wp_flowplayer_field_src_2_wrapper").hide();
  jQuery("#fv_wp_flowplayer_field_src_2_uploader").hide();
  jQuery("#fv_wp_flowplayer_field_src_1_wrapper").hide();
  jQuery("#fv_wp_flowplayer_field_src_1_uploader").hide();
  jQuery("#add_format_wrapper").show();
  jQuery("#add_rtmp_wrapper").show(); 
  jQuery("#fv_wp_flowplayer_field_rtmp_wrapper").hide();
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
      send_to_editor( shortcode );  //  disappears?
    }                                                
  }  
} 


function fv_wp_flowplayer_playlist_remove(link) {
	jQuery(link).parent().parent().remove();	
	return false;
}


function fv_wp_flowplayer_playlist_item( aItem ) {
	var html = '';
	var aPlaylistItem = aItem.split(',');
	if( aPlaylistItem[1] == 'preroll' && !aPlaylistItem[2] ) {
		aPlaylistItem[1] = '';
		aPlaylistItem[2] = 'preroll';
	}
	var sItemSelectHTML = '<option value="">Normall</option><option value="preroll" '+( (aPlaylistItem[2] == 'preroll') ? 'selected' : '' )+'>Preroll</option>'
	
	html += '<tr><th width="18%" title="Drag to order playlist items" class="fv_wp_flowplayer_playlist_head"><a class="fv_wp_flowplayer_playlist_remove" href="#" onclick="return fv_wp_flowplayer_playlist_remove(this)">remove</a> Video<br />Splash Image</th>';
	html += '<td><input type="text" name="playlist[][video]" value="'+( (aPlaylistItem[0]) ? aPlaylistItem[0] : '' )+'" style="width: 100%" /><br />';
	html += '<input type="text" name="playlist[][splash]" value="'+( (aPlaylistItem[1]) ? aPlaylistItem[1] : '' )+'" style="width: 100%" /></td>';				
	//sItemHTML += '<td><select name="playlist[][meta]">'+sItemSelectHTML+'</select></td>';
	html += '</tr>';
	return html;
}


function fv_flowplayer_playlist_add() {
	jQuery('#fv_wp_flowplayer_field_playlist table tbody').append( fv_wp_flowplayer_playlist_item(',') );
  jQuery('.fv_wp_flowplayer_playlist_head').hover(
  	function() { jQuery(this).find('.fv_wp_flowplayer_playlist_remove').show(); }, function() { jQuery(this).find('.fv_wp_flowplayer_playlist_remove').hide(); } ); 
  return false;
}


function fv_wp_flowplayer_edit() {	
  
  fv_wp_flowplayer_init();
  
  jQuery("#fv-wordpress-flowplayer-popup input").each( function() { jQuery(this).val( '' ); jQuery(this).attr( 'checked', false ) } );
  jQuery("#fv-wordpress-flowplayer-popup textarea").each( function() { jQuery(this).val( '' ) } );
  jQuery('#fv_wp_flowplayer_field_autoplay').prop('selectedIndex',0);
  jQuery('#fv_wp_flowplayer_field_embed').prop('selectedIndex',0);
  jQuery('#fv_wp_flowplayer_field_align').prop('selectedIndex',0);  
  jQuery("#fv_wp_flowplayer_field_insert-button").attr( 'value', 'Insert' );
  jQuery('#fv_wp_flowplayer_field_playlist').html('<table><tbody></tbody></table>');  
  
	if( fv_wp_flowplayer_hTinyMCE == undefined || tinyMCE.activeEditor.isHidden() ) {  
    fv_wp_flowplayer_content = fv_wp_flowplayer_oEditor.GetHTML();    
    if (fv_wp_flowplayer_content.match( fv_wp_flowplayer_re_insert ) == null) {
      fv_wp_flowplayer_oEditor.InsertHtml('<'+fvwpflowplayer_helper_tag+' rel="FCKFVWPFlowplayerPlaceholder">&shy;</'+fvwpflowplayer_helper_tag+'>');
      fv_wp_flowplayer_content = fv_wp_flowplayer_oEditor.GetHTML();    
    }           
	}
	else {
    fv_wp_flowplayer_content = fv_wp_flowplayer_hTinyMCE.getContent();
    fv_wp_flowplayer_hTinyMCE.settings.validate = false;
    if (fv_wp_flowplayer_content.match( fv_wp_flowplayer_re_insert ) == null) {      
      //fv_wp_flowplayer_hTinyMCE.selection.setContent('<span data-mce-bogus="1" rel="FCKFVWPFlowplayerPlaceholder"></span>');
      fv_wp_flowplayer_hTinyMCE.execCommand('mceInsertContent', false,'<'+fvwpflowplayer_helper_tag+' data-mce-bogus="1" rel="FCKFVWPFlowplayerPlaceholder"></'+fvwpflowplayer_helper_tag+'>');
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
  	  
	  var shortcode_parse_fix = shortcode.replace(/popup='[^']*?'/g, '');
	  shortcode_parse_fix = shortcode_parse_fix.replace(/ad='[^']*?'/g, '');
    fv_wp_fp_shortcode_remains = shortcode_parse_fix.replace( /^\S+\s*?/, '' );  	
  	
  	var srcurl = shortcode_parse_fix.match( /src=['"]([^']*?)['"]/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /src=['"]([^']*?)['"]/, '' );
  	if( srcurl == null ) {
  		srcurl = shortcode_parse_fix.match( /src=([^,\]\s]*)/ );
      fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /src=([^,\]\s]*)/, '' );
    }     
    
  	var srcrtmp = shortcode_parse_fix.match( /rtmp=['"]([^']*?)['"]/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /rtmp=['"]([^']*?)['"]/, '' );    
		var srcrtmp_path = shortcode_parse_fix.match( /rtmp_path=['"]([^']*?)['"]/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /rtmp_path=['"]([^']*?)['"]/, '' );        
    
    var srcurl1 = shortcode.match( /src1=['"]([^']*?)['"]/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /src1=['"]([^']*?)['"]/, '' );
  	if( srcurl1 == null ) {
  		srcurl1 = shortcode.match( /src1=([^,\]\s]*)/ );
      fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /src1=([^,\]\s]*)/, '' );
    }      
    
    var srcurl2 = shortcode.match( /src2=['"]([^']*?)['"]/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /src2=['"]([^']*?)['"]/, '' );
  	if( srcurl2 == null ) {
  		srcurl2 = shortcode.match( /src2=([^,\]\s]*)/ );
      fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /src2=([^,\]\s]*)/, '' );
    }
  	                                                                          
    var iheight = shortcode_parse_fix.match( /height="?(\d*)"?/ );			
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /height="?(\d*)"?/, '' );
  	var iwidth = shortcode_parse_fix.match( /width="?(\d*)"?/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /width="?(\d*)"?/, '' );
  	var sautoplay = shortcode.match( /autoplay=([^\s]+)/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /autoplay=([^\s]+)/, '' );
    var sembed = shortcode.match( /embed=([^\s]+)/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /embed=([^\s]+)/, '' );
  	var ssplash = shortcode.match( /splash='([^']*)'/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /splash='([^']*)'/, '' );
  	var ssubtitles = shortcode.match( /subtitles='([^']*)'/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /subtitles='([^']*)'/, '' );    
  	var smobile = shortcode.match( /mobile='([^']*)'/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /mobile='([^']*)'/, '' );        
    var sredirect = shortcode.match( /redirect='([^']*)'/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /redirect='([^']*)'/, '' );
  	if( ssplash == null ) {
  		ssplash = shortcode.match( /splash=([^,\]\s]*)/ );
      fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /splash=([^,\]\s]*)/, '' );
    }			
  	var spopup = shortcode.match( /popup='([^']*)'/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /popup='([^']*)'/, '' );
    var sad = shortcode.match( /ad='([^']*)'/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /ad='([^']*)'/, '' ); 
    var iadheight = shortcode_parse_fix.match( /ad_height="?(\d*)"?/ );			
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /ad_height="?(\d*)"?/, '' );
  	var iadwidth = shortcode_parse_fix.match( /ad_width="?(\d*)"?/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /ad_width="?(\d*)"?/, '' );      
    var sloop = shortcode.match( /loop=([^\s]+)/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /loop=([^\s]+)/, '' );
    var ssplashend = shortcode.match( /splashend=([^\s]+)/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /splashend=([^\s]+)/, '' );
  	var sad_skip = shortcode.match( /ad_skip=([^\s]+)/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /ad_skip=([^\s]+)/, '' ); 
  	var salign = shortcode.match( /align="([^"]+)"/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /align="([^"]+)"/, '' ); 
    
  	var sPlaylist = shortcode_parse_fix.match( /playlist=['"]([^']*?)['"]/ );
    fv_wp_fp_shortcode_remains = fv_wp_fp_shortcode_remains.replace( /playlist=['"]([^']*?)['"]/, '' );        
    
  	if( srcrtmp != null && srcrtmp[1] != null ) {
  		document.getElementById("fv_wp_flowplayer_field_rtmp").value = srcrtmp[1];
  		document.getElementById("fv_wp_flowplayer_field_rtmp_wrapper").style.display = 'table-row';
  		document.getElementById("add_rtmp_wrapper").style.display = 'none';   
  	}
    if( srcrtmp_path != null && srcrtmp_path[1] != null ) {
  		document.getElementById("fv_wp_flowplayer_field_rtmp_path").value = srcrtmp_path[1];
      document.getElementById("fv_wp_flowplayer_field_rtmp_wrapper").style.display = 'table-row';
      document.getElementById("add_rtmp_wrapper").style.display = 'none';           
    }    
    
    if( srcurl != null && srcurl[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_src").value = srcurl[1];
    if( srcurl1 != null && srcurl1[1] != null ) {
  		document.getElementById("fv_wp_flowplayer_field_src_1").value = srcurl1[1];
      document.getElementById("fv_wp_flowplayer_field_src_1_wrapper").style.display = 'table-row';
      //document.getElementById("fv_wp_flowplayer_field_src_1_uploader").style.display = 'table-row';
      if( srcurl2 != null && srcurl2[1] != null ) {
    		document.getElementById("fv_wp_flowplayer_field_src_2").value = srcurl2[1];
        document.getElementById("fv_wp_flowplayer_field_src_2_wrapper").style.display = 'table-row';
        //document.getElementById("fv_wp_flowplayer_field_src_2_uploader").style.display = 'table-row';
        document.getElementById("add_format_wrapper").style.display = 'none';        
      }            
    }     
    
  	if( srcurl != null && srcurl[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_src").value = srcurl[1];
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
  	if( sembed != null && sembed[1] != null ) {
  		if (sembed[1] == 'true') 
        document.getElementById("fv_wp_flowplayer_field_embed").selectedIndex = 1;
      if (sembed[1] == 'false') 
        document.getElementById("fv_wp_flowplayer_field_embed").selectedIndex = 2;
    }    
  	if( smobile != null && smobile[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_mobile").value = smobile[1];          
  	if( ssplash != null && ssplash[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_splash").value = ssplash[1];
  	if( ssubtitles != null && ssubtitles[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_subtitles").value = ssubtitles[1];      
  	if( spopup != null && spopup[1] != null ) {
  		spopup = spopup[1].replace(/&#039;/g,'\'').replace(/&quot;/g,'"').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
  		spopup = spopup.replace(/&amp;/g,'&');
  		document.getElementById("fv_wp_flowplayer_field_popup").value = spopup;
  	}
  	if( sad != null && sad[1] != null ) {
  		sad = sad[1].replace(/&#039;/g,'\'').replace(/&quot;/g,'"').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
  		sad = sad.replace(/&amp;/g,'&');
  		document.getElementById("fv_wp_flowplayer_field_ad").value = sad;
  	}  		
  	if( iadheight != null && iadheight[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_ad_height").value = iheight[1];
  	if( iadwidth != null && iadwidth[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_ad_width").value = iwidth[1];
    if( sad_skip != null && sad_skip[1] != null && sad_skip[1] == 'yes' )
  		document.getElementById("fv_wp_flowplayer_field_ad_skip").checked = 1;   		
    if( sredirect != null && sredirect[1] != null )
  		document.getElementById("fv_wp_flowplayer_field_redirect").value = sredirect[1];
    if( sloop != null && sloop[1] != null && sloop[1] == 'true' )
  		document.getElementById("fv_wp_flowplayer_field_loop").checked = 1;
    if( ssplashend != null && ssplashend[1] != null && ssplashend[1] == 'show' )
  		document.getElementById("fv_wp_flowplayer_field_splashend").checked = 1;  

  	if( salign != null && salign[1] != null ) {
  		if (salign[1] == 'left') 
        document.getElementById("fv_wp_flowplayer_field_align").selectedIndex = 1;
      if (salign[1] == 'right') 
        document.getElementById("fv_wp_flowplayer_field_align").selectedIndex = 2;
    }    
    
    var sPlaylistHTML = '';
    if( sPlaylist ) {    	
			aPlaylist = sPlaylist[1].split(';');
			for( sPlI in aPlaylist ) {			
				sPlaylistHTML += fv_wp_flowplayer_playlist_item( aPlaylist[sPlI] );
			}
    } else if( jQuery('#fv_wp_flowplayer_field_playlist table tbody tr').length == 0 )  {
    	sPlaylistHTML = fv_wp_flowplayer_playlist_item(',');   	
    }
    
		jQuery('#fv_wp_flowplayer_field_playlist table tbody').html( sPlaylistHTML );
		jQuery('#fv_wp_flowplayer_field_playlist table tbody').sortable();    
  	
  	jQuery("#fv_wp_flowplayer_field_insert-button").attr( 'value', 'Update' );    
	} else {
    fv_wp_fp_shortcode_remains = '';
  }
  
  jQuery('.fv_wp_flowplayer_playlist_head').hover(
  	function() { jQuery(this).find('.fv_wp_flowplayer_playlist_remove').show(); }, function() { jQuery(this).find('.fv_wp_flowplayer_playlist_remove').hide(); } );  
  
  jQuery('#cboxContent').css('background','white');
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
	
	if(
		jQuery("#fv_wp_flowplayer_field_rtmp_wrapper").is(":visible") &&
		(
			( jQuery("#fv_wp_flowplayer_field_rtmp").val() != '' && jQuery("#fv_wp_flowplayer_field_rtmp_path").val() == '' ) ||
			( jQuery("#fv_wp_flowplayer_field_rtmp").val() == '' && jQuery("#fv_wp_flowplayer_field_rtmp_path").val() != '' )
		)
	) {
		alert('Please enter both server and path for your RTMP video.');
		return false;
	} else if( document.getElementById("fv_wp_flowplayer_field_src").value == '' && jQuery("#fv_wp_flowplayer_field_rtmp").val() == '' && jQuery("#fv_wp_flowplayer_field_rtmp_path").val() == '') {
		alert('Please enter the file name of your video file.');
		return false;
	} else 
	
	shortcode = '[' + shorttag;	
   
  if ( document.getElementById("fv_wp_flowplayer_field_src").value != '' ) {
    shortcode += ' src=\'' + document.getElementById("fv_wp_flowplayer_field_src").value + '\''; 
  } 
   
  if ( document.getElementById("fv_wp_flowplayer_field_src_1").value != '' ) {
    shortcode += ' src1=\'' + document.getElementById("fv_wp_flowplayer_field_src_1").value + '\''; 
  }
  if ( document.getElementById("fv_wp_flowplayer_field_src_2").value != '' ) {
    shortcode += ' src2=\'' + document.getElementById("fv_wp_flowplayer_field_src_2").value + '\''; 
  }
  
  if ( document.getElementById("fv_wp_flowplayer_field_rtmp").value != '' ) {
    shortcode += ' rtmp="' + document.getElementById("fv_wp_flowplayer_field_rtmp").value + '"'; 
  }
  if ( document.getElementById("fv_wp_flowplayer_field_rtmp_path").value != '' ) {
    shortcode += ' rtmp_path="' + document.getElementById("fv_wp_flowplayer_field_rtmp_path").value + '"'; 
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
    
  if( document.getElementById("fv_wp_flowplayer_field_embed").selectedIndex == 1 )
	  shortcode += ' embed=true';
	if( document.getElementById("fv_wp_flowplayer_field_embed").selectedIndex == 2 )
	  shortcode += ' embed=false';    
    
  if( document.getElementById("fv_wp_flowplayer_field_align").selectedIndex == 1 )
	  shortcode += ' align="left"';
	if( document.getElementById("fv_wp_flowplayer_field_align").selectedIndex == 2 )
	  shortcode += ' align="right"';    
        
    
  if( document.getElementById("fv_wp_flowplayer_field_loop").checked )
		shortcode += ' loop=true';    
		
	if( document.getElementById("fv_wp_flowplayer_field_mobile").value != '' )
		shortcode += ' mobile=\'' + document.getElementById("fv_wp_flowplayer_field_mobile").value + '\'';    		
		
	if( document.getElementById("fv_wp_flowplayer_field_splash").value != '' )
		shortcode += ' splash=\'' + document.getElementById("fv_wp_flowplayer_field_splash").value + '\'';
    
	if( document.getElementById("fv_wp_flowplayer_field_subtitles").value != '' )
		shortcode += ' subtitles=\'' + document.getElementById("fv_wp_flowplayer_field_subtitles").value + '\'';    
    
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
	
  if( document.getElementById("fv_wp_flowplayer_field_ad").value != '' ) {
		var ad = document.getElementById("fv_wp_flowplayer_field_ad").value;
		ad = ad.replace(/&/g,'&amp;');
		ad = ad.replace(/'/g,'\\\'');
		ad = ad.replace(/"/g,'&quot;');
		ad = ad.replace(/</g,'&lt;');
		ad = ad.replace(/>/g,'&gt;');
		shortcode += ' ad=\'' + ad +'\''
	}     	
	
	if( document.getElementById("fv_wp_flowplayer_field_ad_width").value != '' )
		shortcode += ' ad_width=' + document.getElementById("fv_wp_flowplayer_field_ad_width").value;
	if( document.getElementById("fv_wp_flowplayer_field_ad_height").value != '' )
		shortcode += ' ad_height=' + document.getElementById("fv_wp_flowplayer_field_ad_height").value;		
	if( document.getElementById("fv_wp_flowplayer_field_ad_skip").checked != '' )
		shortcode += ' ad_skip=yes';			
		
	if( jQuery('#fv_wp_flowplayer_field_playlist table tr td').length > 0 ) {
		var aPlaylistItems = new Array();
		jQuery('#fv_wp_flowplayer_field_playlist table tr td').each(function() {
				var aPlaylistItem = new Array();
				jQuery(this).find('input').each( function() {
					if( jQuery(this).attr('value').trim().length > 0 ) { 
						aPlaylistItem.push(jQuery(this).attr('value').trim());
					}
				} );			
				if( aPlaylistItem.length > 0 ) {
					aPlaylistItems.push(aPlaylistItem.join(','));
				}
			}
		);
		var sPlaylistItems = aPlaylistItems.join(';');
		if( sPlaylistItems.length > 0 ) {
			shortcode += ' playlist="'+sPlaylistItems+'"';
		}
	}
	
	if( fv_wp_fp_shortcode_remains.trim().length > 0 ) {
  	shortcode += ' ' + fv_wp_fp_shortcode_remains.trim();
  }
  
	shortcode += ']';
	
	jQuery(".fv-wordpress-flowplayer-button").colorbox.close();
  
	fv_wp_flowplayer_insert( shortcode );  
}

function fv_wp_flowplayer_add_format() {
  if ( jQuery("#fv_wp_flowplayer_field_src").val() != '' ) {
    if ( jQuery("#fv_wp_flowplayer_field_src_1_wrapper").is(":visible") ) {      
      if ( jQuery("#fv_wp_flowplayer_field_src_1").val() != '' ) {
        jQuery("#fv_wp_flowplayer_field_src_2_wrapper").show();
        jQuery("#fv_wp_flowplayer_field_src_2_uploader").show();
        jQuery("#add_format_wrapper").hide();
      }
      else {
        alert('Please enter the file name of your second video file.');
      }
    }
    else {
      jQuery("#fv_wp_flowplayer_field_src_1_wrapper").show();
      jQuery("#fv_wp_flowplayer_field_src_1_uploader").show();
    }
  }
  else {
    alert('Please enter the file name of your video file.');
  }
}

function fv_wp_flowplayer_add_rtmp() {
	jQuery("#fv_wp_flowplayer_field_rtmp_wrapper").show();
	jQuery("#add_rtmp_wrapper").hide();
}