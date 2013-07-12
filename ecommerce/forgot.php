<?php
/**
 * Recupero password persa
 *
 * Qualora l'utente perda la password di login, tramite questa pagina viene
 * generata una nuova password pseudo-casuale ed inviata via mail.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: forgot.php 26 2009-09-08 10:36:06Z unixo $
 * @link     http://commerce.devzero.it
 * @see      FV_User, FV_Page
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Se la variabile di sessione 'user' e' stata gia' registrata vuol dire che
 * l'utente e' gia' autenticato: in questo caso effettuo un redirect alla
 * pagina che consente la modifica dell'anagrafica.
 */
if (isset($_SESSION['user']) === true) {
    if (!$_SESSION['user']->isDummy()) {
        redirectToURL('edit.php');
    }
}

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('forgot', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page                = new FV_Page($strings['page_title'], array('login.css'));
$page->printRightBox = true;
$page->printHead();
$page->printMenu();

/**
 * Se l'utente ha cliccato sul tasto 'Reset', questa pagina viene richiamata
 * tramite il metodo POST: in questo caso uso la funzione statica forgotPassword
 * della class FV_User per inviare all'utente una nuova password, generata in 
 * modo pseudo-casuale.
 * Traccio l'evento nella tabella dei log e comunico l'invio password all'utente
 */
$error = false;
if (isset($_POST['login']) === true) {
    if (strlen($_POST['login']) === 0) {
        $error   = true;
        $err_msg = $strings['req_field'];
    } else {
        FV_User::forgotPassword($_POST['login']);
        $logger->log('Password for user '.$_POST['login'].' has been resetted', 
                     PEAR_LOG_INFO);
        echo '<table border="0" class="central_box" width="100%" '.
             'style="margin-top: 50px"><tr align="center"><td>'.
             '<span style="font-size: 14px;">'.
             $strings['sent_msg'].'</span></td></tr></table>';
        /**
         * Completo la struttura della pagina stampando il menu orizzontale inferiore
         * ed inviando il tutto al browser dell'utente.
         */
        $page->printFooter();
        exit();
    }
}
?>

<table align="center" width="300" style="margin-top: 30px; margin-bottom: 80px">
<tr><td><h1 class="page_title"><?php echo $strings['page_title']; ?>
        </h1></td></tr>
<tr><td>
    <form name="loginform" id="loginform" action="forgot.php" method="post">
      <label><?php echo $strings['username']; ?><br/>
      <input type="text" id="user_login" class="input" name="login" 
             size="20" tabindex="1" /></label>
<?php
if ($error) {
    echo '<span style="color: red">'.$err_msg.'</span><br/>';
}
?>
      <input type="submit" name="rst-submit" class="reset-submit" 
             value="<?php echo $strings['reset']; ?>" 
             tabindex="100" />
    </form></td></tr>
</table>

<script type="text/javascript">
    try {
        document.getElementById('user_login').focus();
    } catch (e) {}
</script>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
