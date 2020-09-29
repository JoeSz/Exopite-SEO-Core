# Exopite-SEO-Core
## WordPress Plugin
Core SEO optimizations with cookie notice, inline CSS and more. Increase your website's SEO ranking, speed, the number of visitors and ultimately your sales by optimizing your WordPress site for SEO.<br>
Provide some extra but important SEO functions along Yoast SEO. Yoast SEO not required but recommended.
You may or may not use an other SEO plugin too.

- Author: Joe Szalai
- Version: 20200929
- Plugin URL: https://github.com/JoeSz/Exopite-SEO-Core
- Demo URL: https://joe.szalai.org/exopite/exopite-seo-core/
- Author URL: https://joe.szalai.org
- License: GNU General Public License v3 or later
- License URI: http://www.gnu.org/licenses/gpl-3.0.html

DESCRIPTION
-----------

In our analysis in XOVI, we find some possibilities to improve our sites SEO. Mainly we use Yoast SEO Plugin, but unfortunalty we did not
find this options in it. So we decided to create this plugin. Mainly to expand Yoast SEO functionality, but it not required,
the plugin can be used alone too.

All functions are optional.

* Remove JSON links from header,
* Deactivate attachment pages and redirect to attachment file,
* Limit revisions,
* Add noidex on archives, search and 404,
* Automatically set the WordPress image title, alt-text & description based on file name,
* Deactivate comments and pingbacks in the whole site,
* Deactivate feed,
* Add anything to header (e.g. Schema.org JSON, Google Analytics or GEO Tags),
* Add anything to footer (e.g. JavaScript, Google Analytics, etc...),
* Add inline style to header (it is hard to add css for some themes),
* Add personalizable cookie notice.

* Removed: Activate GZip, use .htaccess
* Removed: Activate Google Analytics (via Tag-Manager), you need a Tag-Manager ID for this first, use header and/or footer editor

INSTALLATION
------------

1. [x] Upload `exopite-seo-core` to the `/wp-content/plugins/exopite-seo-core/` directory

OR

1. [ ] ~~Install plugin from WordPress repository (not yet)~~

2. [x] Activate the plugin through the 'Plugins' menu in WordPress

REQUIREMENTS
------------

Server

* WordPress 4.7+ (May work with earlier versions too)
* PHP 5.6+ (Required)
* jQuery 1.9.1+

Browsers

* Modern Browsers
* Firefox, Chrome, Safari, Opera, IE 10+
* Tested on Firefox, Chrome, Edge, IE 11

CHANGELOG
---------

= 20200929 =
* ADDED: option to remove WordPress archives
* FIX: some typos

= 20200624 =
* FIX: add nofollow for internal links

= 20200529 =
* Reorganize code
* ADDED: nofollow, noopener, noreferrer to external links
* REMOVED: Google Analytics and gzip

= 20200514 =
* UPDATE: Separate Cookie CSS and JS and load them only if activated

= 20190711 =
* ADDED: possibility to append text to robots.txt

= 20190710 =
* FIX: Google Analytics add 'anonymizeIp' true
* UPDATE: Google Analytics with GTag and regular
* ADDED: Canonical URL

= 20190521 =
* UPDATE: Update Exopite Simple Options Framework
* FIX: Function create_function() is deprecated since PHP 7.2; Use an anonymous function instead

= 20190401 =
UPDATE: Exopite Simple Options Framework

= 20190206 =
* ADDED: Sanitize file name function
* UPDATED: Check PHP verzion
* REMOVED: Disable gzip checking (site is down?)

= 20181123 =
* UPDATE: Exopite Simple Options Framework
* ADDED: plugin upgrade notification

= 20180624 =
* CHANGED: Remove empty admin JavaScript from enqueue

= 20180622 =
* FIXED: Some typos

= 20180528 =
* UPDATED: Exopite Simple Options Framework
* FIXED: Cookie footer always full width

= 20180524 =
* ADDED: (ACE) Editor field for footer
* ADDED: (ACE) Editor field for inline css in header

= 20180517 =
* ADDED: Cookie Notice from translation file for WPML compatibility
* ADDED: German translation

= 20180328 =
* ADDED: Cookie Notice with color, padding, etc. customisation
* UPDATED: Exopite Simple Options Framework

= 20180322 =
* ADDED: check GZip compression
* ADDED: pot file for translations
* ADDED: cookie warning/note
* FIXED: various bugfix

= 20180315 =
* Initial release.

LICENSE DETAILS
---------------
The GPL license of Exopite SEO Core grants you the right to use, study, share (copy), modify and (re)distribute the software, as long as these license terms are retained.

DISCLAMER
---------

NO WARRANTY OF ANY KIND! USE THIS SOFTWARES AND INFORMATIONS AT YOUR OWN RISK!
[READ DISCLAMER.TXT!](https://joe.szalai.org/disclaimer/)
License: GNU General Public License v3

[![forthebadge](http://forthebadge.com/images/badges/built-by-developers.svg)](http://forthebadge.com) [![forthebadge](http://forthebadge.com/images/badges/for-you.svg)](http://forthebadge.com)
