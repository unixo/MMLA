<?php
/**
 * Cambio localizzazione
 *
 * Se l'utente sceglie di vedere il sito in una lingua diversa, questa pagina
 * imposta l'opportuna variabile di sessione e ricarica la pagina chiamante
 * (referer).
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: lang.php 9 2009-08-25 15:05:48Z unixo $
 * @link     http://commerce.devzero.it
 */
@session_start();

error_reporting(0);
ini_set('display_errors', false);
ini_set('html_errors', false);

$allowed_langs = array('it', 'en');

if (isset($_GET['lang'])) {
    $lang = strtolower(htmlentities($_GET['lang']));
    if (in_array($lang, $allowed_langs) === true) {
        $_SESSION['lang'] = $lang;
    }
}

if (isset($_SERVER["HTTP_REFERER"])) {
    header('Location: '.$_SERVER["HTTP_REFERER"]);
} else {
    header('Location: index.php');
}
?>