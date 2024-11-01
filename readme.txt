=== HTML5 Webcam/Screen/Mic Recorder for Video Comments and Forms ===
Contributors: videowhisper
Author: VideoWhisper.com
Author URI: https://videowhisper.com
Plugin Name: HTML5 Webcam/Screen/Mic Recorder for Video Comments and Forms
Plugin URI: https://demo.videowhisper.com/cam-recorder-html5-video-audio/
Donate link: https://site2stream.com/video/
Tags: webcam, recorder, HTML5, videowhisper, comments
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 5.0
Tested up to: 6.6
Stable tag: trunk

Easily add webcam, screen, and mic recordings to WordPress comments and forms with this shortcode-enabled plugin for video and audio submissions.

== Description ==

HTML5 Webcam Microphone Recorder Forms is a powerful, easy-to-use plugin that allows you to add video and audio recording capabilities directly into WordPress comment forms and custom forms. With this plugin, users can record from their webcam, screen, microphone and submit their recordings as part of their comment or form submission.

Key Features

* Seamless Form Integration: Adds a recording field to your forms with a simple shortcode. Users can easily record video or audio and submit it alongside their comments or form entries.
* Video Comments: Includes a button within the comments form for users to add video or audio recordings. This feature can be toggled on or off in the plugin settings. Submitted recordings are displayed as links that open in a new page or embed.
* Customizable Recorder: The recording interface appears in a user-friendly dialog box and allows multiple recordings. Recordings are uploaded to your server and can be automatically added to the Media Library or processed/published using the [Video Share VOD](https://videosharevod.com) plugin.
* Flexible Recording Options: Supports various recording types, including:
* * Webcam + Microphone Video
* * Screen + Microphone Video
* * Microphone-only Audio
* Simple Integration with Other Plugins: Perfect for use in more advanced setups like paid questions or message forms. Works well with the [PaidVideochat - Video Services Site](https://paidvideochat.com/) plugin.

Note: Recordings are saved in formats supported by the user’s browser (e.g., MP4, WebM). Please be aware that some formats may not be compatible with all browsers.

This plugin utilizes the latest HTML5 technology, based on the HTML5 Videochat / Cam Recorder web app, ensuring fast and reliable performance for both video and audio recording.

Transform your WordPress site by enabling rich, multimedia user interactions with this intuitive and versatile recording plugin.

This web app implementation is based on [HTML5 Videochat / Cam Recoder](https://demo.videowhisper.com/cam-recorder-html5-video-audio/) . 

Sample integration: Paid Questions / Messages forms can include recordings in [PaidVideochat - Video Services Site](https://paidvideochat.com/) plugin.


== Support ==
Display HTML5 Recorder with shortcode [[videowhisper_recorder_inline field="recordings" add_field="1" label=""]]. 
When a recording is sent to server, application calls window.VideoWhisper.recoderUploadCompleted([filename]). Unless field parameter is blank, the function is also implemented and adds the filename to value of field with provided id. Field is added by default unless disabled with add_field parameter. A field label can be included.
This web app implementation is based on [HTML5 Videochat / Cam Recoder](https://demo.videowhisper.com/cam-recorder-html5-video-audio/) . 

* [Contact VideoWhisper Technical Support](https://videowhisper.com/tickets_submit.php) for clarifications


== Screenshots ==
1. Video comments: include video/audio in WordPress comments
2. HTML5 Recorder: recorder webcam/microphone 100% web based


== Changelog ==

= 2.2 =
* Support for video comments 
* PHP 8 compatibility
* Screen recording

= 2.1 =
* Replaced with HTML5 Recorder

= 1.92.4 =
* Integrates Video Share VOD
* Integrates BuddyPress: allows inserting videos into activity posts

= 1.92 =
* Strobe player
* Container settings for easy setup on Red5 servers (Wowza recommended)
* Shortcode updates to work with other filtering plugins

= 1.55 =
* Integrates VideoWhisper Video Recorder 1.55
* HTML5 playback support (if conversion is possible)

= 1.45.2 =
* Shortcodes for code reliability
* Support for JwPlayer Plugin http://wordpress.org/extend/plugins/jw-player-plugin-for-wordpress/
* More settings
* Fixed plugin folder name

= 1.45 =
* First release
* Integrates VideoWhisper Video Recorder 1.45
* Record and embed video when writing post
* Settings
* Recordings list to delete recording files (if folder is accessible)