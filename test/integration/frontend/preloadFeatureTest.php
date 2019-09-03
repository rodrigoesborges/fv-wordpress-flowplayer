<?php

require_once( dirname(__FILE__).'/../fv-player-unittest-case.php');

/**
 * Tests a preload feature on the player.
 */
final class FV_Player_PreloadFeatureTestCase extends FV_Player_UnitTestCase {
  
  public function setUp() {
    parent::setUp();
    
    $shortcode_body = 'src="https://cdn.site.com/video1.mp4" splash="https://cdn.site.com/video1.jpg" preload="true" share="no" embed="false"';

    // create a post with preload turned on
    $this->post = $this->factory->post->create( array(
      'post_content' => '[fvplayer '.$shortcode_body.']'
    ) );

    // create a post with DB shortcode
    global $FV_Player_Db;
    $FV_Player_Db->import_player_data( false, false, json_decode( file_get_contents(dirname(__FILE__).'/player-data-preload.json'), true) );
    $FV_Player_Db->import_player_data( false, false, json_decode( file_get_contents(dirname(__FILE__).'/player-data.json'), true) );

  }

  public function testPreloadFromShortcode() {
    global $post;
    
    $post = get_post( $this->post );
    $output = apply_filters( 'the_content', $post->post_content );
    
    $sample = <<< HTML
    <div id="wpfp_some-test-hash" data-item="{&quot;sources&quot;:[{&quot;src&quot;:&quot;https:\/\/cdn.site.com\/video1.mp4&quot;,&quot;type&quot;:&quot;video\/mp4&quot;}]}" class="flowplayer no-brand is-poster no-svg is-paused skin-slim fp-slim fp-edgy" data-fvpreload="true" style="max-width: 640px; max-height: 360px; " data-ratio="0.5625">
      <div class="fp-ratio" style="padding-top: 56.25%"></div>
      <img class="fp-splash" alt="video" src="https://cdn.site.com/video1.jpg" />
      <div class="fp-ui"><noscript>Please enable JavaScript</noscript><div class="fp-preload"><b></b><b></b><b></b><b></b></div></div>
    </div>
HTML;
    
    $this->assertEquals( $this->fix_newlines($sample), $this->fix_newlines($output) );
  }

  public function testPreloadFromDB() {
    $output = apply_filters( 'the_content', '[fvplayer id="1"]' );

    $sample = <<< HTML
<div id="wpfp_some-test-hash" class="flowplayer no-brand is-poster no-svg is-paused skin-slim fp-slim fp-edgy has-playlist has-playlist-horizontal" data-fvpreload="true" style="max-width: 100%; " data-ratio="0.5625">
  <div class="fp-ratio" style="padding-top: 56.25%"></div>
  <img class="fp-splash" alt="Fire" src="https://foliovision.com/video/burning-hula-hoop-girl-dominika.jpg" />
  <div class="fp-ui"><noscript>Please enable JavaScript</noscript><div class="fp-preload"><b></b><b></b><b></b><b></b></div></div>
  </div>
  <div class="fp-playlist-external fv-playlist-design-2017 fp-playlist-horizontal fp-playlist-has-captions skin-slim" rel="wpfp_some-test-hash">
  <a href='#' data-item='{"sources":[{"src":"https:\/\/foliovision.com\/videos\/dominika-960-31.mp4","type":"video\/mp4"}],"id":1234,"fv_title":"Fire"}'><div class='fvp-playlist-thumb-img'><img  src='https://foliovision.com/video/burning-hula-hoop-girl-dominika.jpg' /></div><h4><span>Fire</span></h4></a>
  <a href='#' data-item='{"sources":[{"src":"https:\/\/foliovision.com\/videos\/Paypal-video-on-home-page.mp4","type":"video\/mp4"}],"id":1234,"fv_title":"PayPal Background Video","subtitles":[{"srclang":"en","label":"English","src":"https:\/\/foliovision.com\/videos\/paypal-splash.vtt"}]}'><div class='fvp-playlist-thumb-img'><img  src='https://foliovision.com/videos/paypal-splash.jpg' /></div><h4><span>PayPal Background Video</span></h4></a>
  <a href='#' data-item='{"sources":[{"src":"https:\/\/foliovision.com\/videos\/Carly-Simon-Anticipation-1971.mp4","type":"video\/mp4"}],"id":1234,"fv_title":"Carly Simon","subtitles":[{"srclang":"en","label":"English","src":"https:\/\/foliovision.com\/images\/2014\/01\/carly-simon-1971-anticipation.vtt"}]}'><div class='fvp-playlist-thumb-img'><img  src='https://foliovision.com/images/2014/01/carly-simon-1971-anticipation.png' /></div><h4><span>Carly Simon</span></h4></a>
</div>
HTML;

    $this->assertEquals( $this->fix_newlines($sample), $this->fix_newlines($output) );
  }

  public function testGlobalPreloadFromDB() {
    global $fv_fp;
    $fv_fp->_set_option('preload', 'true');
    $output = apply_filters( 'the_content', '[fvplayer id="2"]' );

    $sample = <<< HTML
<div id="wpfp_some-test-hash" class="flowplayer no-brand is-poster no-svg is-paused skin-slim fp-slim fp-edgy has-playlist has-playlist-horizontal" data-fvpreload="true" style="max-width: 100%; " data-ratio="0.5625">
  <div class="fp-ratio" style="padding-top: 56.25%"></div>
  <img class="fp-splash" alt="Fire" src="https://foliovision.com/video/burning-hula-hoop-girl-dominika.jpg" />
  <div class="fp-ui"><noscript>Please enable JavaScript</noscript><div class="fp-preload"><b></b><b></b><b></b><b></b></div></div>
  </div>
  <div class="fp-playlist-external fv-playlist-design-2017 fp-playlist-horizontal fp-playlist-has-captions skin-slim" rel="wpfp_some-test-hash">
  <a href='#' data-item='{"sources":[{"src":"https:\/\/foliovision.com\/videos\/dominika-960-31.mp4","type":"video\/mp4"}],"id":1234,"fv_title":"Fire"}'><div class='fvp-playlist-thumb-img'><img  src='https://foliovision.com/video/burning-hula-hoop-girl-dominika.jpg' /></div><h4><span>Fire</span></h4></a>
  <a href='#' data-item='{"sources":[{"src":"https:\/\/foliovision.com\/videos\/Paypal-video-on-home-page.mp4","type":"video\/mp4"}],"id":1234,"fv_title":"PayPal Background Video","subtitles":[{"srclang":"en","label":"English","src":"https:\/\/foliovision.com\/videos\/paypal-splash.vtt"}]}'><div class='fvp-playlist-thumb-img'><img  src='https://foliovision.com/videos/paypal-splash.jpg' /></div><h4><span>PayPal Background Video</span></h4></a>
  <a href='#' data-item='{"sources":[{"src":"https:\/\/foliovision.com\/videos\/Carly-Simon-Anticipation-1971.mp4","type":"video\/mp4"}],"id":1234,"fv_title":"Carly Simon","subtitles":[{"srclang":"en","label":"English","src":"https:\/\/foliovision.com\/images\/2014\/01\/carly-simon-1971-anticipation.vtt"}]}'><div class='fvp-playlist-thumb-img'><img  src='https://foliovision.com/images/2014/01/carly-simon-1971-anticipation.png' /></div><h4><span>Carly Simon</span></h4></a>
</div>
HTML;

    $this->assertEquals( $this->fix_newlines($sample), $this->fix_newlines($output) );
  }

  public function tearDown() {
    delete_option('fv_player_popups');
  }

}
