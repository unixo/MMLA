<?php
/**
 * Funzione checkout del carrello (pagamento)
 *
 * Tramite questa pagina viene simulato il pagamento della merce presente nel
 * carrello: l'operazione prevede inoltre di ridurre la disponibilita' del 
 * prodotto nonche' di inserire un record nella tabella delle transazioni per
 * ogni prodotto acquistato.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: checkout.php 24 2009-09-08 10:33:59Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Il carrello puo' essere usato solo da utenti registrati e autenticati; nel
 * caso di un visitatore occasionale viene effettuato un redirect alla pagina
 * di login.
 */ 
if (isset($_SESSION['user'])) {
    $user =& $_SESSION['user'];
    if ($user->isDummy()) {
        redirectToURL('login.php');
    }
}

/**
 * L'operazione di checkout non e' fattibile qualora il carrello risulti vuoto:
 * in questo caso effettuo un redirect al riassunto del carrello.
 */
if ($_SESSION['user']->basket->total() === 0) {
    redirectToURL('summary.php');
}

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('checkout', $_SESSION['lang']);

$err = false;
if (isset($_POST['submit'])) {
    if (!strlen($_POST['cc'])) {
        $err     = true;
        $err_msg = $strings['cc_error'];
    } else {
        foreach ($_SESSION['user']->basket->items() as $item) {
            executeQuery('UPDATE products SET availability=availability-? '.
                          'WHERE pid=?', array($item['count'], $item['pid']));
            executeQuery('INSERT INTO transactions VALUES (0, now(), ?, ?, ?, ?)',
                        array($_SESSION['user']->uid, $item['pid'], 
                              $item['count'], $item['count']*$item['price']));
            $logger->log('User #'. $_SESSION['user']->uid .' bought #'. 
                         $item['count'] .' of '. $item['pid'], PEAR_LOG_INFO);
        }
        $_SESSION['user']->basket->emptyBasket();
        redirectToURL('index.php');
    }
}

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array('checkout.css'));
$page->printHead();
$page->printMenu();

$order_total = $_SESSION['user']->basket->totalVAT() + 
               $_SESSION['user']->basket->total();
?>

<table width="100%" class="central_box">
<tr><td colspan="2"><h1 class="page_title">
                    <?php echo $strings['page_title']; ?>
                    </h1></td></tr>
<tr><td>Items in your basket</td>
    <td align="right"><?php echo $_SESSION['user']->basket->itemsCount(); ?>
    </td></tr>
<tr><td>Sub-total</td>
    <td align="right"><?php echo currency($_SESSION['user']->basket->total()); ?>
    </td></tr>
<tr><td>V.A.T. (<?php echo FV_Basket::VAT; ?>)</td>
    <td align="right"><?php echo currency($_SESSION['user']->basket->totalVAT()); ?>
    </td></tr>
<tr><td>Order total</td>
    <td align="right"><?php echo currency($order_total); ?></td></tr>
<tr><td colspan="2" align="center"><form id="checkoutform" action="checkout.php" 
                                         method="post">
            <label><?php echo $strings['cc']; ?><br/>
            <input class="input" type="text" alt="credit card" name="cc" id="cc"
                   size="16"></label><br/>
            <input type="submit" name="submit" class="mysubmit" value="Checkout">
        </form></td></tr>
</table>

<script type="text/javascript">
try {
    document.getElementById('cc').focus();
} catch (e) {}
</script>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>