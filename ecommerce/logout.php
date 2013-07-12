<?php
/**
 * Logout utente
 *
 * La funzione di logout prevede la serializzazione sul database di un 
 * eventuale carrello dell'utente, la deallocazione della variabile di sessione
 * 'user', nonche' la distruzione della sessione stessa.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: logout.php 8 2009-08-25 15:03:54Z unixo $
 * @link     http://commerce.devzero.it
 * @see      FV_User
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Solo un utente autenticato puo' effettuare il logout dalla sessione attiva
 */
needAuthenticatedUser(false);

if (isset($_SESSION["user"])) {
    $logger->log('User '.$_SESSION['user']->login.' logged out', PEAR_LOG_INFO);
}

/**
 * Effettuo il logout dell'utente e distruggo equivalente istanza dell'oggetto
 * dalle variabili di sessione.
 */
$_SESSION['user']->logout();
unset($_SESSION["user"]);
@session_destroy();

redirectToURL("index.php");
?>
