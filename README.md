WordPress Setup Plugin
========================

Adding some basic functions and settings to WordPress for creating custom post types and taxonomies, clean up and sort admin menu items.


## Installation

1. Upload the `riesma-setup` folder to the `/wp-content/plugins/` directory
2. When needed: edit the functions in the plugin's php file to create more post types and edit the preferences
3. Activate the plugin through the 'Plugins' menu in WordPress


## Todo
1.   Create easier way to edit the settings instead of editing this php file?
  a. Via XML, or
  b. Admin pages
2.   Custom meta boxes
3.   Set default screen options.
4.   Add Custom Post Type archive pages to menu (still needed?)
     (http://wordpress.org/plugins/add-custom-post-types-archive-to-nav-menus/)
5.   Set menu order dynamically for custom post types
6.   Rename the URL slug: find better way to swap characters and character encoding!
7.   Add custom taxonomy class
8.   Flush rewrite rules on load plugin (but after adding post types)
x.   Add translation: _x( 'text', 'context' ) => 'Nieuw' vs 'Nieuwe'?


## More information
register_post_type   http://codex.wordpress.org/Function_Reference/register_post_type
register_taxonomy    http://codex.wordpress.org/Function_Reference/register_taxonomy
custom meta boxes    https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
custom post type     http://www.smashingmagazine.com/2012/11/08/complete-guide-custom-post-types/

