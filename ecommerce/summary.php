<?php
/**
 * Pagina 'Cart summary'
 *
 * Il riepilogo del carrello mostra, per l'utente che ha gia' compiuto con
 * successo la fase di autenticazione, il dettaglio degli oggetti contenuti
 * nel carrello: per ognuno di questi verra' descritto il nome del prodotto,
 * la quantita' ed il prezzo unitario.
 * Al termine verra' visualizzato il totale dei prezzi, l'IVA ed il totale
 * dell'intero ordine.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: summary.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 * @see      FV_User, FV_Basket
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Verifico che esista, tra le variabili di sessione, l'oggetto 'user': in caso
 * contrario creo un utente fittizio, non ancora autenticato, perche' siano
 * disponibili tutte le funzionalita' del carrello.
 */
checkSessionUser();

/**
 * Un utente con privilegi di amministratore non ha motivo di usare il carrello
 */
if ($_SESSION['user']->isAdmin() === true) {
    redirectToURL('admin.php');
}

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('summary', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['cart_summary'], array('summary.css'));
$page->printHead();
$page->printMenu();
?>

<table border="0" width="100%" class="central_box">
<tr><td><h1 class="page_title"><?php echo $strings['cart_summary']; ?>
        </h1></td></tr>
<tr><td>
    <table border="0" width="600">
<?php
/**
 * Itero su ogni elemento contenuto nel carrello dell'utente: per
 * ognuno ne stampo il nome, la quantita' ed il prezzo unitario.
 */
foreach ($_SESSION['user']->basket->items() as $item) {
    echo '<tr height="50"><td style="font-size: 12px"><a href="detail.php?pid='.
         $item["pid"] .'">'. $item['name'] . '</a></td>';
    echo '<td align="right" style="font-size: 12px">';
    /**
     * Stampo la quantita' del prodotto solo se ne e' stato selezionato piu'
     * di uno dello stesso tipo.
     */
    if ($item['count'] > 1) {
        echo $item['count'] . '&nbsp;*';
    }
    echo '</td>';
    echo '<td align="right" width="120"><strong style="display: inline-block; '.
         'font-size: 13px; text-align: right;">'.currency($item['price']).
         '</strong></td></tr>';
    echo '<tr><td colspan="3" align="right"><p '.
         'class="remove_item"><a href="remove.php?pid='.
         $item['pid'] .'">remove</a></p></td></tr>';
}
?>
    <tr class="subtotal"><td colspan="3"></td></tr>
    <tr><td class="sx_price" colspan="2">
        <?php echo $strings['sub_total']; ?></td>
        <td class="price">
        <?php echo currency($_SESSION['user']->basket->total()); ?></td></tr>
    <tr><td class="sx_price" colspan="2"><?php echo $strings['vat']; ?> (
        <?php echo FV_Basket::VAT; ?>%)</td>
        <td class="price">
        <?php echo currency($_SESSION['user']->basket->totalVAT()); ?></td></tr>
    <tr class="subtotal"><td colspan="3"></td></tr>
        <tr><td class="sx_price" colspan="2">
            <?php echo $strings['order_total']; ?></td>
            <td class="price">
            <?php echo currency($_SESSION['user']->basket->total()+
                                $_SESSION['user']->basket->totalVAT()); ?></td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3" align="right"><a href="products.php">
        <img border="0" 
             src="<?php echo THEME_IMAGES; ?>continue-shopping.png"/>
        </a></td></tr>
    </table></td></tr>
</table>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
