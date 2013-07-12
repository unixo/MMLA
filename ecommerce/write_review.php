<?php
/**
 * Modulo per scrivere una recensione.
 *
 * Un utente che abbia gia' completato con successo il processo di autenticazione
 * puo' scrivere una recensione di un prodotto, fornendo un titolo, una breve
 * descrizione ed un 'rating' del prodotto stesso.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: write_review.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Solo un utente autenticato puo' aggiungere una recensione ad un prodotto.
 */
needAuthenticatedUser(false, 'login.php');

/**
 * Se la pagina viene richiamata tramite il metodo GET, entro in modalita'
 * visualizzazione del form per l'inserimento della recensione: in questo caso
 * e' necessario specificare il parametro 'pid' ovvero l'identificativo del
 * prodotto.
 */
$pid = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['pid'])) {
        /**
         * Provvedo a convertire eventuali input 'malevoli' e verifico che il
         * parametro 'pid' sia di tipo numerico.
         */
        $pid = htmlentities($_GET["pid"]);
        if (!ctype_digit($pid)) {
            redirectToURL('products.php');
        }
    } else {
        redirectToURL('product.php');
    }
}

$error = false;

/**
 * Se la pagina e' stata invocata tramite il metodo POST vuol dire che l'utente
 * ha effettuato il 'submit' del form: in questo caso effettuo la validazione
 * dei dati inseriti e, qualora siano corretti, procedo all'inserimento del
 * record nella tabella 'reviews'.
 */
if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['submit'])) {
    /**
     * Applico le funzioni 'addslashes' e 'htmlentities' al valore di ogni
     * campo del form per prevenire SQL Injection e XSS
     */
    foreach ($_POST as $key => $value) {
        $data[$key] = addslashes(htmlentities($value));
    }

    if (!strlen($data['title']) || !strlen($data['descr']) || 
        !strlen($data['rating'])) {
        $error   = true;
        $err_msg = $strings['err_required'];
    } else {
        $data['rating'] = min(max(-1, $data['rating']), 6);
        executeQuery('INSERT INTO reviews VALUES (0, ?, ?, ?, ?, ?)',
                     array($data['pid'], $_SESSION['user']->uid, 
                           $data['title'], $data['descr'], $data['rating']));

        $record = executeQuery('SELECT * FROM products WHERE pid=?', $data['pid']);
        if ($record[0]['rating'] == 0) {
            executeQuery('UPDATE products SET rating=? WHERE pid=?', 
                        array($data['rating'], $data['pid']));
        } else {
            executeQuery('UPDATE products SET rating=(rating+?)/2 WHERE pid=?',
                        array($data['rating'], $data['pid']));
        }
        redirectToURL('detail.php?pid='. $data['pid']);
    }
}

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('wr_review', $_SESSION['lang']);

/**
 * Leggo dal database il record corrispondente al prodotto selezionato.
 */
$result = executeQuery('SELECT * FROM products WHERE pid=?', $pid);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra (ma non quello di destra).
 */
$page                = new FV_Page($strings['page_title'], array('review.css'), 
                                   array('formfieldlimiter.js'));
$page->printRightBox = false;
$page->printHead();
$page->printMenu();
?>

    <div id="tips">
        <h1><?php echo $strings['tip_header']; ?></h1>
        <ul>
        <li><?php echo $strings['tip1']; ?></li>
        <li><?php echo $strings['tip2']; ?></li>
        <li><?php echo $strings['tip3']; ?></li>
        <li><?php echo $strings['tip4']; ?></li>
        <li><?php echo $strings['tip5']; ?></li>
        </ul>
    </div>
    
    <table border="0" width="70%" class="review">
    <tr><td><h1 class="page_title">
            <?php echo $strings['page_title']; ?></h1></td></tr>
<?php 
if ($error === true) {
    echo '<tr><td><h3 style="font-size: 12px; text-align: center; '.
         'color: red;">['.$err_msg.']</h3></td></tr>';
}
?>
    <tr class="subtitle"><td>
            <?php echo $strings['write_for']. '&nbsp;' .$result[0]['name'] .
                       '.&nbsp;' . $strings['write_for-end']; ?>
            </td></tr>
    <tr><td>
        <form name="reviewform" action="write_review.php" method="post">
        <input type="hidden" name="pid" value="<?php echo $pid; ?>"/>
        <table class="inner_review" width="100%">
        <tr class="inner_title"><td><?php echo $result[0]["name"]; ?></td></tr>
        <tr><td><span style="margin-left:10px;">Title of the review </span>
                <span class="note">(please limit to 10 words)</span><br/>
                <input class="input_field" type="text" id="title" name="title" 
                       size="20" tabindex="20"/>
                <div id="title-status"></div></td></tr>
        <tr><td><span style="margin-left:10px;">Body of the review </span>
                <span class="note">(please limit to 300 words)</span><br/>
                <textarea style="margin-left: 10px;" rows="10" cols="55" 
                        tabindex="30" name="descr" 
                        id="styled">Enter your comment here&hellip;
                </textarea></td></tr>
        <tr><td><span style="margin-left:10px;">Rate this product </span>
                <span class="note">(1-5)</span><br/>
                <input class="rating_input_field" type="text" name="rating" 
                       size="3" value="0" tabindex="40"/></td></tr>
        <tr><td><input style="margin-left:10px;" type="submit" name="submit" 
                       value="submit" tabindex="100"/></td></tr>
        <tr><td>&nbsp;</td></tr>
        </table></td></tr>
        </form>
    </table><br/>
    
    <script type="text/javascript">
    try {
        document.getElementById('title').focus();
    } catch (e) {}

    fieldlimiter.setup({
        thefield: document.reviewform.title,
        maxlength: 50,
        statusids: ["title-status"], 
        onkeypress:function(maxlength, curlength){
            if (curlength<maxlength)
                this.style.border="2px solid gray" 
            else
                this.style.border="2px solid red"
        }
    })
    </script>
  
<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
