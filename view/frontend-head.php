<?php
/**
 * Displays metatags for frontend.
 */

global $fv_wp_flowplayer_ver;
?>

<?php if( is_admin() ) : ?>
	<script type="text/javascript" src="<?php echo RELATIVE_PATH ?>/flowplayer/flowplayer.min.js?ver=<?php echo $fv_wp_flowplayer_ver; ?>"></script>
<?php endif; ?>

<?php if ($this->conf['disableembedding'] == 'true') { ?>
	<script type="text/javascript">                                                                     
		flowplayer.conf.embed = false;
	</script>
<?php } ?>

<link rel="stylesheet" href="<?php echo RELATIVE_PATH; ?>/css/flowplayer.css?ver=<?php echo $fv_wp_flowplayer_ver; ?>" type="text/css" media="screen" />

<?php if ( isset($this->conf['key']) && $this->conf['key'] != 'false' && strlen($this->conf['key']) > 0 && isset($this->conf['logo']) && $this->conf['logo'] != 'false' && strlen($this->conf['logo']) > 0 ) : ?>
		<style type="text/css">
			.flowplayer .fp-logo { display: block; opacity: 1; }    
		</style>                                              
<?php endif; ?>

<style type="text/css">
	.flowplayer, flowplayer * { margin: 0 auto; display: block; }
	.flowplayer .fp-controls { background-color: <?php echo trim($this->conf['backgroundColor']); ?> !important; }
	.flowplayer { background-color: <?php echo trim($this->conf['canvas']); ?> !important; }
	.flowplayer .fp-duration { color: <?php echo trim($this->conf['durationColor']); ?> !important; }
	.flowplayer .fp-elapsed { color: <?php echo trim($this->conf['timeColor']); ?> !important; }
	.flowplayer .fp-volume { text-align: left; }
	.flowplayer .fp-volumelevel { background-color: <?php echo trim($this->conf['progressColor']); ?> !important; }  
	.flowplayer .fp-volumeslider { background-color: <?php echo trim($this->conf['bufferColor']); ?> !important; }
	.flowplayer .fp-timeline { background-color: <?php echo trim($this->conf['timelineColor']); ?> !important; }
	.flowplayer .fp-progress { background-color: <?php echo trim($this->conf['progressColor']); ?> !important; }
	.flowplayer .fp-buffer { background-color: <?php echo trim($this->conf['bufferColor']); ?> !important; }
	#content .fv-wp-flowplayer-notice { background-color: #FFFFE0; border-color: #E6DB55; margin: 5px 0 15px; padding: 0 0.6em; border-radius: 3px 3px 3px 3px; border-style: solid; border-width: 1px; }
	#content .fv-wp-flowplayer-notice p { font-family: sans-serif; font-size: 12px; margin: 0.5em 0; padding: 2px; }
  #content .fv-wp-flowplayer-notice blockquote, #content .fv-wp-flowplayer-notice pre { padding: 5px; margin: 0; }
  #content .fv-wp-flowplayer-error { background-color: #FFEBE8; border-color: #CC0000; }     
	#content .flowplayer a, .flowplayer a:hover { text-decoration: none; border-bottom: none; }
	#content .flowplayer { font-family: <?php echo trim($this->conf['font-face']); ?>; }
	#content .flowplayer .fp-embed-code { padding: 3px 7px; }
	#content .flowplayer .fp-embed-code textarea { line-height: 1.4; white-space: pre-wrap; color: <?php echo trim($this->conf['durationColor']); ?> !important; height: 160px; font-size: 10px; }
	
	.fv-wp-fp-hidden { display: none; }
	.fv-wp-flowplayer-notice-parsed .row { text-align: left; border-bottom: 1px solid lightgray; border-right: 1px solid lightgray; border-left: 1px solid lightgray; padding-left: 5px; font-size: 12px; clear: both; }
	.fv-wp-flowplayer-notice-parsed .close { height: 0px; }
	.fv-wp-flowplayer-notice-parsed .value { border-left: 1px solid lightgray; display: inline-block; float: right; padding-left: 5px; width: 300px; /*height: 21px; overflow: hidden;*/ }	
	.fv-wp-flowplayer-notice-parsed.indent { margin-left: 10px; }	
	.fv-wp-flowplayer-notice-parsed.level-1 { background: #f8f8f8; }
	.fv-wp-flowplayer-notice-parsed.level-2 { background: #f0f0f0; }	
	.fv-wp-flowplayer-notice-parsed.level-3 { background: #e8e8e8; }	
	.fv-wp-flowplayer-notice-parsed.level-4 { background: #e0e0e0; }	
	.fv-wp-flowplayer-notice-parsed.level-5 { background: #d8d8d8; }	
	.fv-wp-flowplayer-notice-parsed.level-6 { background: #d0d0d0; }	
	.fv-wp-flowplayer-notice-parsed.level-7 { background: #c8c8c8; }		
</style>