<?php
/**
 * Theme related functions.
 *
 */

/**
 * Get title for the webpage by concatenating page specific title with site-wide title.
 *
 * @param string $title for this page.
 * @return string/null for webpage title.
 */
function get_title($title) {
  global $loom;
  return $title . (isset($loom['title_append']) ? $loom['title_append'] : null);
}

/**
 * Get header for the webpage.
 *
 * @param void.
 * @return html string for the webpage header.
 */
function generate_header() {
  global $loom;
  $navBar = CNavBar::Get($loom['mainnavbar']);
  return <<<EOD
  <img class='sitelogo' src='img/RM-icon-1-x130.png' alt='RM Logo'/>
  <span class='sitetitle'>Rental Movies</span>
  <span class='siteslogan'>Filmer som du vill se</span>
  $navBar
EOD;
}

/**
 * Get footer for the webpage.
 *
 * @param void.
 * @return html string for the webpage footer.
 */
function generate_footer() {
  $liveReload = ($_SERVER['SERVER_NAME']=='localhost') ? '<script src="http://localhost:35729/livereload.js"></script>' : '';
  return <<<EOD
  <footer><span class='sitefooter'>Copyright (c) 2016 Rental Movies | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a> | <a href='http://validator.w3.org/check/referer'>Nu</a></span>
  </footer>
  $liveReload
EOD;
}
