=== JSM's Show Post Metadata ===
Plugin Name: JSM's Show Post Metadata
Plugin Slug: jsm-show-post-meta
Text Domain: jsm-show-post-meta
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://jsmoriss.github.io/jsm-show-post-meta/assets/
Tags: custom fields, meta, post meta, post types, delete, debug, inspector
Contributors: jsmoriss
Requires PHP: 7.2
Requires At Least: 5.2
Tested Up To: 6.0.2
Stable Tag: 3.0.5

Show post metadata (aka custom fields) in a metabox when editing posts / pages - a great tool for debugging issues with post metadata.

== Description ==

**The JSM's Show Post Metadata plugin displays post (ie. posts, pages, and custom post types) meta keys (aka custom field names) and their unserialized values in a metabox at the bottom of post editing pages.**

The current user must have the [WordPress *manage_options* capability](https://wordpress.org/support/article/roles-and-capabilities/#manage_options) (allows access to administration options) to view the Post Metadata metabox, and the *manage_options* capability to delete individual meta keys.

The default *manage_options* capability can be modified using the 'jsmspm_show_metabox_capability' and 'jsmspm_delete_meta_capability' filters (see filters.txt in the plugin folder).

There are no plugin settings - simply install and activate the plugin.

= Related Plugins =

* [JSM's Show Comment Metadata](https://wordpress.org/plugins/jsm-show-comment-meta/)
* [JSM's Show Term Metadata](https://wordpress.org/plugins/jsm-show-term-meta/)
* [JSM's Show User Metadata](https://wordpress.org/plugins/jsm-show-user-meta/)
* [JSM's Show Registered Shortcodes](https://wordpress.org/plugins/jsm-show-registered-shortcodes/)

== Installation ==

== Frequently Asked Questions ==

== Screenshots ==

01. The "Post Metadata" metabox added to admin post editing pages.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes and/or incompatible API changes (ie. breaking changes).
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Repositories</h3>

* [GitHub](https://jsmoriss.github.io/jsm-show-post-meta/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/jsm-show-post-meta/)

<h3>Changelog / Release Notes</h3>

**Version 3.0.5 (2022/05/26)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Update for SucomUtil library class.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.

**Version 3.0.4 (2022/05/09)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed missing `SucomCountryCodes` class for country related methods in `SucomUtil`.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.

**Version 3.0.3 (2022/03/18)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the sucom class library files.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.

**Version 3.0.2 (2021/12/10)**

* **New Features**
	* None.
* **Improvements**
	* Added a `trim()` to the returned CSS id after successful delete, in case the ajax return is corrupted with a space or newline.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.

**Version 3.0.1 (2021/12/09)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed a missing table column if there is no metadata and allowed to delete meta is true.
* **Developer Notes**
	* Updated `SucomUtilMetabox::get_table_metadata()` to add a missing empty delete column if `$row_count` is 0.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.

**Version 3.0.0 (2021/11/30)**

* **New Features**
	* Added the ability to delete individual post meta.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the `js/com/jquery-admin-page.js` library.
	* Updated the `JsmSpmScript->get_admin_page_script_data()` method to add an '_ajax_actions' array.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.

**Version 2.0.0 (2021/11/26)**

* **New Features**
	* When a post / page is saved in the WordPress block editor, the Post Metadata metabox is now refreshed.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Complete rewrite of the plugin - all class, method, and filter names have changed.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.

== Upgrade Notice ==

= 3.0.5 =

(2022/05/26) Update for SucomUtil library class.

= 3.0.4 =

(2022/05/09) Fixed missing `SucomCountryCodes` class for country related methods in `SucomUtil`.

= 3.0.3 =

(2022/03/18) Updated the sucom class library files.

= 3.0.2 =

(2021/12/10) Added a `trim()` to the returned CSS id after successful delete, in case the ajax return is corrupted with a space or newline.

= 3.0.1 =

(2021/12/09) Fixed a missing table column if there is no metadata and allowed to delete meta is true.

= 3.0.0 =

(2021/11/30) Added the ability to delete individual post meta.

= 2.0.0 =

(2021/11/26) When a post / page is saved in the WordPress block editor, the Post Metadata metabox is now refreshed.

