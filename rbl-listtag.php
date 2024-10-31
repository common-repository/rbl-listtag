<?php
/*
Plugin Name: Rbl-listtag 
Plugin URI: http://www.berthou.com/us/?p=25
Description: Display of the last comments or post filtered by categories or tag ( with this you can display a html table which contains there data). Affichage des derniers articles modifiés d'un tag ou d'une categorie et les derniers commentaires (cela me permet d'afficher dans une page un tableau contenant la liste de ces articles).
Author: Raymond BERTHOU
Version: 1.10 
Author URI: http://www.berthou.com/

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

Warning all post are sorted by date_modified desc

Copyright 2007  Raymond BERTHOU  (email : rberthou@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$rbl_tr_id = 1 ;

function rbl_callback($content) 
{
	$rbl_pos = strpos($content,'%%rbl-') ;
	$rbl_cat = '..' ;
	
	$new_content = $content ;
	
	while ( $rbl_pos!== false) 
	{
		$rbl_pos2 = strpos($new_content,'%', $rbl_pos + 2) ;
		
		if ( $rbl_pos2 !== false) 
		{
			$rbl_cat  = substr($new_content, $rbl_pos + 2, $rbl_pos2 - $rbl_pos - 2) ;
			$rbl_val = explode("-", $rbl_cat) ;

		        if ($rbl_val[1] == 'com') {
				$rbl = rbl_com($rbl_val[2], $rbl_val[4], $rbl_val[3] +0 ) ;
		        } else {
				$rbl = rbl_tag($rbl_val[1], $rbl_val[2], $rbl_val[4], $rbl_val[3] +0 ) ;
		        }
			$new_content = ereg_replace("%%" . $rbl_cat . "%%", $rbl, $new_content);
		}
		$rbl_pos = strpos($new_content,'%%rbl-') ;
	}
	return $new_content ;
}

function rbl_tag($tag_cat, $tag_id, $class_id, $number = 4) 
{
	$_str = '' ;
	$_str .= '<table class="'.$class_id.'"><tbody>';
	// $_str .= '<table class="'.$class_id.'"><thead class="rh"><tr><th colspan="3">'.$tag_id.'</th></tr></thead><tbody>';
	$_str .= rbl_tag_posts($tag_cat, $tag_id, $number);
	$_str .= '</tbody></table>';
	
	return $_str ;
}

function rbl_tag_posts($tag_cat, $tag, $num = 4) 
{
	global $wpdb ;

		
	if ( $tag_cat == 'tag') {
		$tag = str_replace(",", "','", $tag) ;
		
		$_strq = "SELECT a.ID, a.post_date, a.post_title, a.post_excerpt, a.post_modified, a.comment_count 
		            FROM $wpdb->posts a ,  $wpdb->terms b, $wpdb->term_taxonomy c, $wpdb->term_relationships d 
		           WHERE a.post_type in ('post', 'page') 
		             and a.post_status = 'publish' 
		             and b.name in ( '". $tag ."') 
		             and b.term_id = c.term_id 
		             and c.taxonomy = 'post_tag' 
		             and c.term_taxonomy_id = d.term_taxonomy_id 
		             and a.ID = d.object_id 
		             ORDER BY a.post_date DESC 
		             LIMIT $num" ;
		             
	} else {
		if ($tag_cat == 'cat') {
			$tag = str_replace(",", "','", $tag) ;
			
			$_strq = "SELECT a.ID, a.post_date, a.post_title, a.post_excerpt, a.post_modified, a.comment_count 
			            FROM $wpdb->posts a ,  $wpdb->terms b, $wpdb->term_taxonomy c, $wpdb->term_relationships d 
			           WHERE a.post_type in ('post', 'page') 
			             and a.post_status = 'publish' 
			             and b.name in ( '". $tag ."') 
			             and b.term_id = c.term_id 
			             and c.taxonomy = 'category' 
			             and c.term_taxonomy_id = d.term_taxonomy_id 
			             and a.ID = d.object_id 
			             ORDER BY a.post_date DESC 
			             LIMIT $num" ;
			             
		} else {
			$_strq = "SELECT a.ID, a.post_date, a.post_title, a.post_excerpt, a.post_modified, a.comment_count 
			            FROM $wpdb->posts a 
			           WHERE a.post_type in ('post', 'page') 
			             and a.post_status = 'publish' 
			             ORDER BY a.post_date DESC 
			             LIMIT $num" ;
		}
	}
	$rbl_catposts = $wpdb->get_results($_strq);
	
	$_str = '' ;
	foreach($rbl_catposts as $post) 
	{
		if ($rbl_tr_id == 1) $rbl_tr_id = 2 ; else $rbl_tr_id = 1 ;
		$_str .= '<tr class="r'. $rbl_tr_id .'"><td><a href="'.get_permalink($post).'">'.$post->post_title.'</a> : '.$post->post_excerpt .'</td><td class="tdr">'.$post->comment_count.'</td><td class="tdc">'. mysql2date('d M Y',$post->post_modified_gmt) .'</td></tr>';
	}

	return $_str ;
}

function rbl_com($tit, $class_id, $number = 4) 
{
	$_str = '' ;
	$_str .= '<table class="'.$class_id.'"><tbody>';
	// $_str .= '<table class="'.$class_id.'"><thead class="rh"><tr><th colspan="3">'.$tit.'</th></tr></thead><tbody>';
	$_str .= rbl_comments($number);
	$_str .= '</tbody></table>';
	
	return $_str ;
}

function rbl_comments($num = 4) 
{
	global $wpdb ;
	$rbl_comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID, comment_date_gmt, comment_content FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT $num");

	foreach($rbl_comments as $post) 
	{
		if ($rbl_tr_id == 1) $rbl_tr_id = 2 ; else $rbl_tr_id = 1 ;
		
		$_str .= '<tr class="r'. $rbl_tr_id .'"><td><a href="'. get_permalink($post->comment_post_ID) . '#comment-' . $post->comment_ID . '">' . get_the_title($post->comment_post_ID) . '</a> : '.substr($post->comment_content,0,70).'...</td><td class="tdc">'.$post->comment_author.'</td><td class="tdc">'. mysql2date('d M Y',$post->comment_date_gmt) .'</td></tr>';
		
	}

	return $_str ;
}

add_filter('the_content','rbl_callback', 7);

?>