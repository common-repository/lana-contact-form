=== Lana Contact Form ===
Contributors: lanacodes
Donate link: https://www.paypal.com/donate/?hosted_button_id=F34PNECNYHSA4
Tags: contact form, captcha, email message, bootstrap contact form
Requires at least: 4.0
Tested up to: 6.0
Stable tag: 1.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy to use contact form with captcha

== Description ==

Easy to use bootstrap based contact form with captcha.

The Lana Contact Form are developed primarily for Lana themes.

= Video =

[youtube https://www.youtube.com/watch?v=Jt62n4tq4R4]

= How to use with function: =

`<?php
if( function_exists( 'lana_contact_form' ) ) {
    echo lana_contact_form();
}
?>`

= Available shortcodes: =

`[lana_contact_form]`


= Customize view: =

Copy the `/wp-content/plugins/lana-contact-form/view/lana-contact-form.php` file to `/wp-content/themes/{your-theme}/lana-contact-form/lana-contact-form.php`

Change the file in the theme folder.


= Lana Codes =
[Lana Contact Form](https://lana.codes/product/lana-contact-form/)

== Installation ==

= Requires =
* WordPress at least 4.0
* PHP at least 5.3

= Instalation steps =

1. Upload the plugin files to the `/wp-content/plugins/lana-contact-form` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

= How to use it =
* in `Pages > Edit` selected page, add the `[lana_contact_form]` shortcode to the page content.

== Frequently Asked Questions ==

Do you have questions or issues with Lana Contact Form?
Use these support channels appropriately.

= Lana Codes =
[Support](https://lana.codes/contact/)

= WordPress Forum =
[Support Forum](http://wordpress.org/support/plugin/lana-contact-form)

== Screenshots ==

1. screenshot-1.jpg

== Changelog ==

= 1.4.0 =
* add wp_unslash function to post vars

= 1.3.0 =
* add get_field_value method
* reformat code

= 1.2.3 =
* change method of check widget for session

= 1.2.2 =
* bugfix captcha in widget

= 1.2.1 =
* bugfix include view

= 1.2.0 =
* add privacy policy checkbox to form
* add filters to wp send args

= 1.1.1 =
* bugfix email template

= 1.1.0 =
* add bootstrap version selector and views
* change register session function
* change form post handle function

= 1.0.9 =
* reformat code
* update readme.txt

= 1.0.8 =
* add include view function
* change captcha background transparent

= 1.0.7 =
* add text domain to plugin header

= 1.0.6 =
* Tested in WordPress 4.8 (compatible)
* Change website to lana.codes

= 1.0.5 =
* register session only in frontend

= 1.0.4 =
* change register session and form handle function action hook

= 1.0.3 =
* bugfix error reporting in get captcha

= 1.0.2 =
* bugfix session init

= 1.0.1 =
* Tested in WordPress 4.7 (compatible)
* No change

= 1.0.0 =
* Added Lana Contact Form

== Upgrade Notice ==

= 1.4.0 =
This version fixes form handle function slashes. Upgrade recommended.

= 1.3.0 =
This version added get_field_value method. Upgrade recommended.

= 1.2.3 =
This version fixed captcha in widget. Upgrade recommended.

= 1.2.2 =
This version fixed captcha in widget. Upgrade recommended.

= 1.2.1 =
This version fixed include view. Upgrade recommended.

= 1.2.0 =
This version added privacy policy checkbox. Upgrade recommended.

= 1.1.1 =
This version fixes email template. Upgrade recommended.

= 1.1.0 =
This version added bootstrap version selector and views and changed form handle function. Upgrade recommended.

= 1.0.9 =
This version changed code format.

= 1.0.8 =
This version added captcha transparent background and include view function. Upgrade recommended.

= 1.0.7 =
This version added text domain to the plugin header. Upgrade recommended.

= 1.0.6 =
Nothing has changed in this version. Tested in WordPress 4.8 and compatible.

= 1.0.5 =
This version fixes session bug. Upgrade recommended.

= 1.0.4 =
This version fixes session bug. Upgrade recommended.

= 1.0.3 =
This version fixes error reporting bug. Upgrade recommended.

= 1.0.2 =
This version fixes session init bug. Upgrade recommended.

= 1.0.1 =
Nothing has changed in this version. Tested in WordPress 4.7 and compatible.