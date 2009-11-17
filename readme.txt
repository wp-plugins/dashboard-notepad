=== Dashboard Notepad ===
Contributors: sillybean
Tags: widget, dashboard, notes
Requires at least: 2.8
Tested up to: 2.9
Stable tag: 1.22

The very simplest of notepads for your Dashboard. 

== Description ==

This dashboard widget provides a simple notepad. The widget settings allow you to choose which roles can edit the notes, and which roles can merely read them.

New in 1.2: You can now display the contents of your notepad using a template tag and/or shortcode. The widget permissions apply to these tags as well: only users with permission to read the notes will see the notes on the front end.

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` 
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to your Dashboard and configure the widget by clicking the link in its upper right corner.
1. To display your notes in a theme file, use the `<?php dashboard_notes(); ?>` template tag.
1. To display your notes in a post, page, or text widget, use the [dashboard_notes] shortcode. (To use it in a widget, you'll have to enable shortcode parsing in text widgets, if you haven't already. Add `add_filter('widget_text', 'do_shortcode');` to your functions.php file.)

== Screenshots ==

1. The notepad
1. The widget options

== Changelog ==

= 1.22 =
* Fixed bug where the dashboard widget disappeared when unregistered users were allowed to read the notes. (November 17, 2009)
= 1.21 =
* Added option to allow the public (unregistered users) read the notes. (November 16, 2009)
= 1.2 =
* New template tag and shortcode to display notes publicly.
* Security fix, as a result of the new tags: now checking whether users can post unfiltered HTML in the notes.
* Added translation support.
* Fixed CSS bug that threw off column widths. (November 14, 2009)
= 1.1 =
* Fixed bug in the role configuration.
* The widget now disappears entirely for users who aren't allowed to read its contents. (August 24, 2009)
= 1.0 =
* First release (August 5, 2009)