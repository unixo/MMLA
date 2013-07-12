<?php
/**
 * Visualizzazione messaggio d'errore
 *
 * Questa funzionalita' non viene mai invocata direttamente, ma e' il sito
 * stesso a redirigere la navigazione in questo punto qualora si incorra in
 * qualche errore.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: error.php 26 2009-09-08 10:36:06Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

if (!isset($_GET['msg'])) {
    redirectToURL('index.php');
}

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page('An error occurred');
$page->printHead();
$page->printMenu();
?>

<table border="0" width="100%" class="central_box">
<tr><td><h1 class="page_title">Ops&hellip; An error occurred!</h1></td></tr>
<tr><td><span class="error">
    <?php echo htmlentities($_GET['msg']); ?>
    </span></td></tr>
</table>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
