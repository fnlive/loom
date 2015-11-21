<?php
/**
 * Render content to theme.
 *
 */
 // dump($loom['mainnavbar']);

// Extract the data array to variables for easier access in the template files.
extract($loom);

// Include the template functions.
include(__DIR__ . '/functions.php');

// Include the template file.
include(__DIR__ . '/index.tpl.php');
