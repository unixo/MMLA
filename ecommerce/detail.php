<?php
/**
 * Scheda dettaglio prodotto
 *
 * Dato l'identificativo del prodotto, questa pagina mostra il dettaglio dello
 * stesso: verra' quindi visualizzato il titolo, la descrizione, il prezzo,
 * i tempi di consegna e l'immagine del prodotto stesso.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: detail.php 25 2009-09-08 10:35:00Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Il parametro 'pid' (product ID) e' necessario per il funzionamento.
 */
if (isset($_GET['pid']) === false) {
    redirectToURL('products.php');
}

/**
 * Estraggo il valore della variabile 'pid', ne valido il contenuto ed il 
 * tipo.
 */
$pid = htmlentities($_GET['pid']);
if (!ctype_digit($pid)) {
    throw new PEAR_Exception('Invalid product ID', ERR_PARAM);
}

/**
 * Leggo dal database il record equivalente al prodotto specificato.
 * Qualora il prodotto non venga trovato (pid inesistente), effettuo un 
 * redirect alla pagina d'errore, segnalando l'avvenuto.
 */
$result = executeQuery('SELECT * FROM products WHERE pid=?', array($pid));
if (count($result) == 0) {
    $msg = 'Nonexistent product';
    redirectToURL('error.php?msg='.$msg);
}

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('detail', $_SESSION['lang']);

/**
 * Controllo se, per il prodotto selezionato, sono state inserite delle
 * recensioni.
 */
$reviews = executeQuery('SELECT * FROM reviews WHERE pid=?', $pid);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page                = new FV_Page($strings['page_title'], array('detail.css'), 
                                   array('zoom.js'));
$page->printRightBox = false;
$page->printHead();
$page->printMenu();
?>

<table border="0" width="700" class="central_box">
<tr class="prd_title"><td colspan="2">
    <?php echo stripslashes($result[0]['name']); ?></td>
    <td class="right_box" width="150" align="center"><span class="item_price">
    &euro;&nbsp;<?php echo currency($result[0]['price']); ?></span><br/>
    <span class="shiptime">
    <?php echo $strings['ship_time'] ."&nbsp;". $result[0]["ship_time"].
               "&nbsp;".$strings['hours']; 
    ?>
    </span></td></tr>

<tr class="detail_row">
    <td width="600">
       <?php echo nl2br(html_entity_decode($result[0]['descr'])); ?></td>
    <td align="center"><img width="130" height="130" alt="product image" 
                            border="0" ondblclick="enlargeImage()" 
                            onclick="dropImage()" id="prdimage" 
                            src="<?php echo CONTENT_URL.'/uploads/'. 
                                 $result[0]['image'].'"/>'; ?><br/>
    <img alt="glass" width="15" 
         src="<?php echo CONTENT_URL; ?>/images/glass.gif" />
    <span id="glassmsg">Double-click to enlarge</span></td>
    <td class="right_box" width="150" align="center">
<?php
if ($result[0]['availability'] > 0) {
    echo '<a href="add.php?pid='.$result[0]['pid'].'">'.
         '<img border="0" alt="Add" src="'.THEME_IMAGES.
         '/add-to-cart.png"/></a>';
} else {
    echo '<span style="font-weight: bold; color: red; font-size: 20px;">'.
         $strings['not_available'].'<span>';
}
?>
         </td></tr>

<tr class="detail_row"><td colspan="2">&nbsp;</td>
                      <td class="right_box"></td></tr>

<tr class="detail_row"><td colspan="2"><span class="rating">
     <?php echo $strings['cust_rating']; ?></span>&nbsp;</td>
     <td class="right_box"></td></tr>

<tr class="detail_row"><td colspan="2">
     <?php printRating($result[0]["rating"]); ?>&nbsp;
     <span class="reviews_count">(<?php echo $strings['based_on'].'&nbsp;'.
       count($reviews). '&nbsp;'.$strings['reviews']; ?>)</span></td>
     <td class="right_box"></td></tr>

<tr class="detail_row"><td colspan="2">
<?php 
if (count($reviews) > 0) {
    echo '<a class="review_link" href="read_reviews.php?pid='.
         $result[0]['pid'].'">'.$strings['read_reviews'].'</a>&nbsp;|&nbsp;';
} 
?>
    <a href="write_review.php?pid=<?php echo $result[0]['pid']; ?>">
    <?php echo $strings['write_review']; ?></a></td>
    <td class="right_box"></td></tr>

<tr><td colspan="3" align="center" style="padding-top: 60px; padding-bottom: 10px">
    <table border="0" cellspacing="10">
<?php
$similar = executeQuery('SELECT * FROM products WHERE cid = ? '.
                        'AND pid != ?  ORDER BY pid DESC LIMIT 4', 
                        array($result[0]['cid'], $result[0]['pid']));
echo '<tr><td align="center" colspan="'.count($similar).
     '"><span class="similiar">'.
     $strings['similiar'].'</span></td></tr><tr>';

foreach ($similar as $item) {
    echo '<td align="center"><div class="image" style="width: 130px; '.
         'border: 1px solid gray"><a href="'.CONTENT_URL.'/detail.php?pid='.
         $item['pid'].'"><img width="110" border="0" src="'.CONTENT_URL.
         '/uploads/'. $item['image'].
         '" alt="similiar prd img"/></a></div></td>';
}
echo '</tr><tr>';
foreach ($similar as $item) {
    echo '<td align="center"><a href="'.CONTENT_URL.'/detail.php?pid='.
         $item['pid'].'">'.$item['name'].'</a></td>';
}
echo '</tr></table>';
?>
</td></tr>
</table>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
