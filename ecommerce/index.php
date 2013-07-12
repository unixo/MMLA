<?php
/**
 * Pagina principale
 *
 * Homepage del sito: viene dapprima stampata la struttura portante (menu,
 * blocchi a sinistra e destra) e poi incorporata l'equivalente index.php del
 * tema corrente.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: index.php 26 2009-09-08 10:36:06Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page('/dev/zero commerce');
$page->printHead();
$page->printMenu();

if (file_exists(THEME_PATH.'index.php')) {
    include_once THEME_PATH.'index.php';
}

/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>