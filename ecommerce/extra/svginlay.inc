<?php

/* 
    svglib.inc
    Copyright(c), 2006, Jeff Schiller
    http://blog.codedread.com/

    Location
    ========
    The official location of this library is: http://www.codedread.com/lib/svginlay.php
    and is dependent upon http://www.codedread.com/lib/svglib.js.
    Please check back regularly for updates.

    Description
    ===========  
    This is a PHP Library that contains a function to help you inlay external 
    SVG documents with fallback content into your HTML files.  Under normal circumstances, 
    the HTML:object tag should be enough, however the Adobe SVG Viewer seems to have a 
    problem with HTML:object and prefers the non-standard <embed> tag (which doesn't allow
    fallback content).  Furthermore, the Eolas v. Microsoft case forces the user to click
    on each embedded item to use it.  
    
    This PHP function generates conditional comments and JavaScript to work around this.
    
    You include this file in your HEAD section and use it like so:
    
    <?php include('lib/svginlay.inc'); ?>
    <head>
      <script type="text/javascript" src="lib/svglib.js"></script>
    </head>
    <body>

      <?php inlaySVG('mysvg', 'somefile.svg', 500, 375, '<p>Error, browser must support "SVG"</p>');
    
    This PHP function call produces the following HTML code for your web page written out using document.write():
    
    <!--[if IE]>
        <script type='text/javascript'>
            inlaySvg('mysvg', 'somefile.svg', 500, 375, '<p>Error, browser must support "SVG"</p>');
        </script>
        <noscript>
            <p>Error, browser must support "SVG"</p>
        </noscript>
    <![endif]-->
    <!--[if !IE]><!-->
        <object id="mysvg" type="image/svg+xml" data="somefile.svg" width="500" height="375">
            <p>Error, browser must support "SVG"</p>
        </object>
    <!--<![endif]-->


    IMPORTANT NOTE:  Your fallback content must not include any single quotes, only double-quotes should be used.
    
    Tested Configurations
    =====================
    + tested and works in IE6 + ASV (3 and 6)
    + tested and works in Firefox 1.5 (native SVG)
    + tested and works in Opera 9 TP1
    
    History
    =======
    Version  Date         Notes
    ----------------------------------------------------------------------------
      1.0    2006-01-13   Initial version.    
      1.1    2007-04-17   Changed IE conditional comments such that they are valid in XHTML
      1.2    2007-04-17   Changed from PHP to JavaScript implementation to work around Eolas patent issue
      
    Thanks To
    =========    
    - Rob Russell for expanding upon this idea (via his WordPress plugin) and providing the cool name "inlay"

    Disclaimer and License
    ======================
    This script is free to use and/or modify, but please provide credit and (where applicable) 
    a link to http://www.codedread.com/ would be nice.

    Contact
    =======
    Email comments/suggestions/bugs to jeff at codedread dot com.    

*/

// This is the PHP Function
function inlaySVG($id, $svgFilename, $width, $height, $fallbackContentAsXmlString) {
    // prints out the final result to the document
    printf("<!--[if IE]>\n");
    printf("  <script type='text/javascript'>\n");
    printf("    inlaySVG('%s', '%s', %s, %s, '%s');\n", $id, $svgFilename, $width, $height, $fallbackContentAsXmlString);
    printf("  </script>\n");
    printf("  <noscript>\n");
    printf("    %s\n", $fallbackContentAsXmlString);
    printf("  </noscript>\n");
    printf("<![endif]-->\n");
    
    printf("<!--[if !IE]><!-->\n");
    printf("  <object id='%s' type='image/svg+xml' data='%s' width='%s' height='%s'>\n", $id, $svgFilename, $width, $height);
    printf("    %s\n", $fallbackContentAsXmlString);
    printf("  </object>\n");
    printf("<!--<![endif]-->\n");
}

?>