<?php
/**
 * Aggiunta di un prodotto al carrello.
 *
 * Un utente che abbia gia' completato con successo il processo di autenticazione
 * puo' aggiungere un prodotto al proprio carrello, qualora il prodotto abbia
 * una disponibilita' pari o superiore ad uno.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: add.php 8 2009-08-25 15:03:54Z unixo $
 * @link     http://commerce.devzero.it
 * @see      FV_User, FV_Basket
 */
require_once dirname(__FILE__).'/includes/common.php';

if (isset($_GET['pid']) === false) {
    redirectToURL('index.php');
}

/**
 * Verifico che esista, tra le variabili di sessione, l'oggetto 'user': in caso
 * contrario creo un utente fittizio, non ancora autenticato, perche' siano
 * disponibili tutte le funzionalita' del carrello.
 */
checkSessionUser();

/**
 * Aggiungo il prodotto al carrello.
 */
$_SESSION['user']->basket->add($_GET['pid']);
redirectToURL('summary.php');
?>
