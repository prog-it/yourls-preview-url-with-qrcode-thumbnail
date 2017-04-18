<?php
/*
Plugin Name: Preview URL with QR Code and Thumbnail image
Plugin URI: https://github.com/prog-it/yourls-preview-url-with-qrcode-thumbnail
Description: Preview URLs before you're redirected there with QR code and Thumbnail image
Version: 1.0
Author: progit
Author URI: https://github.com/prog-it
*/

// EDIT THIS

// Character to add to a short URL to trigger the preview interruption
define( 'PROGIT_PREVIEW_CHAR', '~' );
// Type translation. 1 - Custom translation or 2 - English
define( 'PROGIT_TRANSLATION_TYPE', 1 );

// Translation
class Trans {
	// Do not edit key!
	private static $trans = array(
		'Preview short URL' => 'Предпросмотр сокращенного URL',
		'You requested a shortened URL' => 'Вы запросили сокращенный URL',
		'This URL points to' => 'Этот URL указывает на',
		'Long URL' => 'Длинный URL',
		'Title' => 'Описание',
		'QR code' => 'QR код',
		'If you still want to visit this URL, please go to' => 'Если вы все еще хотите посетить этот URL, пожалуйста, перейдите по',
		'this URL' => 'этой ссылке',
		'You will be redirected to another page. We are not responsible for the content of this page.' => 'Вы будете перенаправлены на другую страницу. Мы не несем ответственности за содержание этой страницы и последствий, которые могут иметь это для вас.',
	);
	
	public static function get($key) {
		if ( PROGIT_TRANSLATION_TYPE == 1) {
			return self::$trans[$key];
		} else {
			return $key;
		}
	}	
}


// Handle failed loader request and check if there's a ~
yourls_add_action( 'loader_failed', 'progit_preview_loader_failed' );

function progit_preview_loader_failed( $args ) {
	$request = $args[0];
	$pattern = yourls_make_regexp_pattern( yourls_get_shorturl_charset() );
	if( preg_match( "@^([$pattern]+)".PROGIT_PREVIEW_CHAR."$@", $request, $matches ) ) {
		$keyword = isset( $matches[1] ) ? $matches[1] : '';
		$keyword = yourls_sanitize_keyword( $keyword );
		progit_preview_show( $keyword );
		die();
	}
}

// Show the preview screen for a short URL
function progit_preview_show( $keyword ) {
	require_once( YOURLS_INC.'/functions-html.php' );
	$trans = new Trans;

	yourls_html_head( 'preview', $trans->get('Preview short URL') );
	//yourls_html_logo();

	$title = yourls_get_keyword_title( $keyword );
	$url   = yourls_get_keyword_longurl( $keyword );
	$base  = YOURLS_SITE;
	$char  = PROGIT_PREVIEW_CHAR;
	// Required this plugin - https://github.com/seandrickson/YOURLS-QRCode-Plugin
	$qrcode = YOURLS_SITE.'/'.$keyword.'.qr';
	// Required this plugin - https://github.com/prog-it/yourls-thumbnail-url
	$thumb = YOURLS_SITE.'/'.$keyword.'.i';

	echo <<<HTML
	<style>
		.halves {
			display: flex;
			display: -webkit-flex;
			display: -moz-flex;
			justify-content: space-between;
			-webkit-justify-content: space-between;
			-moz-justify-content: space-between;
			align-items: flex-start;
			-webkit-align-items: flex-start;
			-moz-align-items: flex-start;
		}
		.half-width {
			
		}
		.desc-box {
			line-height: 1.6em;
			width: 65%;
		}		
		.thumb-box {
			margin-right: 10px;
		}
		.short-thumb {
			width: 326px;
			height: 245px;
			border: 5px solid #151720;
		}	
		.short-qr {
			border: 1px solid #ccc;
			width: 100px;
			margin-top: 3px;
		}
		hr {
			margin: 10px 0;
			border: 0;
			border-top: 1px solid #eee;
			border-bottom: 1px solid #fff;
			display: block;
			clear: both;
		}
		.disclaimer {
			color: #aaa;
		}
		/* Mobile */
		@media screen and (max-width: 720px) {
			.halves {
				display: block;
			}
			.half-width {
				width: 100%;
			}
			.thumb-box {
				margin: 0;
			}
			.desc-box {
				
			}		
		}		
	</style>
	<h2>{$trans->get('Preview short URL')}</h2>
	<div class="halves">
		<div class="half-width thumb-box">
			<img class="short-thumb" src="$thumb">
		</div>
		<div class="half-width desc-box">
			<div>
				{$trans->get('You requested a shortened URL')} <strong>$base/$keyword</strong>
				<p>{$trans->get('This URL points to')}:</p>		
			</div>
			<div>
				{$trans->get('Long URL')}: <strong><a href="$base/$keyword">$url</a></strong>
			</div>
			<div>
				{$trans->get('Title')}: <strong>$title</strong>
			</div>
			<div>
				{$trans->get('QR code')}:
				<div>
					<img class="short-qr" src="$qrcode">
				</div>
			</div>
		</div>
	</div>
	<p>{$trans->get('If you still want to visit this URL, please go to')} <strong><a href="$base/$keyword">{$trans->get('this URL')}</a></strong>.</p>
	<hr>
	<div class="disclaimer">{$trans->get('You will be redirected to another page. We are not responsible for the content of this page.')}</div>
HTML;

	yourls_html_footer();
}
