<?php
/**
 * Motore di ricerca
 *
 * Questa pagina corrisponde all'action del FORM di ricerca che compare in ogni
 * pagina (in alto a dx): se e' definito il campo 'pid', tramite la funzione
 * autosuggest, dirigo la navigazione dell'utente alla scheda di dettaglio,
 * viceversa al form di ricerca.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link     http://commerce.devzero.it
 * @version  SVN: $Id: do_search.php 8 2009-08-25 15:03:54Z unixo $
 */
require_once dirname(__FILE__).'/includes/common.php';

$pid = 0;
if (isset($_POST['pid'])) {
    $pid = htmlentities($_POST['pid']);
}

/**
 * L'identificativo del prodotto e' valorizzato solo se la funzione autosuggest
 * ha trovato un'occorrenza nel database: in questo caso posso visualizzare
 * direttamente la scheda di dettaglio, viceversa mostro il form di ricerca.
 */
if ($pid != 0) {
    redirectToURL('detail.php?pid='.$pid);
} else {
    redirectToURL('searchform.php?name='.addslashes(htmlentities($_POST['what'])));
}

?>