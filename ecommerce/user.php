<?php
/**
 * Modulo per l'abilitazione e disabilitazione di un utente
 *
 * Tramite questa pagina, fornito l'identificativo dell'utente, e' possibilie
 * abilitarne o disabilitarne il login.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: user.php 8 2009-08-25 15:03:54Z unixo $
 * @link     http://commerce.devzero.it
 * @see      admin.php, FV_User
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Tutte le funzionalita' offerte dagli "Admin tools" sono ad esclusivo
 * appannaggio degli amministratori: in caso contrario effettuo un redirect
 * verso la pagina principale.
 */
needAuthenticatedUser(true, 'index.php');

/**
 * Controllo che tipo di operazione devo effettuare sull'utente: e' possibile
 * abilitare/disabilitare un UID o inviare una mail con le nuove credenziali
 * alla sua email.
 */
$op = htmlentities($_GET['op']);

if (($op == 'ena') || ($op === 'dis')) {
    $value = ($op === 'ena');
    FV_User::enableUser($_GET['uid'], $value);
    redirectToURL('admin.php?tool=user');
} else if ($op === 'mail') {
    $login = $_GET['login'];
    FV_User::sendVerificationEmail($login);
    redirectToURL('admin.php?tool=user');
} else {
    throw new PEAR_Exception('Invalid parameter(s)', ERR_PARAM);
}

redirectToURL("index.php");

?>
