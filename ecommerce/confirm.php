<?php
/**
 * Modulo per l'attivazione di un utente.
 *
 * A seguito del processo di registrazione di un nuovo utente, l'equivalente
 * record nel database viene marcato come 'non valido', viene inviata una
 * mail di conferma all'utente con il link per attivare l'account (tramite
 * questa pagina).
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: confirm.php 8 2009-08-25 15:03:54Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Verifico che il parametro 'code' sia stato specificato nella URL.
 */
if (isset($_GET['code']) === false) {
    redirectToURL('index.php');
}

/*
 * Attivo l'utente equivalente al codice specificato ed effettuo il redirect
 * alla pagina di login.
 */
$code = htmlentities($_GET['code']);
executeQuery('UPDATE users SET valid=1 WHERE securecode=?', $code);
redirectToURL('login.php');

?>