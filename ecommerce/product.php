<?php
/**
 * Modifica dati di un prodotto
 *
 * Tramite questa pagina e' possibile:
 * - eliminare un prodotto esistente
 * - incrementare/ridurre la disponibilita' del prodotto
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: product.php 8 2009-08-25 15:03:54Z unixo $
 * @link     http://commerce.devzero.it
 * @see      admin.php
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Tutte le funzionalita' offerte dagli "Admin tools" sono ad esclusivo
 * appannaggio degli amministratori: in caso contrario effettuo un redirect
 * verso la pagina principale.
 */
needAuthenticatedUser(true, 'index.php');

/**
 * Ottengo dalla richiesta l'ID del prodotto sul quale effettuare l'operazione:
 * verifico inoltre che il tipo sia corretto.
 */
$pid = htmlentities($_GET['pid']);
if (!ctype_digit($pid)) {
    throw new PEAR_Exception('Invalid product number', ERR_PARAM);
}

/**
 * Controllo che tipo di operazione devo effettuare sul prodotto: e' possibile
 * incrementare/decrementare la disponibilita' o eliminarlo
 */
$op = htmlentities($_GET['op']);
if ($op === "del") {
    executeQuery("DELETE FROM products WHERE pid=?", $_GET["pid"]);
    redirectToURL("admin.php?tool=product");
} else if ($op === "inc") {
    executeQuery('UPDATE products SET availability=availability+1 '.
                 'WHERE pid = ?', $pid);
    redirectToURL('admin.php?tool=product');
} else if ($op === "dec") {
    executeQuery('UPDATE products SET availability=availability-1 '.
                 'WHERE availability>0 AND pid = ?', $pid);
    redirectToURL("admin.php?tool=product");
} else {
    throw new PEAR_Exception('Invalid parameter(s)', ERR_PARAM);
}

redirectToURL('index.php');

?>
