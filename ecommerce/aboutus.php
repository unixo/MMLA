<?php
/**
 * Pagina 'About Us'
 *
 * La pagina 'About us' istanzia un oggetto Page per la creazione della
 * struttura HTML di base e richiama l'equivalente pagina 'aboutus' contenuta
 * all'interno del tema corrente, permettendo di fatto una personalizzazione
 * non solo del CSS ma anche del contenuto.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: aboutus.php 24 2009-09-08 10:33:59Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page('About us');
$page->printHead();
$page->printMenu();

/**
 * Controllo se il tema corrente definisce una versione personalizzata della
 * pagina e la includo.
 */
if (file_exists(THEME_PATH.'aboutus.php')) {
    include_once THEME_PATH.'aboutus.php';
}

/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
