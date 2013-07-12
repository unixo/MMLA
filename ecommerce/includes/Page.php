<?php
/**
 * Classe FV_Page
 *
 * PHP Version 5
 *
 * @category Class
 * @package  UrbinoCommerce
 * @filesource
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: Page.php 33 2009-09-09 08:47:02Z unixo $
 * @link     http://commerce.dezero.it/
 */
require_once dirname(__FILE__).'/../extra/svginlay.inc';

/**
 * Classe FV_Page
 *
 * L'oggetto FV_Page serve per creare l'infrastruttura della pagina in modo
 * tale che venga condiviso il layout tra tutte le pagine del sito.
 *
 * Esempio d'utilizzo della classe
 * <code>
 * require_once 'Page.php';
 *
 * $page = new FV_Page('page title', array('file1.css', 'file2.css'));
 * $page->addJS('anotherfile.js');
 * $page->printHead();
 * $page->printMenu();
 * ...
 * $page->printFooter();
 * </code>
 *
 * @category Class
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link     http://commerce.dezero.it/
 * @uses     executeQuery API per leggere le informazioni sui prodotti
 * @uses     getConfigValue API per leggere le impostazioni del sito
 */
class FV_Page
{
    private $_title;
    private $_css;
    private $_js;
    public  $printLeftBox;
    public  $printRightBox;
    public  $printReviewBox;
    public  $head_extra;

    /**
     * Costruttore di default della classe FV_Page.
     *
     * @param string $title Titolo della pagina
     * @param array  $css   Vettore di CSS da includere
     * @param array  $js    Vettore di JavaScript da includere
     */
    function __construct($title, $css = null, $js = null)
    {
        $this->head_extra = '';
        $this->_css       = array("main.css", 'autosuggest_inquisitor.css', 
                                  'default.css|default', 'dummy.css|dummy_css');

        if (isset($css)) {
            foreach ($css as $item) {
                array_push($this->_css, $item);
            }
        }
        $this->_js = array('marquee.js', 'bsn.AutoSuggest_2.1.3.js', 
                           'svglib.js', 'applesearch.js');
        
        if (isset($js)) {
            foreach ($js as $item) {
                array_push($this->_js, $item);
            }
        }
        $this->_title         = $title;
        $this->printRightBox  = true;
        $this->printLeftBox   = true;
        $this->printReviewBox = true;

        /**
         * Attivo il buffering dell'output per tutte le pagine. L'output della
         * pagina, costruito progressivamente, viene registrato in un buffer 
         * interno e inviato interamente quando viene invocato ob_end_flush();
         */
        @ob_start();
    }
    
    /**
     * Aggiunge un CSS alla pagina: questo metodo va invocato prima che venga
     * stampato l'header della pagina.
     *
     * @param string $file File CSS da includere
     *
     * @return void
     */
    function addCSS($file)
    {
        array_push($this->_css, $file);
    }

    /**
     * Aggiunge un JS alla pagina: questo metodo va invocato prima che venga
     * stampato l'header della pagina.
     *
     * @param string $file File JS da includere
     *
     * @return void
     */
    function addJS($file)
    {
        array_push($this->_js, $file);
    }

    /**
     * Imposta il titolo della pagina, sovrascrivendo il valore assegnato dal
     * costruttore della classe.
     *
     * @param string $str Titolo della pagina
     *
     * @return void
     */
    function setTitle($str)
    {
        $this->_title = $str;
    }

    /**
     * Stampa la prima componente dell'output di una pagina, composto dalla
     * intestazione del documento e dalla sezione HEAD; questo metodo include
     * inoltre l'elenco dei CSS e JS associati alla pagina.
     *
     * @return void
     */
    function printHead()
    {
        if (file_exists(THEME_PATH.'header.php')) {
            include_once THEME_PATH.'header.php';
        } else {
            $url = CONTENT_URL;
            echo <<< EOB
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>$this->_title</title>
    <link rel="shortcut icon" href="$url/images/favicon.ico" />
    <link rel="icon" type="image/gif" href="$url/images/animated_favicon1.gif"/>
EOB;
            echo "\n";
            foreach ($this->_css as $c) {
                /**
                 * Ogni CSS puo' anche presentarsi nella forma "file|ID": la
                 * funzione, in questo caso, esegue uno split usando la pipe
                 * come separatore e identifica il nome del file CSS e l'ID.
                 */
                $css_info = explode('|', $c);
                if (count($css_info) > 1) {
                    $css_name = $css_info[0];
                    $css_id   = $css_info[1];
                } else {
                    $css_name = $c;
                    $css_id   = strtr($c, '.', '_');
                }
                /**
                 * Controllo se il file di stile viene definito all'interno
                 * del tema corrente o se, viceversa, e' presente a livello
                 * globale.
                 */
                if (file_exists(THEME_PATH.'/css/'.$css_name)) {
                    $reldir = CONTENT_URL .'/themes/' . THEME;
                } else {                    
                    $reldir = CONTENT_URL;
                }
                echo "\t" . '<link href="'. $reldir . '/css/'. $css_name .
                     '" rel="stylesheet" type="text/css" id="'. $css_id.'"/>'. 
                     "\n";
            }
            /**
             * Procedo all'inclusione dei file javascript, se presenti.
             */
            foreach ($this->_js as $j) {
                echo "\t".'<script language="javascript" type="text/javascript" '.
                     'src="'. CONTENT_URL .'/js/' . $j .'"></script>'. "\n";
            }
            if (strlen($this->head_extra) > 0) {
                echo $this->head_extra;
            }
            /**
             * Visualizzo il feed RSS se l'equivalente impostazione e' attiva
             */
            if (getConfigValue('rss') === 'on') {
                echo "\t".'<link rel="alternate" type="application/rss+xml" href="'.
                     CONTENT_URL.'/rss.php" title="Our last products"/>';
            }
            /**
             * Inizializzo il campo di ricerca sulla base del browser
             */
            $cssPath= CONTENT_URL.'/css/';
            echo <<<EOB
                <script type="text/javascript">
                //<![CDATA[
                    window.onload = function () { applesearch.init('$cssPath'); }
                //]]>
                </script>
EOB;
            echo "</head>\n";       
        }
    }
    
    /**
     * Stampa il menu di destra, il footer di riepilogo e chiude i tag BODY e
     * HTML.
     *
     * @return void
     */
    function printFooter()
    {
        global $tr;

        if (file_exists(THEME_PATH.'footer.php')) {
            include_once THEME_PATH.'footer.php';
        } else {
            echo '</td>';
            if ($this->printRightBox) {
                echo '<td class="leftside" width="288" valign="top">';
                echo '<h2 style="margin-top: 10px;">'.
                     $tr->get('last_news', 'index').'</h2><ul>';
                $records = executeQuery('SELECT * FROM products '.
                                        'ORDER BY pid DESC LIMIT 10');
                foreach ($records as $row) {
                     echo '<li><a href="detail.php?pid='. $row["pid"] .'">' . 
                           $row["name"] .'</a></li>' . "\n";
                }
                echo '</ul>';
                if ($this->printReviewBox) {
                    echo '<h2 style="margin-top: 20px;">Last users\' reviews</h2>';
                    echo '<div id="marqueecontainer"' .
                         ' onmouseover="copyspeed=pausespeed" '.
                         ' onmouseout="copyspeed=marqueespeed">'.
                         '<div id="vmarquee" style="position: absolute; '.
                         'width: 98%;">';
                    $reviews  = executeQuery('SELECT products.name AS pname, '.
                                            'reviews.* FROM products,reviews '.
                                            'WHERE products.pid=reviews.pid '.
                                            'ORDER BY reviews.rid DESC LIMIT 30');
                    $last_pid = 0;
                    foreach ($reviews as $record) {
                        if ($record['pid'] !== $last_pid) {
                            echo '<hr/><h4><a href="detail.php?pid='.
                                 $record['pid'] .'">' .$record['pname'] .
                                 '</a></h4>';
                        } else {
                            echo '<br/>';
                        }
                        echo '<strong>'. stripslashes($record['title']) .
                             '</strong><br/>';
                        $str = html_entity_decode($record['value']);
                        echo stripslashes(substr($str, 0, 80)).
                             '&hellip;<br/>';
                        echo '<span style="padding-left: 1px">'.
                             printRating($record['rating'], false, 13) .
                             '</span><br/>';
                        $last_pid = $record['pid'];
                    }
                    echo '</div></div>';
                }
                echo '<p style="text-align: center; margin-top: 50px">'.
                     '<a href="http://validator.w3.org/check?uri=referer">'.
                     '<img border="0" src="'. THEME_IMAGES .'valid-xhtml10.png" '.
                     'alt="Valid XHTML 1.0 Transitional" height="31" width="88"/>'.
                     '</a></p>'. '</td></tr>'. "\n";
            } else {
                echo '<td></td></tr>';
            }
            echo '<tr class="footer"><td colspan="3" align="center">';
            echo '<a href="index.php">Home</a> | <a href="products.php">'.
                 $tr->get('products', 'index') .'</a> | '.
                 '<a href="categories.php">'.$tr->get('categories', 'index').
                 '</a> | <a href="searchform.php">'.$tr->get('search', 'index').
                 '</a> | <a href="summary.php">'.
                 $tr->get('shopping_cart', 'index').'</a> | '.
                 '<a href="privacy.php">'.$tr->get('privacy', 'index').
                 '</a><br/>Copyright ©2009 - Ferruccio Vitale per MMLA</td></tr>';
            echo <<< EOB
</tbody>
</table>

<script type="text/javascript">
        var options_xml = {
        script: function (input) { return "search.php?hint="+input; },
        varname:"input",
        callback: function (obj) { 
                       document.getElementById('prodid').value = obj.id; }
    };
    var as_xml = new bsn.AutoSuggest('what', options_xml);
</script>

</body>
</html>
EOB;
        }
        @ob_end_flush();
    }
     
    /**
     * Barra di navigazione
     *
     * Stampa la struttura portante della pagina: questa e' costituita dal 
     * menu superiore, comprensivo del logo SVG e dal blocco di menu a sinistra.
     *
     * @return void
     */ 
    function printMenu()
    {
        global $tr;

        if (file_exists(THEME_PATH.'structure.php')) {
            include_once THEME_PATH.'structure.php';
        } else {
            if (isset($_SESSION['user'])) {
                $user =& $_SESSION['user'];
            } else {
                $user = null;
            }
            
            echo <<<EOB
<body>

<table class="main_table" border="0" style="padding: 10px" cellspacing="0">
    <tbody>
        <tr><td colspan="3">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr><td rowspan="2" valign="middle" width="60%" class="logo">
EOB;
            inlaySVG('svg_logo', CONTENT_URL.'/images/site_logo.svg', 170, 87, 
                '<p>Error, browser must support "SVG"</p>');
            echo <<< EOB
                             <span class="site_title">/dev/zero commerce
                             </span></td>
                    <td align="right" class="search_title">
EOB;
            echo '<strong>'.$tr->get('search_store', 'index').'</strong></td>';
            echo <<<EOB
                    <td align="left" width="230"><div id="applesearch">
                        <form class="searchform" 
                              action="do_search.php" method="post">
                        <input type="hidden" id="prodid" name="pid" value=""/>

                        <span class="sbox_l"></span>
                        <span class="sbox"><input type="text" id="what" 
                             onkeyup="applesearch.onChange('srch_fld','what')"
                             name="what" size="30" class="sbox_r" /></span>
                        <span class="sbox_r" id="srch_clear"></span>

                        </form></div></td>
                    </tr>
                <tr><td colspan="3" align="right">
                    <a href="lang.php?lang=it" title="Italiano">
EOB;
            echo '<img width="24" border="0" src="'.CONTENT_URL.
                 '/images/flag-it.jpeg" alt="Italiano"/></a>'.
                 '       <a href="lang.php?lang=en" title="English">'.
                 '       <img width="28" border="0" src="'.CONTENT_URL.
                 '/images/flag-uk.jpeg" alt="English"/></a>';
            echo <<<EOB
                        </td></tr>
                </table></td></tr>

               <tr class="menu"><td colspan="2">
               <ul>
EOB;
            echo '<li><a class="lmenu" href="index.php">Home</a></li>';
            echo '<li><a class="lmenu" href="searchform.php">'.
                 $tr->get('search', 'index').'</a></li>';
            echo '<li><a class="lmenu" href="categories.php">'.
                 $tr->get('categories', 'index').'</a></li>';
            echo '<li><a class="lmenu" href="top_rated.php">'.
                 $tr->get('top_rated', 'index').'</a></li>';
            echo '<li><a class="lmenu" href="aboutus.php">'.
                 $tr->get('aboutus', 'index').'</a></li>';

            if (getConfigValue(CONFIG_RSS) === 'on') {
                echo '<li><a class="lmenu" href="rss.php">RSS <img border="0"'.
                     ' src="'. CONTENT_URL.'/images/feed-rss.gif" alt="rss"/>'.
                     '</a></li>';
            }
            echo '</ul></td><td align="right"><ul><li>';
            if (($user == null) || $user->isDummy()) {
                echo '<a class="lmenu" href="login.php">'.
                     $tr->get('login', 'index');
            } else {
                echo '<a class="lmenu" href="logout.php">'.
                     $tr->get('logout', 'index');
            }
            echo '</a></li></ul></td></tr>';

            echo '<tr>';

            /**
             * Calcolo la larghezza della pagina, valutando se sono visibili
             * i box laterali
             */
            $w    = 600;
            $cols = 1;
            if (!$this->printLeftBox) {
                $w += 180;
                $cols++;
            } else {
                $this->printLeftMenu($user);
            }
            if (!$this->printRightBox) {
                $w += 180;
            }
            
            $str = 'width="'.$w.'"';
            if ($cols > 1) {
                $str .= ' colspan="'.$cols.'"';
            }
            echo '<td '. $str .' valign="top" style="padding: 0px 17px;">';
        }
    }

    /**
     * Box di sinistra (menu)
     *
     * Stampa il menu di sinistra contenente un blocco di collegamenti.
     *
     * @param mixed $user Istanza della variabile FV_User
     *
     * @return void
     */
    function printLeftMenu($user)
    {
        global $tr;
   
        echo '<td valign="top" class="leftside">';
        echo '<h2 style="margin-top: 10px;">'.
             $tr->get('quick_shop', 'index').'</h2><ul>';
        echo '<li><a href="products.php">'.
             $tr->get('browse_prd', 'index').'</a></li>';
        echo '<li><a href="categories.php">'.
             $tr->get('category_shop', 'index').'</a></li>';
        echo '<li><a href="top_rated.php">'.
             $tr->get('top_rated', 'index').'</a></li></ul>';
        echo '<h2 style="margin-top: 20px;">'.
             $tr->get('user_corner', 'index').'</h2>'."\n<ul>\n";
        if ($user != null) {
            /**
             * Stampo il link per gli AdminTool solo se l'utente e' un
             * amministratore
             */
            if ($user->isAdmin()) {
                echo '<li><strong><a href="admin.php">'.
                     $tr->get('admin_tool', 'index').
                     '</a></strong></li>';
            }
            echo '<li><a href="summary.php">'.
                 $tr->get('cart_summary', 'index').'&nbsp('.
                 $user->basket->itemsCount().')</a></li>';
            if ($user->basket->itemsCount()>0) {
                echo '<li><a href="empty.php">'.
                     $tr->get('empty_cart', 'index').'</a></li>';
                echo '<li><a href="checkout.php">'.
                     $tr->get('checkout', 'index').'</a></li>';
            }
            /**
             * Se l'utente non e' un visitatore occasionale ma regolarmente
             * autenticato, gli permetto di modificare il proprio profilo e di
             * effettuare il logout.
             */
            if (!$user->isDummy()) {
                echo '<li><a href="edit.php">'.
                     $tr->get('edit_profile', 'index').'</a></li>';
                echo '<li><a href="logout.php">'.
                     $tr->get('logout', 'index').'</a></li>';
            } else {
                echo '<li><a href="login.php">'.$tr->get('login', 'index').
                     '</a></li>' . "\n".
                     '<li><a href="register.php">'.
                     $tr->get('register', 'index').'</a></li>';
            }
        } else {
            echo '<li><a href="login.php">'.$tr->get('login', 'index').
                 '</a></li>' . "\n".
                 '<li><a href="register.php">'.
                 $tr->get('register', 'index').'</a></li>';
        }
        echo '</ul>';
        if ($user != null) {
            $items_count = $user->basket->itemsCount();;
            $total       = $user->basket->total();

            echo '<h2 style="margin-top: 20px;">'.
                 $tr->get('shop_art', 'index');
            echo <<<EOB
                 </h2><br/>
            <table align="center" bgcolor="#FFFFFF" width="130" 
                   class="mincart">
                <tbody>
                    <tr><td bgcolor="#F0F0F0" align="center">
EOB;
            echo '<img border="0" alt="cart" src="'.CONTENT_URL.
                 '/images/img_cart.gif">&nbsp;';
            echo '<strong>'.$tr->get('shopping_cart', 'index').
                 '</strong></td></tr>'.
                 '<tr><td bgcolor="#F0F0F0" align="center">'.
                 $items_count.'&nbsp;'.$tr->get('prd_in_cart', 'index').
                 '</td></tr><tr><td bgcolor="#F0F0F0" align="center">'.
                 $tr->get('total', 'index'). "&nbsp;&euro;&nbsp;".
                 currency($total). '</td></tr></tbody></table>';
        }
        echo "\t</td>";
    }

}

?>