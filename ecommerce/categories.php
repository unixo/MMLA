<?php
/**
 * Visualizzazione categorie prodotti
 *
 * Questa pagina visualizza l'elenco delle categorie dei prodotti disponibili:
 * per ognuna di esse, produrra' un opportuno link in modo tale che risulti
 * possibili visualizzare tutti i prodotti appartenenti alla categoria
 * selezionata.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: categories.php 24 2009-09-08 10:33:59Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'Structures/DataGrid.php';
require_once 'HTML/Table.php';
require_once dirname(__FILE__).'/extra/svginlay.inc';

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('category', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['category_shop'], array('category.css'));
$page->addJS('svglib.js');
$page->printHead();
$page->printMenu();

$categories = executeQuery('SELECT * FROM categories ORDER BY name');

echo '<div>';
foreach ($categories as $item) {
    $products = executeQuery('SELECT * FROM products WHERE cid=?', $item['cid']);
    
    echo '<div class="cat_block">';
    echo '<div class="catname"><center><a href="'.CONTENT_URL.
         '/products.php?cid='.$item['cid'].'">'. $item['name'] .
         '</a></center></div>';
    echo '<div class="catsvg"><center>';
    inlaySVG('svg_'.str_replace(' ', '_', $item['name']), 
             CONTENT_URL.'/uploads/'.$item['image'], 120, 120, 
             '<p>Error, browser must support "SVG"</p>');
    echo '</center></div>';
    echo '<div class="catcount"><center>'. count($products) . 
         ' items present</center></div>';
    echo '</div>';
}
echo '</div>';

/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
