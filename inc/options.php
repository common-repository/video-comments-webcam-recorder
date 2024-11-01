<?php
namespace VideoWhisper\InlineRecorder;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//ini_set('display_errors', 1);

trait Options {
	//define and edit settings


	//! Admin Side

		static function admin_bar_menu($wp_admin_bar)
		{
			if (!is_user_logged_in()) return;

			$options = get_option('VWinlineRecorderOptions');

			if( current_user_can('editor') || current_user_can('administrator') ) {


				//find VideoWhisper menu
				$nodes = $wp_admin_bar->get_nodes();
				if (!$nodes) $nodes = array();
				$found = 0;
				foreach ( $nodes as $node ) if ($node->title == 'VideoWhisper') $found = 1;

					if (!$found)
					{
						
						$wp_admin_bar->add_node(
					array(
						'id'    => 'videowhisper',
						'title' => '👁 VideoWhisper',
						'href'  => admin_url( 'plugin-install.php?s=videowhisper&tab=search&type=term' ),
					)
					);

						//more VideoWhisper menus

						$wp_admin_bar->add_node( array(
								'parent' => 'videowhisper',
								'id'     => 'videowhisper-add',
								'title' => __('Add Plugins', 'paid-membership'),
								'href'  => admin_url('plugin-install.php?s=videowhisper&tab=search&type=term'),
							) );

						$wp_admin_bar->add_node( array(
								'parent' => 'videowhisper',
								'id'     => 'videowhisper-contact',
								'title' => __('Contact Support', 'paid-membership'),
								'href'  => 'https://videowhisper.com/tickets_submit.php?topic=WordPress+Plugins+' . urlencode($_SERVER['HTTP_HOST']),
							) );
					}


				$menu_id = 'videowhisper-irecorder';

				$wp_admin_bar->add_node( array(
						'parent' => 'videowhisper',
						'id'     => $menu_id,
						'title' => '📹 ' . __('Form Recorder', 'paid-membership'),
						'href'  => admin_url('admin.php?page=irecorder')
					) );
			
				$wp_admin_bar->add_node( array(
						'parent' => $menu_id,
						'id'     => $menu_id . '-settings',
						'title' => __('Settings', 'ppv-live-webcams'),
						'href'  => admin_url('admin.php?page=irecorder')
					) );


				$wp_admin_bar->add_node( array(
						'parent' => $menu_id,
						'id'     => $menu_id . '-hosting',
						'title' => __('Complete Hosting', 'ppv-live-webcams'),
						'href'  => 'https://webrtchost.com/hosting-plans/'
					) );
	
				$wp_admin_bar->add_node( array(
						'parent' => $menu_id,
						'id'     => $menu_id . '-turnkey',
						'title' => __('Turnkey Plans', 'ppv-live-webcams'),
						'href'  => 'https://paidvideochat.com/order/'
					) );
	
					$wp_admin_bar->add_node(
					array(
						'parent' => $menu_id . '-wpreview',
						'id'     => $menu_id . '-wpreview',
						'title'  => __( 'Review WP Plugin', 'paid-membership' ),
						'href'   => 'https://wordpress.org/support/plugin/video-comments-webcam-recorder/reviews/#new-post',
					)
				);
								
			}



		}

		
		static function admin_menu() {

			add_menu_page('Form Recorder', 'Form Recorder', 'manage_options', 'irecorder', array( 'VWinlineRecorder', 'adminOptions' ), 'dashicons-video-alt2',83);
			add_submenu_page("irecorder", "Settings", "Settings", 'manage_options', "irecorder-settings", array( 'VWinlineRecorder', 'adminOptions' ) );
		}
		
		static function settings_link($links) {
			$settings_link = '<a href="admin.php?page=irecorder">'.__("Settings").'</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

		static function getOptions()
		{
			$options = get_option('VWinlineRecorderOptions');
			if (!$options) $options =  self::adminOptionsDefault();
	
			return $options;
		}
	
		static function adminOptionsDefault()
		{
			$root_url = get_bloginfo( "url" ) . "/";
			$upload_dir = wp_upload_dir();

			return array(
						'comments' => 1,
						'whitelabel' => 0, 
						'mediaLibrary' => 0,						
						'videosharevod' => 0,

						'appSetup' => unserialize('a:1:{s:6:"Config";a:12:{s:8:"darkMode";s:0:"";s:16:"resolutionHeight";s:3:"480";s:7:"bitrate";s:3:"750";s:19:"maxResolutionHeight";s:4:"1080";s:10:"maxBitrate";s:4:"3500";s:15:"recorderMaxTime";s:3:"300";s:12:"recorderMode";s:5:"video";s:19:"recorderModeDisable";s:0:"";s:10:"recordStay";s:1:"1";s:14:"recordMultiple";s:1:"1";s:12:"recordClosed";s:1:"1";s:12:"timeInterval";s:6:"300000";}}'),
			'appSetupConfig' => '
; This configures HTML5 Videochat application and other apps that use same API.

[Config]						; Application settings
darkMode = false 			 	; true/false : start app in dark mode
resolutionHeight = 480			; streaming resolution, maximum 480p in free mode
bitrate = 750					; streaming bitrate in kbps, maximum 750kbps in free mode
maxResolutionHeight = 1080 		; maximum selectable resolution height, maximum 480p in free mode
maxBitrate = 3500				; maximum selectable streaming bitrate in kbps, maximum 750kbps in free mode, also limited by hosting
recorderMaxTime = 300			; maximum recording time in seconds, limited in free mode
recorderMode = video			; video/audio mode
recorderModeDisable	= false		; disable user from toggling video/audio mode
recordStay = true 				; prevents redirect after recording
recordMultiple = true			; enable multiple recordings with an add recording button
recordClosed = true				; recorder starts closed, requires recordMultiple to use
timeInterval = 300000			; check web connection to server
',

			'appCSS' => '
.ui.button
{
width: auto !important;
height: inherit !important;
}

.ui .item
{
 margin-top: 0px !important;
}

.ui.modal>.content
{
margin: 0px !important;
}
.ui.header .content
{
background-color: inherit !important;
}

.site-inner
{
max-width: 100%;
}

.panel
{
padding: 0px !important;
margin: 0px !important;
}						
			',
			
				'uploadsPath' => $upload_dir['basedir'] . '/vw_recordings',

				'canRecord' => 'members',
				'recordList' => 'Super Admin, Administrator, Editor, Author, Contributor, Subscriber',

				'videowhisper' => 1
			);

		}

		static function getAdminOptions() {

			$adminOptions = self::adminOptionsDefault();

			$options = get_option('VWinlineRecorderOptions');
			if (!empty($options)) {
				foreach ($options as $key => $option)
					$adminOptions[$key] = $option;
			}
			update_option('VWinlineRecorderOptions', $adminOptions);
			return $adminOptions;
		}
	
		
		static function adminOptions()
		{

				$options = self::getAdminOptions();

				$optionsDefault = self::adminOptionsDefault();

				//var_dump($options);
				if (isset($_POST)) if (!empty($_POST))
				{

				$nonce = $_REQUEST['_wpnonce'];
				if ( ! wp_verify_nonce( $nonce, 'vwsec' ) )
				{
					echo 'Invalid nonce!';
					exit;
				}


					foreach ($options as $key => $value)
						if (isset($_POST[$key])) $options[$key] = sanitize_textarea_field( $_POST[$key] );

					if (isset($_POST['appSetupConfig']))
						$options['appSetup'] = parse_ini_string(sanitize_textarea_field($_POST['appSetupConfig']), true);
						
						update_option('VWinlineRecorderOptions', $options);
				}


				$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_file_name( $_GET[ 'tab' ] ) : 'html5';

?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>VideoWhisper / Form Recorder / Settings</h2>
</div>

<h2 class="nav-tab-wrapper">
	<a href="options-general.php?page=irecorder&tab=html5" class="nav-tab <?php echo $active_tab=='html5'?'nav-tab-active':'';?>">HTML5 Recorder</a>
	<a href="options-general.php?page=irecorder&tab=integration" class="nav-tab <?php echo $active_tab=='integration'?'nav-tab-active':'';?>">Integrations</a>
</h2>



<form method="post" action="<?php echo wp_nonce_url($_SERVER["REQUEST_URI"], 'vwsec'); ?>">

<?php


				switch ($active_tab)
				{
				case 'integration':
?>
<h2>Integrations for HTML5 Recorder</h2>
<h4>Media Library</h4>
<select name="mediaLibrary" id="mediaLibrary">
  <option value="0" <?php echo $options['mediaLibrary']?"":"selected"?>>Disabled</option>
  <option value="1" <?php echo $options['mediaLibrary']?"selected":""?>>Enabled</option>
</select>
<br>Add recorded files to Media Library.

<h4>Video Share VOD <a target="_plugin" href="https://videosharevod.com/">Plugin</a></h4>
<?php
			if (is_plugin_active('video-share-vod/video-share-vod.php')) echo 'Detected:  <a href="admin.php?page=video-share">Configure</a> | <a href="https://videosharevod.com/features/quick-start-tutorial/">Tutorial</a>'; else echo 'Not detected. Please install and activate <a target="_videosharevod" href="https://wordpress.org/plugins/video-share-vod/">VideoShareVOD Plugin</a> from <a href="plugin-install.php">Plugins > Add New</a>!';
?>
<BR><select name="videosharevod" id="videosharevod">
  <option value="0" <?php echo $options['videosharevod']?"":"selected"?>>Disabled</option>
  <option value="1" <?php echo $options['videosharevod']?"selected":""?>>Enabled</option>
</select>
<br>This feature requires FFmpeg with involved codecs.
<br>Recorder videos are automatically imported into VideoShareVOD.
<br>To also add source recorded files to Media Library, make sure Video Share VOD > Import > Delete Original on Import is disabled. Otherwise it will remove the original recording files (added to the library).


<h4>WP Comments</h4>
<select name="comments" id="comments">
  <option value="0" <?php echo $options['comments']?"":"selected"?>>Disabled</option>
  <option value="1" <?php echo $options['comments']?"selected":""?>>Enabled</option>
</select>
<br>Toggle integration with WordPress comments. The comments form includes a new button to add recordings. Recording are listed with the comments as link to open in new page and embed. Disabling integration also hides previously recorded videos from comments. 

<?php
					break;	
					
				case 'html5':
?>
<h2>HTML5 Recorder Settings</h2>
Display HTML5 Recorder with shortcode [videowhisper_recorder_inline field="recordings" add_field="1" label=""]. This web app implementation is based on <a href="https://demo.videowhisper.com/cam-recorder-html5-video-audio/">HTML5 Videochat / Cam Recoder </a>. 
<br>When a recording is sent to server, application calls window.VideoWhisper.recoderUploadCompleted([filename]). Unless field parameter is blank, the function is also implemented and adds the filename to value of field with provided id. Field is added by default unless disabled with add_field parameter. A field label can be included.


<h4>App Configuration</h4>
<textarea name="appSetupConfig" id="appSetupConfig" cols="120" rows="12"><?php echo esc_textarea($options['appSetupConfig'])?></textarea>
<BR>Application setup parameters are delivered to app when connecting to server. Config section refers to application parameters. Room section refers to default room options (configurable from app at runtime). User section refers to default room options configurable from app at runtime and setup on access.

Default:<br><textarea readonly cols="120" rows="6"><?php echo esc_textarea($optionsDefault['appSetupConfig'])?></textarea>

<BR>Parsed configuration (should be an array or arrays):<BR>
<?php

			var_dump($options['appSetup']);
?>
<BR>Serialized:<BR>
<?php

			echo esc_html(serialize($options['appSetup']));
?>

<h4>App CSS</h4>
<textarea name="appCSS" id="appCSS" cols="100" rows="6"><?php echo esc_textarea($options['appCSS'])?></textarea>
<br>
CSS code to adjust or fix application styling if altered by site theme. Multiple interface elements are implemented by <a href="https://fomantic-ui.com">Fomantic UI</a> (a fork of <a href="https://semantic-ui.com">Semantic UI</a>). Editing interface and layout usually involves advanced CSS skills. For reference also see <a href="https://paidvideochat.com/html5-videochat/css/">Layout CSS</a>. Default:<br><textarea readonly cols="100" rows="3"><?php echo esc_textarea($optionsDefault['appCSS'])?></textarea>

<h4><?php _e('Uploads Path','video-share-vod'); ?></h4>
<input name="uploadsPath" type="text" id="uploadsPath" size="100" maxlength="256" value="<?php echo esc_attr(trim($options['uploadsPath']))?>"/>
<br>Where recordings are originally uploaded. Default: <?php echo esc_html( trim( $optionsDefault['uploadsPath'] ))?>
<?php
if (!@file_exists($options['uploadsPath']) ) echo '<BR>WARNING! Path does not exist: ' . esc_html($options['uploadsPath']);
?>

<h4>Who can record videos</h4>
<select name="canRecord" id="canRecord">
  <option value="all" <?php echo $options['canRecord']=='all'?"selected":""?>>Anybody (including visitors)</option>
  <option value="members" <?php echo $options['canRecord']=='members'?"selected":""?>>All Members</option>
  <option value="list" <?php echo $options['canRecord']=='list'?"selected":""?>>Members in List</option>
</select>
<BR>Functionality should be limited to users that need to use this. Visitors not recommended.

<h4>Members allowed to record video (comma separated usernames-logins, roles, IDs)</h4>
<textarea name="recordList" cols="100" rows="3" id="recordList"><?php echo esc_textarea($options['recordList'])?>
</textarea>
<BR>If setting above is Members in List.

<h4>Whitelabel Mode: Remove Author Attribution Notices (Explicit Permission Required)</h4>
<select name="whitelabel" id="whitelabel">
	<option value="0" <?php echo ! $options['whitelabel'] ? 'selected' : ''; ?>>Disabled</option>
	<option value="1" <?php echo $options['whitelabel'] == '1' ? 'selected' : ''; ?>>Enabled</option>
</select>
<br>Embedded HTML5 Videochat application is branded with subtle attribution references to authors, similar to most software solutions in the world. Removing the default author attributions can be permitted by authors with a <a href="https://videowhisper.com/tickets_submit.php?topic=WhiteLabel+HTML5+Videochat">special licensing agreement</a>, in addition to full mode. Whitelabelling is an extra option that can be added to full mode.
<br>Warning: Application will not start if whitelabel mode is enabled and explicit licensing agreement from authors is not available, to remove attribution notices.
					
<?php
					break;	
		}

				submit_button(); ?>
</form>
	 <?php
	}


}