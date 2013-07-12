<?php
/**
 * Modulo per l'eliminazione di un record dal database
 *
 * Tramite questa pagina viene simulato il pagamento della merce presente nel
 * carrello: l'operazione prevede inoltre di ridurre la disponibilita' del 
 * prodotto nonche' di inserire un record nella tabella delle transazioni per
 * ogni prodotto acquistato.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: delete.php 8 2009-08-25 15:03:54Z unixo $
 * @link     http://commerce.devzero.it
 * @see      admin.php
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Tutte le funzionalita' offerte dagli "Admin tools" sono ad esclusivo
 * appannaggio degli amministratori: in caso contrario effettuo un redirect
 * verso la pagina principale.
 */
needAuthenticatedUser(true);

/**
 * Controllo che tipo di oggetto devo cancellare: e' possibile eliminare una
 * categoria, un utente o svuotare i log applicativi.
 */
$obj = htmlentities($_GET['obj']);
if ($obj === 'cat') {
    /**
     * Eliminazione categoria: ottengo il 'category ID', verifico che il tipo
     * sia corretto e lo elimino dalla base dati.
     */
    $cid = htmlentities($_GET['cid']);
    if (ctype_digit($cid) === false) {
        throw new PEAR_Exception('Invalid category', ERR_PARAM);
    }
    executeQuery('DELETE FROM categories WHERE cid=?', $cid);
    redirectToURL('admin.php?tool=category');
} else if ($obj === 'user') {
    /**
     * Eliminazione utente: ottengo li 'user ID', verifico che il tipo
     * sia corretto e lo elimino dalla base dati.
     */
    $uid = htmlentities($_GET['uid']);
    if (ctype_digit($uid) === false) {
        throw new PEAR_Exception('Invalid user ID', ERR_PARAM);
    }
    executeQuery('DELETE FROM users WHERE uid=?', $uid);
    redirectToURL('admin.php?tool=user');
} else if ($obj === 'log') {
    /**
     * Effettuo il TRUNC della tabella 'log_table'
     */
    executeQuery('TRUNCATE TABLE log_table');
    redirectToURL('admin.php');
} else {
    throw new PEAR_Exception('Invalid parameter(s)', ERR_PARAM);
}

?>
