<?php
/** views/pagestub.php
 * 
 * 
 * 
 */


// These stubs setup includes for the HTML document for various items like
// CSS, Local or external JS

// CSS Includes - assumes local to server
$css_stub = '<link rel="stylesheet" type="text/css" media="all" href="CSS_PATH" />';

// Javascript Includes - needs full pathing becuase it could be 
// local or remote
$js_stub = '<script type="text/javascript" src="JS_PATH"></script>';


?>
<!DOCTYPE html>
<html lang="en" ng-app="ccssmonitor">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
        <title><?php echo isset($page_title) ? $page_title : "No Page Title"; ?></title>

        <!--[if IE]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!--[if lte IE 7]>
            <script src="js/IE8.js" type="text/javascript"></script>
        <![endif]-->
        <!--[if lt IE 7]>
            <link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/>
        <![endif]-->        
        
        <?php
        // Import the CSS -----------------------------------------------------

        // Now use the requested CSS
        if (isset($css_includes)) {
            foreach ($css_includes as $css) {
                echo "\n\t".str_replace("CSS_PATH", $css, $css_stub);
            }
        }  
        
        // Import the JS ------------------------------------------------------
        if (isset($js_includes)) {
            foreach ($js_includes as $js) {
                echo "\n\t".str_replace("JS_PATH", $js, $js_stub);
            }
        }               
        ?>  
    </head>
    <body> 
        <?php 
            echo $body_zone; 
        ?>
    </body>
</html>

