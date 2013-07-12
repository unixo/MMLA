<?php
/**
 * Informativa privacy
 *
 * Informativa sul trattamento dei dati personali, dei cookie.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: privacy.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page                = new FV_Page('Privacy');
$page->printRightBox = false;
$page->printHead();
$page->printMenu();
?>

<table border="0" width="100%" class="central_box">
<tr><td><h1 class="page_title">/dev/zero Customer Privacy Policy</h1></td></tr>
<tr><td><div id="small">
        /dev/zero’s Customer Privacy Policy covers the collection, use, and 
        disclosure of personal information that may be collected by /dev/zero 
        anytime you interact with /dev/zero, such as when you visit our website, 
        when you purchase /dev/zero products and services, or when you call our 
        sales or support associates. Please take a moment to read the following 
        to learn more about our information practices, including what type of 
        information is gathered, how the information is used and for what 
        purposes, to whom we disclose the information, and how we safeguard 
        your personal information. Your privacy is a priority at /dev/zero, 
        and we go to great lengths to protect it.</div>
    </td></tr>

<tr><td><h1 class="page_title">Why we collect personal information</h1></td></tr>
<tr><td>We collect your personal information because it helps us deliver a 
        superior level of customer service. It enables us to give you convenient 
        access to our products and services and focus on categories of greatest 
        interest to you. In addition, your personal information helps us keep 
        you posted on the latest product announcements, software updates, 
        special offers, and events that you might like to hear about.

        If you do not want /dev/zero to keep you up to date with /dev/zero news, 
        software updates and the latest information on products and services 
        click commerce.devzero.com/edit.php and update your personal contact 
        information and preferences.
    </td></tr>
</table>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
