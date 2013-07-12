<?php
/**
 * Rimozione prodotto dal carrello
 *
 * Dato l'identificativo del prodotto, questa pagina si occupa di rimuovere
 * l'equivalente voce dal carrello dell'utente: viene inoltre aggiornato il
 * numero di oggetti presenti nel carrello (ad una voce del carrello
 * corrisponde un singolo prodotto, ma la quantita' dello stesso puo' essere
 * maggiore di uno).
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: remove.php 8 2009-08-25 15:03:54Z unixo $
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
 * Rimuovo il prodotto specificato dal carrello dell'utente.
 */
$_SESSION['user']->basket->remove($_GET['pid']);
redirectToURL('summary.php');
?>
