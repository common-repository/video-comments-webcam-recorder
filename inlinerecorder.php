<?php
/*
Plugin Name: HTML5 Webcam/Screen/Mic Recorder for Video Comments and Forms
Plugin URI: https://fanspaysite.com
Description:  <strong>HTML5 Webcam/Screen/Mic Recorder for Video Comments and Forms</strong> enables inserting webcam/microphone recordings into WP comments and various forms, as inline fields. Implements a shortcode that generates a recording field. <a href='https://consult.videowhisper.com/'>Support</a>
Version: 2.2.4
Author: VideoWhisper.com
Author URI: https://videowhisper.com/
Contributors: videowhisper, VideoWhisper.com
Requires PHP: 7.4
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


//Legacy "comments" folder removed: available in v1.92.8 and older

require_once plugin_dir_path( __FILE__ ) .'/inc/options.php';
require_once plugin_dir_path( __FILE__ ) .'/inc/h5videochat.php';

use VideoWhisper\InlineRecorder;

if (!class_exists("VWinlineRecorder"))
{
	class VWinlineRecorder {

		use VideoWhisper\InlineRecorder\Options;
		use VideoWhisper\InlineRecorder\H5Videochat;
	
		public function __construct()
		{
		}


		function VWinlineRecorder()
		{
			//constructor
			self::__construct();
		}
	
		static function plugins_loaded()
		{

			$options = self::getOptions();

			add_shortcode('videowhisper_recorder_inline', array( 'VWinlineRecorder', 'videowhisper_recorder_inline'));

			//web app ajax calls
			add_action( 'wp_ajax_vw_irec_app', array('VWinlineRecorder', 'vw_irec_app') );
			add_action( 'wp_ajax_nopriv_vw_irec_app', array('VWinlineRecorder', 'vw_irec_app') );


			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin",  array('VWinlineRecorder', 'settings_link') );
			
			
			if ($options['comments'])
			{
				add_filter( 'comment_form_defaults', array('VWinlineRecorder', 'comment_form_defaults') );
				add_action( 'comment_post',	array('VWinlineRecorder', 'comment_post') );
				add_filter( 'get_comment_text',	array('VWinlineRecorder', 'get_comment_text'), 10, 3);

			}
	
		}
		
		static function comment_form_defaults($default)
		{
				//$commenter = wp_get_current_commenter();	
				
				$default['comment_field'] .= do_shortcode('[videowhisper_recorder_inline field="recordings" add_field="1" label=""]');
	
				return $default;
		}


		static function comment_post( $comment_id ) 
		{
				$recordings = sanitize_text_field( $_POST['recordings'] );
				if (!$recordings) return;
				
				$options = self::getOptions();
				
				$user = wp_get_current_user();
				if ($user) $userName = sanitize_file_name($user->user_login);
				else $userName = sanitize_file_name( self::get_ip_address() );

				$recordingFiles  = explode( ',', $recordings );
				
						foreach ( $recordingFiles as $recordingFile ) {
							if ( $recordingFile ) {
								if ( file_exists( $filePath = $options['uploadsPath'] . '/' . $userName . '/' . $recordingFile ) ) {
									$recordingsMeta[] = array(
										'name' => sanitize_text_field( $recordingFile ),
										'file' => $filePath,
										'size' => filesize( $filePath ),
									);
								} else {
									$htmlCode .= ' Recording file not found: ' . $filePath;
								}
							}
						}
						
			add_comment_meta( $comment_id, 'recordings', $recordingsMeta  );
		}

		static function get_comment_text( $text, $comment ) 
		{
			$recordings = get_comment_meta( get_comment_ID(), 'recordings', true );	
			if (!$recordings) return $text;
			if (!is_array($recordings)) return text;
			
			$htmlCode = '';
			foreach ( $recordings as $attachment )
						if ( file_exists( $attachment['file'] ) )
						{
						$htmlCode .= '<p><a class="ui label small" href="' . self::path2url( $attachment['file'] ) . '"> <i class="file video icon"></i>' . $attachment['name'] . ' ' . self::humanSize( filesize( $attachment['file'] ) ) . ' </a> ';
						
						$ext        = strtolower(pathinfo( $attachment['file'], PATHINFO_EXTENSION ));
						$videoExtensions = array( 'mp4', 'webm');
						$audioExtensions = array( 'mp3', 'ogg', 'opus');
						if (in_array($ext, $videoExtensions)) $htmlCode .= '<br><video playsinline controls style="max-width:320px" src="' . self::path2url( $attachment['file'] ) . '"></video>';
						if (in_array($ext, $audioExtensions)) $htmlCode .= '<br><audio playsinline controls style="max-width:320px" src="' . self::path2url( $attachment['file'] ) . '"></audio>';	
						
						$htmlCode .= '</p>';		
						}
						else $htmlCode .=  $attachment['name'] . ' missing ';
 
			return $text . $htmlCode;
		}


		
	}//class
}//if

//instantiate
if (class_exists("VWinlineRecorder"))
{
	$inlineRecorder = new VWinlineRecorder();
}

//Actions and Filters
if (isset($inlineRecorder))
{
	
	add_action('plugins_loaded', array(&$inlineRecorder, 'plugins_loaded'));
	add_action('admin_menu', array(&$inlineRecorder, 'admin_menu'));
	add_action( 'admin_bar_menu', array(&$inlineRecorder, 'admin_bar_menu'),90 );

}
?>