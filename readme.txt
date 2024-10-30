=== LH Restricted Post Status ===
Contributors: shawfactor
Donate link: https://lhero.org/plugins/lh-restricted-post-status/
Tags: admin, posts, pages, status, workflow, restrict, restricted
Requires at least: 3.0
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restrict access to posts, pages, and cpts by user role

== Description ==

This plugin creates an additional post status 'restricted'. This status functions similarly to setting a post, page, or cpt to private. However with this plugin you can specify which roles have access to restricted posts

This is required because natively WordPress follows a publishing/editorial paradigm and therefore the 'read_private_posts' capability is only granted to admins and editors.


== Frequently Asked Questions ==

= How does this plugin work? =

It creates a custom post status 'restrict' and a custom capability 'read_restricted_posts'.

== Installation ==

1. Upload the entire `lh-restricted-post-status` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Navigate to Settings->Restricted Post Status and set the roles that can access restricted posts.


== Changelog ==

**1.00 January 20, 2017**
* Initial release

**1.01 January 23, 2017**
* Better documentation

**1.02 July 29, 2017**
* Added class check

**1.03 December 17, 2017**
* used wp_status library