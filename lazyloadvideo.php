<?php
/**
 * Plugin Name: Lazyload video
 * Plugin URI: http://www.fatpixel.nl/labs/lazyload-video
 * Description: Lazyload video replaces the standard youtube embed with a clickable poster image. Youtube and it's scripts will be loaded after the user clicks.
 * Author: Marijn Tijhuis
 * Version: 0.1
 * Author URI: http://www.fatpixel.nl/
 * License: GPL2+
 * Text Domain: lazyload-video
*/

/**
 * [lazyloadvideo_replace_youtube description]
 * @param  [type] $return [description]
 * @param  [type] $data   [description]
 * @param  [type] $url    [description]
 * @return [type]         [description]
 *
 * Inspiration: http://wordpress.stackexchange.com/a/19533
 */
function lazyloadvideo_replace_youtube($return, $data, $url) {
	if ( (! is_feed()) && ($data->provider_name == 'YouTube') ) {
		$preview_url = '<a class="lazyload-video preview-youtube" href="' . $url . '" title="Play Video">Play video</a>';
		return $preview_url;
	}
	else return $return;
}
add_filter('oembed_dataparse','lazyloadvideo_replace_youtube', 10, 3);

// TODO: check if this is nesesary
/**
 * [lazyloadvideo_enqueue_jquery Enable jQuery]
 * @return [embed] [enqueues jQuery in the head of the site]
 */
function lazyloadvideo_enqueue_jquery() {
	wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_scripts', 'lazyloadvideo_enqueue_jquery' );

// TODO: check if security needs optimisation
/**
 * [enable_lazyloadvideo_youtube javascript that handles parsing of youtube link and clicking on placeholder]
 * @return [embed] [the script code that should go in the head]
 *
 * Inspiration: http://yabtb.blogspot.com/2012/02/youtube-videos-lazy-load-improved-style.html
 */
function enable_lazyloadvideo_youtube() { ?>

	<script type='text/javascript'>
		var $llv = jQuery.noConflict();

		// TODO: split the fuction into useful partials
		function doLazyloadVideo() {
			$llv("a.lazyload-video").each(function(index) {
				// Parse embed params (ID, start) from Youtube URL
				var embedparms = $llv(this).attr("href").split("/embed/")[1];
				if(!embedparms) embedparms = $llv(this).attr("href").split("://youtu.be/")[1];
				if(!embedparms) embedparms = $llv(this).attr("href").split("v=")[1].replace(/\&/,'?');
				var youid = embedparms.split("?")[0].split("#")[0];
				var start = embedparms.match(/[#&]t=(\d+)s/);
				if(start) start = start[1];
				else {
					start = embedparms.match(/[#&]t=(\d+)m(\d+)s/);
					if(start) start = parseInt(start[1])*60+parseInt(start[2]);
					else {
						start = embedparms.match(/[?&]start=(\d+)/);
						if(start) start = start[1];
					}
				}
				embedparms = embedparms.split("#")[0];

				// Set start time if passed in url
				if(start && embedparms.indexOf("start=") == -1)
					embedparms += ((embedparms.indexOf("?")==-1) ? "?" : "&") + "start="+start;

				// Add video info block based on embed params
				if(embedparms.indexOf("showinfo=0") != -1)
					$llv(this).html('');
				else
					$llv(this).html('<div class="lazyload-video-info">' + $llv(this).html() + '</div>');

				// Add placeholder div for video
				$llv(this).prepend('<div style="height:'+(parseInt($llv(this).css("height"))-4)+'px;width:'+(parseInt($llv(this).css("width"))-4)+'px;" class="lazyload-video-div"></div>');

				// Add poster image for video
				$llv(this).css("background", "#000 url(http://i2.ytimg.com/vi/"+youid+"/0.jpg) center center no-repeat");
				// Set id (to target onclick)
				$llv(this).attr("id", youid+index);
				// Add link to video as fallback
				$llv(this).attr("href", "http://www.youtube.com/watch?v="+youid+(start ? "#t="+start+"s" : ""));

				// Combine embedparams into a link, to use in iframe
				var emu = 'http://www.youtube.com/embed/'+embedparms;
				emu += ((emu.indexOf("?")==-1) ? "?" : "&") + "autoplay=1";

				// Buid Youtube iframe
				var videoFrame = '<iframe width="'+parseInt($llv(this).css("width"))+'" height="'+parseInt($llv(this).css("height"))+'" style="vertical-align:top;" src="'+emu+'" frameborder="0" allowfullscreen></iframe>';

				// Add onClick handler
				// TODO: implement actual event handler to the DOM element
				$llv(this).attr("onclick", "$llv('#"+youid+index+"').replaceWith('"+videoFrame+"');return false;");
			});
		}
		// Run once on document ready
		$llv(document).ready(function() {
			doLazyloadVideo();
		});
		// Run when possible ajaxRequest is finished
		$llv(document).ajaxStop(function(){
			doLazyloadVideo();
		});
	</script>

<?php }
add_action('wp_head', 'enable_lazyloadvideo_youtube');

/**
 * [lazyloadvideo_youtube_style enqueue some styles for this plugin]
 * @return [embed] [enqueues style in head]
 */
function lazyloadvideo_youtube_style() {
	wp_register_style( 'lazyload-video-style', plugins_url('style.css', __FILE__) );
	wp_enqueue_style( 'lazyload-video-style' );
}
add_action( 'wp_enqueue_scripts', 'lazyloadvideo_youtube_style' );

/***** Plugin by Fat Pixel || Marijn Tijhuis || fatpixel.nl *****/
?>