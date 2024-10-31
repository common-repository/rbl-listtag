=== Rbl-listtag === 
Plugin Name: Rbl-listtag
Plugin URI: http://www.berthou.com/us/?p=25
Author : rberthou
Author URI: http://www.berthou.com/us/
Tags: plugin, page, menu, categories
Requires at least: 2.0
Tested up to: 2.3
Version: 1.0.0

Display of the last comments or post filtered by categories or tag .

== Description ==

Display of the last comments or post filtered by categories or tag ( with this you can display a html table which contains there data).

Affichage des derniers articles modifiés d'un tag ou d'une categorie et les derniers commentaires (cela me permet d'afficher dans une page un tableau contenant la liste de ces articles).

== Installation ==

1. Upload tliste-navi.zip to your Wordpress plugins directory, usually `wp-content/plugins/` and unzip the file.  It will create a `wp-content/plugins/rbl-listtag/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.


== Frequently Asked Questions ==


== Screenshots ==


== Usage ==

Add there code "%%rbl-........" in your post (page or news)

Exemple 
--> filter tag "applet" ; show 6 new max  ; class tbl2
 %%rbl-tag-applet-6-tbl2%%

--> filter tag "java or websphere" ; show 6 new max  ; class tbl1
 %%rbl-tag-java,websphere-6-tbl1%%

--> filter comment ; show 5 comments max  ; class tbl1
 %%rbl-com-Commentaires-5-tbl1%%

--> filter categories db2  ; show 6 comments max  ; class tbl2
 %%rbl-cat-db2-6-tbl2%%

--> filter categories db2 or mysql ; show 6 comments max  ; class tbl2
 %%rbl-cat-db2,mysql-6-tbl2%%

--> filter last post ; show 6 comments max  ; class tbl2
 %%rbl-last-zz-6-tbl2%%


== Release Notes ==

**1.0.0** : First internal release; 
