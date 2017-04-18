# Preview URL with QR Code and Thumbnail image

Plugin for [YOURLS](http://yourls.org) `1.5+`.

Description
-----------
Add the character '~' to a short URL to display a preview page with QR code and Thumbnail image before redirection.

Requirements
-----------
The following plugins should already be installed and activated:
1. [YOURLS QRCode](https://github.com/seandrickson/YOURLS-QRCode-Plugin) or [Google Chart API QR Code Plugin](https://github.com/YOURLS/YOURLS/wiki/Plugin-%3D-QRCode-ShortURL)
2. [Thumbnail URL image](https://github.com/prog-it/yourls-thumbnail-url)

Installation
------------
1. In `/user/plugins`, create a new folder named `preview-url-with-qrcode-thumbnail`.
2. Drop these files in that directory.
4. Go to the Plugins administration page ( *eg* `http://sho.rt/admin/plugins.php` ) and activate the plugin.
5. Have fun!

Translating
------------
This plugin already translated to English and Russian. If you want to translate to your language, please, edit array *$trans* in class Trans.
To change the language of the text, you need to change the value of the variable *PROGIT_TRANSLATION_TYPE*. 1 - Custom translation or 2 - English.

License
-------
MIT License
