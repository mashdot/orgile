<?php // Time-stamp: <2011-11-27 Sun 16:21 orgile.php>

/*___________
  O R G I L E

  Orgile: an Emacs Org-mode file parser and publishing tool.
  Used with classOrgile which is the very rough Org-Mode (http://orgmode.org/)
  file to HTML parser.

  Copyright (c) 2011, 'Mash (Thomas Herbert) <letters@toshine.org>
  All rights reserved.

  Version 20110918
  For the latest version please see: http://toshine.org

  NOTE: If you would like to help me develop this tool properly rather then this
  amateur garden-shed effort; please do contact me on the above address.

  _____________________
  T O S H I N E . O R G

  http://toshine.org is built with the very same tool, but simply with added CSS
  and various other content functions. Orgile really is quite flexible and easy
  to customise.

  _____________
  L I C E N S E

  This file is part of Orgile.
  Orgile: an Emacs Org-mode file parser and publishing tool.
  
  Orgile is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Orgile is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Orgile.  If not, see <http://www.gnu.org/licenses/>.

  _____________________
  D E S C R I P T I O N

  I wrote Orgile and classOrgile so that I could focus on writing articles and
  keeping my documents in their original Org-mode format. Orgile simply reads the files
  from the directory, and via classOrgile outputs the content as HTML. The Org-mode
  headers are used to create the article published date, title, author and article url.
  Orgile can output the full article, the description or a link list of titles.

  _________
  U S A G E
  
  Orgile will read a rewritten url and translate it into the actual file path.

  Since I use lighttpd I use the following rules:

  url.rewrite-once = (
  "^/([-a-zA-Z0-9]+)/$" => "/index.php?section=$1",
  "^/([-a-zA-Z0-9/]+)/([-a-zA-Z0-9/]+)$" => "/index.php?section=$1&article=$2"
  )

  I.e. http://orgile.toshine.org/articles/example-file/ = /var/www/orgile.toshine.org/www/articles/example-file.org

  By default Orgile is configured to use an .org file with the following headers.
  If you remove or add to this you will need to amend: "$body = preg_replace('/^(.*\n){7}/', '', $fileData);"

  _____________________

  #+Time-stamp: <2011-09-18 Sun 17:54 example-file.org>
  #+TITLE: This is the title of the article and the link name.
  #+AUTHOR: Your name.
  #+DATE: <2010-01-01 Mon 00:00>
  #+DESCRIPTION: This is the description of the article which is also used as the abstract and meta description.

  * This is the first orgile line it is NOT displayed but needed.
  ** This line IS seen and displayed as <H2>
  This content would be seen as the first <p> paragraph.

  _____________________
*/

// --------------------------------------------------[ DEFINITIONS ]--------------------------------------------------
// Various definitions used across Orgile. Please do read through this file to see what else you can change.
define('SITEURL','orgile.toshine.org'); // Site URL. No http/https or trailing slash!
define('SITETITLE','Orgile: an Emacs Org-Mode file to HTML parser and publishing tool.'); // Site title.
define('SITESUBTITLE','Orgile: an Emacs Org-Mode file to HTML parser and publishing tool.'); // Site subtitle.
define('SITEAUTHOR','&#039;Mash (Thomas Herbert)'); // You.
define('EMAIL', 'gro.enihsot@srettel'); // Main contact email (reversed) and CSS turns it back.
define('LICENSE','Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.'); // License description.
define('LICENSEURL','http://creativecommons.org/licenses/by-nc-sa/3.0/'); // License URL.

// --------------------------------------------------[ HTML5 META HEADER ]--------------------------------------------------
// HTML5 header used for all pages.
// Variables pulled from .org file headers.
function htmlHeader($date, $author, $description, $title, $nav) {
  $htmlHeader = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="alternate" type="application/atom+xml" title="'.SITETITLE.'" href="/feed/" />
    <link rel="license" title="'.LICENSE.'" href="'.LICENSEURL.'" />
    <title>'.$title.'</title>
    <meta name="generator" content="Orgile (http://toshine.org)">
    <meta name="date" content="'.$date.'" />
    <meta name="author" content="'.$author.'" />
    <meta name="description" content="'.$description.'" />
    <link rel="stylesheet" href="/site/css/screen.css" />
   </head>
  <body id="'.$nav.'">
  <a name="top"></a>
  <div id="wrapper">';
  return $htmlHeader;
}

// --------------------------------------------------[ ATOM XML FEED HEADER ]--------------------------------------------------
// ATOM XML feed header which can even be used as Google sitemap.
// I.e. http://orgile.toshine.org/feed/
function xmlHeader($title, $subtitle, $xmlLink, $guid, $date) {
  $xmlHeader = '<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title type="html">'.$title.'</title>
<subtitle type="html">'.$subtitle.'</subtitle>
<updated>'.$date.'</updated>
<link href="'.$xmlLink.'" rel="self" type="application/atom+xml" />
<link href="http://'.SITEURL.'" rel="alternate" type="text/html" />
<author>
<name>'.SITEAUTHOR.'</name>
<email>'.EMAIL.'</email>
<uri>http://'.SITEURL.'</uri>
</author>
<rights>'.LICENSE.'</rights>
<id>urn:uuid:'.$guid.'</id>';
  return $xmlHeader;
}

// --------------------------------------------------[ PAGE HEADER ]--------------------------------------------------
// Opening page DIV tag and top navigation. Continuing HTML5 markup.
// See CSS for menu current page highlighting. Used with "<body id="'.$nav.'">".
function pageHeader() {
  $pageHeader = '<div id="header">
<header>
<div id="headerLine"><a href="http://'.SITEURL.'" title="'.SITETITLE.'">'.SITETITLE.'</a></div>
<nav>
<div id="headerMenu">
<ul id="headerMenuList">
<li class="articles"><a href="/articles/" title="Articles">articles</a></li>
</ul>
</div>
</nav>
</header>
</div>';
  print $pageHeader;
}

// --------------------------------------------------[ PAGE FOOTER ]--------------------------------------------------
// Page footer content with HTML5 markup and closing HTML tags.
function pageFooter() {
  $pageFooter = '
<div id="footer">
<footer>
<ul id="footerList">
<li><strong><span class="red">&#039;</span>to</strong> life is doxology.</li>
<li><a href="http://orgile.toshine.org/" title="Orgile: an Emacs Org-Mode file parser and publishing tool." target="_blank">Orgile made.</a></li>
<li><a href="http://orgmode.org/" title="Org-Mode - Your Life in Plain Text" target="_blank">Org-Mode.</a></li>
<li><a href="http://www.gnu.org/software/emacs/" title="GNU Emacs is an extensible, customizable text editor and more." target="_blank">GNU Emacs.</a></li>
<li><a href="http://www.debian.org" title="Debian GNU/Linux" target="_blank">Debian GNU/Linux.</a></li>
<li><a href="http://www.linode.com/?r=cf21c6b4ea70d36fc9439efa23cd82d18a455e57" title="Linode.com Hosted" target="_blank">Linode.com hosted.</a></li>
</ul>
</footer>
</div>';
  print $pageFooter . '</div><!--[ wrapper ]--></body></html>'; // Close HTML tags.
}

// -------------------------[ SIDE CONTENT ]-------------------------
// Various content used in the sidebar below the side navigation.
function sideContent() {
  $sideContent = '
<div class="content">
<ul>
<li><h3>Orgile?</h3><p>Orgile is an Emacs Org-mode file parser and publishing tool written in PHP. It uses classOrgile.php which is the rough Org-Mode file to HTML parser. Orgile actually validates as HTML5 and the feed as a valid Atom 1.0 feed.</p></li>
</ul>
</div>';
  return $sideContent;
}

// --------------------------------------------------[ SECTION PAGE ]--------------------------------------------------
// This builds the layout of the landing pages for the various sections.
// I.e. http://orgile.toshine.org/articles/
// You could create a whole "switch()" for each section, i.e. "feed"
// but by default a general layout is defined and an "if" statement to
// control meta info depeding on section.
function sectionPage($section) {
  switch(dropDash($section)) {

    // -----[ DEFAULT ]-----
    // If nothing is defined below.
  default:
    $date = date('c');
    $author = SITEAUTHOR;
    $description = $section;

    // -----[ DEFINE SECTION LANDING META INFO ]-----
    // This allows for each landing page for the section to carry HTML meta data. Good for SEO of course.
    if($section == 'articles') {
      $title = 'Some articles.';
      $description = 'Some articles.';
    }

    // Create HTML header.
    $htmlHeader = htmlHeader($date, $author, $description, $description, dropDash($section));

    // Starts the object buffer.
    ob_start();

    pageHeader();
    print '<div id="columnX">';
    fetchSome($section,'abstract','0','sort'); // See funtion below.
    print '</div>';
    print '<div id="columnY">';
    print '<aside>';
    print '<div class="content">';
    print '<h2><a href="/'.spaceDash($section).'/" title="'.spaceDash($section).'">'.spaceDash($section).'</a>:</h2>';
    print '<ul class="side">';
    fetchSome($section,'list','0','sort'); // See function below.
    print '</ul><br>';
    print '</div>'.sideContent();
    print '</aside>';
    print '</div>';
    pageFooter();

    // End the object buffer.
    $content = ob_get_contents();
    ob_end_clean();

    $content = $htmlHeader . $content;
    print $content;
    break;

    // -----[ FEED ]-----
    // Layout define for the "/feed/".
    // Do enclose case 'example' or PHP will interpret them as constants.
  case 'feed':

    $title = SITETITLE;
    $subtitle = SITESUBTITLE;
    $date = date('c');
    $xmlLink = 'http://'.SITEURL.'/feed/';
    $guid = 'a5dddb5c-83af-40d8-9d8a-406134db9ba7'; // Create your own http://www.guidgenerator.com/

    // Create XML header.
    $xmlHeader = xmlHeader($title, $subtitle, $xmlLink, $guid, $date);

    // Starts the object buffer.
    ob_start();

    fetchSome('articles','feed','100','sort');
    print '</feed>';

    // End the object buffer.
    $content = ob_get_contents();
    ob_end_clean();

    $content = $xmlHeader . $content;
    print $content;
    break;

  } // End: "switch()".
} // End: "sectionPage()".

// --------------------------------------------------[ ARTICLE PAGE ]--------------------------------------------------
// This builds the layout of the article called.
// I.e. http://orgile.toshine.org/articles/this-is-an-example-article/
function articlePage($section, $filePath) {

  // -----[ CACHE LITE ]-----
  // Cache Lite is optional but recommended as it rolls and stores the page as HTML
  // and avoids having to rebuild the page everytime it is called. You will need to clear
  // the cache if you update the page. You could create a seperate "clearcache.php" page.
  // See: "/site/orgile/clearcache.php".

  require_once('Cache/Lite/Output.php');
  $options = array('cacheDir' => '/srv/www/'.SITEURL.'/www/site/ramcache/','lifeTime' => '604800'); // Define cache directory and cache lifetime (168 hours).
  $cache = new Cache_Lite_Output($options);

  // Begin cache lite.
  if (!$cache->start($filePath)) {

    if (is_file($filePath)) {
      $fileData = file_get_contents($filePath, NULL, NULL, 0, 1000); // This reads the first 1000 chars for speed.

      // Pulls details from .org file header.
      $regex = '/^#\+\w*:(.*)/m';
      preg_match_all($regex,$fileData,$matches);
      $title = trim($matches[1][0]);
      $author = trim($matches[1][1]);
      $date = trim($matches[1][2]);
      $date = date('c', cleanDate($date));
      $description = trim($matches[1][3]);
      $description = strip_tags($description);

      // Create HTML header.
      $htmlHeader = htmlHeader($date, $author, $description, $title, dropDash($section));

      // Starts the object buffer.
      ob_start();

      pageHeader();
      print '<div id="columnX">';
      fetchOne($filePath,'orgile');
      print '</div>';
      print '<div id="columnY">';
      print '<aside>';
      print '<div class="content">';
      print '<h2><a href="/'.spaceDash($section).'/" title="'.spaceDash($section).'">'.spaceDash($section).'</a>:</h2>';
      print '<ul class="side">';
      fetchSome($section,'list','0','sort'); // See function below.
      print '</ul><br>';
      print '</div>'.sideContent();
      print '</aside>';
      print '</div>';
      pageFooter();

      // End the object buffer.
      $content = ob_get_contents();
      ob_end_clean();

      $content =  $htmlHeader . $content;
    } // End: is_file($filePath).
    print $content;

    // End cache.
    $cache->end();

  } // End: cache lite.
} // End: "articlePage()".

// --------------------------------------------------[ CLASS ORGILE FUNCTION ]--------------------------------------------------
// .org file to HTML parser.
function orgile($content) {
  require_once('classOrgile.php');
  $orgile = new orgile();
  return $orgile->orgileThis($content);
}

// --------------------------------------------------[ MISC FUNCTIONS ]--------------------------------------------------
// Used to strip "dash" from directories used by "sectionPage()" "switch()" cases.
function dropDash($string) {
  $string = str_replace('-','', $string);
  return $string;
}

// Used to Replace "dash" with space from directories used by "sectionPage()".
function spaceDash($string) {
  $string = str_replace('-',' ', $string);
  return $string;
}

// Used to cleanup org-mode dates into strtotime
function cleanDate($date) {
  $dirty = array('<','>','[',']');
  $clean = array('','','','');
  $date = str_replace($dirty, $clean, $date);
  $date = strtotime($date);
  return $date;
}

// Redirect function for HTTP status codes.
function statusCodeRedirect($code) {
  switch($code){
  case 404:
    exit(header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found" ));
    break;
  }
}

// --------------------------------------------------[ FILE FETCHING FUNCTIONS ]--------------------------------------------------
// -------------------------[ FETCH ONE ]-------------------------
// Fetches a single file and prints it's content in HTML5 markup. Also adds citation info.
function fetchOne($filePath, $output) {

  if (is_file($filePath)) {
    $fileData = file_get_contents($filePath);

    // Pulls details from .org file header.
    $regex = '/^#\+\w*:(.*)/m';
    preg_match_all($regex,$fileData,$matches);
    $title = trim($matches[1][0]);
    $author = trim($matches[1][1]);
    $date = trim($matches[1][2]);
    $dateTime = date('c', cleanDate($date));
    $dateNice = date('F d, Y', cleanDate($date));
    $description = trim($matches[1][3]);

    $body = preg_replace('/^(.*\n){7}/', '', $fileData); // Removes the header from file. If you add or remove lines you need to change the number.

    $orgURL = 'http://'.SITEURL.'/'.$filePath;
    $orgLink = '<a href="'.$orgURL.'" title="'.$title.'&nbsp;.org file.">'.$orgURL.'</a>';
    $articleURL = 'http://'.SITEURL.'/'.substr($filePath, 0, -4).'/';
    $permalink = '<a rel="bookmark" href="'.$articleURL.'" title="'.$title.'">'.$title.'</a>';

    if ($output == 'orgile') { $body = orgile($body); }

    $content = '<article class="hentry"><header><h3 class="entry-unrelated">'.$dateNice.'</h3><h1 class="entry-title">'.$title.'</h1></header><div class="entry-content">'.$body.'<br><hr></div><aside><h3>'.$title.'</h3><p class="small"><strong>published:&nbsp;</strong>'.$dateNice.'<br><strong>permalink:&nbsp;</strong>'.$permalink.'<br><strong>citing:&nbsp;</strong><span class="byline author vcard"><a class="email fn" href="mailto:'.EMAIL.'">'.$author.'</a></span>.&nbsp;<i>'.$title.'</i>&nbsp;<time class="updated" datetime="'.$dateTime.'" pubdate>'.$dateNice.'</time>.&nbsp;'.$articleURL.'.<br><span class="source-org vcard copyright"><strong>copyright:&nbsp;</strong>Unless otherwise indicated, this article is licensed under a <a rel="license" href="'.LICENSEURL.'" title="Unless otherwise indicated, this article is licensed under a '.LICENSE.'">'.LICENSE.'</a> (cc) 2012 <a class="url org fn" href="http://'.SITEURL.'">'.SITETITLE.'</a></p><a class="gototop" href="#top">return to top</a></p></aside></article>';

    print $content;
  }
} // End: "fetchOne()".

// -------------------------[ FETCH SOME ]-------------------------
// Fetches multiple files.
function fetchSome($dir, $mode, $limit, $sort) {

  $fileArray = array();
  if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
      if($file != "." && $file != ".." && !is_dir($dir.'/'.$file)) {

        $fileData = file_get_contents($dir.'/'.$file, NULL, NULL, 0, 1000); // This reads the first 1000 chars for speed.

        // Pulls details from .org file header.
        $regex = '/^#\+\w*:(.*)/m';
        preg_match_all($regex,$fileData,$matches);
        $date = trim($matches[1][2]);
        $timestamp = cleanDate($date);
        $file = $timestamp.'-'.$file;

        $fileArray[] = $file;
      }
    }

    if ($sort == 'sort') { rsort($fileArray, SORT_NUMERIC); }
    if ($sort == 'shuffle') { shuffle($fileArray); }

    if ($limit > '0') { $fileArray = array_slice($fileArray, 0, $limit); }

    foreach($fileArray as $file) {

      $file = ltrim($file, substr($file, 0, 11));

      // -----[ LIST ]-----
      // Fetches list of articles.
      if($mode == 'list') {

        $filePath = $dir.'/'.$file;

        $urlPath = '/'.$dir.'/'.substr($file, 0, -4).'/';

        if (is_file($filePath)) {
          $fileData = file_get_contents($filePath, NULL, NULL, 0, 1000); // This reads the first 1000 chars for speed.

          // Pulls details from .org file header.
          $regex = '/^#\+\w*:(.*)/m';
          preg_match_all($regex,$fileData,$matches);
          $title = trim($matches[1][0]);

          $link = '<a href="'.$urlPath.'" title="'.$title.'">'.$title.'</a>';

          $content = '<li>'.$link.'</li>';
        }
        print $content;
      }
      // -----[ ABSTRACT ]-----
      // Fetches abstracts of articles.
      if($mode == 'abstract') {

        $filePath = $dir.'/'.$file;

        $urlPath = '/'.$dir.'/'.substr($file, 0, -4).'/';

        if (is_file($filePath)) {
          $fileData = file_get_contents($filePath, NULL, NULL, 0, 1000); // This reads the first 1000 chars for speed.

          // Pulls details from .org file header.
          $regex = '/^#\+\w*:(.*)/m';
          preg_match_all($regex,$fileData,$matches);
          $title = trim($matches[1][0]);
          $author = trim($matches[1][1]);
          $date = trim($matches[1][2]);
          $dateTime = date('c', cleanDate($date));
          $dateNice = date('F d, Y', cleanDate($date));
          $description = trim($matches[1][3]);

          $link = '<a rel="bookmark" href="'.$urlPath.'" title="'.$title.'">'.$title.'</a>';

          $content = '<div class="content"><article><header><h3>'.$dateNice.'</h3><h1>'.$link.'</h1></header><p>'.$description.'</p></article></div>';
        }
        print $content;
      }

      // -----[ FEED ]-----
      // Fetches articles to produce XML feed.
      if($mode == 'feed') {

        $filePath = $dir.'/'.$file;

        $urlPath = '/'.$dir.'/'.substr($file, 0, -4).'/';

        if (is_file($filePath)) {
          $fileData = file_get_contents($filePath, NULL, NULL, 0, 1000); // This reads the first 1000 chars for speed.

          // Pulls details from .org file header.
          $regex = '/^#\+\w*:(.*)/m';
          preg_match_all($regex,$fileData,$matches);
          $title = trim($matches[1][0]);
          $author = trim($matches[1][1]);
          $date = trim($matches[1][2]);
          $date = date('c', cleanDate($date));
          $description = trim($matches[1][3]);

          $absoluteLink = 'http://'.SITEURL.$urlPath.'';

          $tagDate = date('Y-m-d', strtotime($date));
          $tag = 'tag:'.SITEURL.','.$tagDate.':'.$urlPath;

          $content = '<entry><title>'.$title.'</title><link href="'.$absoluteLink.'" /><id>'.$tag.'</id><updated>'.$date.'</updated><published>'.$date.'</published><summary>'.$description.'</summary></entry>';
        }
        print $content;
      }
    }
    closedir($handle);

  } // End: "if($handle...)".
} // End: "fetchSome()".

// --------------------------------------------------[ PAGE HANDLER ]--------------------------------------------------
// Main function used in "index.php" to drive Orgile.
function pageHandler() {

  if(isset($_GET['section'])) { $section = $_GET['section']; }
  if(isset($_GET['article'])) { $article = $_GET['article']; }

  if(!isset($section) && !isset($article)) { sectionPage('articles'); }

  // -----[ SECTION ]-----
  if(isset($section) && !isset($article)) {

    if(!is_dir($section)) { statusCodeRedirect('404'); }

    sectionPage($section);
  }

  // -----[ SECTION + ARTICLE ]-----
  if(isset($section) && isset($article)) {

    $filePath = $section .'/'. rtrim($article, '/').'.org';

    if(!is_file($filePath)) { statusCodeRedirect('404'); }

    articlePage($section, $filePath);
  }
} // End: pageHandler().
  // NOTE: there must be no trailing whitespace after closing or you will recieve a "headers already sent" error!
?>
