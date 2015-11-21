<?php
/**
 * A CNavBar class to generate a navigation bar.
 *
 */
 class CNavBar
 {

   public $menu;

   function __construct()
   {
     ;
   }
/**
 * GenerateMenu
 *
 * @param array $menu with menu content, example:
 * array(
 *  'home'  => array('text'=>'Hem',  'url'=>'home.php'),
 *  'about' => array('text'=>'Om', 'url'=>'about.php'),
 *  )
 *
 */
public static function GenerateMenu($menu, $class="") {
     // Get URI with query string removed to see what page we are on.
    // If on same page as menu item, mark it with class=selected.
    $pos = strpos($_SERVER['REQUEST_URI'], "?");
    if (false != $pos) {
        // Remove query string, i.e. all characters efter first ?
        $currentPage = Basename(substr($_SERVER['REQUEST_URI'], 0, $pos));
    } else {
        // URI do not contain a query string
        $currentPage = basename($_SERVER['REQUEST_URI']);
    }

    // Return menu item with link. Set style if on current page
     $html = "<nav class=\"$class\">\n";
     foreach($menu as $item) {
       $selected = ($currentPage == $item['url']) ? "selected" : "";
       $html .= "<a href='{$item['url']}' class='$selected'>{$item['text']}</a>\n";
     }
     $html .= "</nav>\n";
     return $html;
   }

 }
