<?php
/**
 * Pagina principale [blue]
 *
 * Homepage del sito, tema "blue"; vengono visualizzato gli ultimi sei prodotti
 * caricati dall'amministratore
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: index.php 21 2009-09-08 10:29:03Z unixo $
 * @link     http://commerce.devzero.it
 */
?>
<table border="0" class="central_box" align="center">
<tr align="center"><td colspan="2">
<?php
$products = executeQuery('SELECT * FROM all_products ORDER BY pid DESC LIMIT 6');
foreach ($products as $item) {
    echo '<div class="prd_block">';
    echo '<div class="catname">'. $item['cat_name'] . '</div>';
    echo '<div class="prdname">'. stripslashes($item['name']) . '</div>';
    echo '<div class="image"><a href="'.CONTENT_URL.'/detail.php?pid='.
         $item['pid'].'"><img src="'.CONTENT_URL.'/uploads/'.$item['prd_image'].
         '" border="0" width="130" height="130" alt="prd image"/></a></div>';
    $descr = substr(strip_tags(html_entity_decode($item['descr'])), 0, 180);
    echo '<div class="descr">'. stripslashes($descr).'&hellip;</div>';
    echo '<div class="price"><span class="euro">&euro;</span>&nbsp;'. 
         currency($item['price']) .'</div>';
    echo '</div>';
}
?>  
</td></tr></table>