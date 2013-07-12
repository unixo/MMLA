<?php
/**
 * Svuotamente carrello
 *
 * Tramite questa pagina, viene svuotato il carrello dell'utente corrente:
 * una volta effettuata l'operazione, la navigazione viene rediretta alla
 * pagina principale.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: empty.php 8 2009-08-25 15:03:54Z unixo $
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
 * Svuoto il carrello dell'utente
 */
$_SESSION['user']->basket->emptyBasket();
redirectToURL('index.php');
?>
