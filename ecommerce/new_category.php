<?php
/**
 * Inserimento nuova categoria
 *
 * Questa pagina viene richiamata direttamente dagli 'Admin Tools' al fine di
 * inserire una nuova categoria di prodotti.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: new_category.php 28 2009-09-08 10:36:51Z unixo $
 * @link     http://commerce.devzero.it
 * @see      admin.php
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'HTML/QuickForm.php';

/**
 * Tutte le funzionalita' offerte dagli "Admin tools" sono ad esclusivo
 * appannaggio degli amministratori: in caso contrario effettuo un redirect
 * verso la pagina principale.
 */
needAuthenticatedUser(true, 'index.php');

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('category', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array('category.css'));
$page->printHead();
$page->printMenu();

$form = new HTML_QuickForm('categoryform', 'post');

/**
 * Creo gli elementi che compongono il form e li aggiungo a $form; il valore
 * testuale del campo e' localizzato.
 */
$form->addElement('header', 'category_header', $strings['cat_header']);
$form->addElement('text', 'name', $strings['catame'], array('id'=>'name'));
$form->addElement('file', 'image', $strings['image']);
$form->addElement('submit', null, $strings['insert'], array('class' => 'mysubmit'));

/**
 * Imposto le regole di validazione del form (sintassi, semantica e campi
 * obbligatori)
 */
$form->addRule('name', $strings['catname_required'], 'required');
$form->addRule('image', $strings['image_required'], 'uploadedfile');
$form->addRule('image', $strings['file_too_big'], 'maxfilesize', 204800);

/**
 * Prima del POST, chiamo la funzione 'trim' per il contenuto di ogni campo
 */
$form->applyFilter('__ALL__', 'trim');

/**
 * Versione localizzata del messaggio di campo obbligatorio
 */
$form->setRequiredNote('<span style="font-size:80%; color: red">*</span> '.
                       $strings['required_note']);

if ($form->validate()) {
    /**
     * Applico le funzioni 'addslashes' e 'htmlentities' al valore di ogni
     * campo del form per prevenire SQL Injection e XSS
     */
    $form->applyFilter('__ALL__', 'addslashes');
    $form->applyFilter('__ALL__', 'htmlentities');
    
    $file =& $form->getElement('image'); 
    if ($file->isUploadedFile()) {
        $file->moveUploadedFile(dirname(__FILE__).'/uploads/');
        $fileInfo = $file->getValue();
        $sql      = "INSERT INTO categories VALUES (0,?, ?)";
        $data     = array($form->exportValue('name'), $fileInfo['name']);
        executeQuery($sql, $data);
        $logger->log('A new category has been added by '. 
                     $_SESSION["user"]->login, PEAR_LOG_NOTICE);
        redirectToURL("admin.php?tool=category");
    } else {
        die("file not uploaded");
    }
} else {
    /**
     * Imposto un template personalizzato per il form, in modo che possa
     * modificarne l'apparenza tramite CSS.
     */
    $renderer =& $form->defaultRenderer(); 
    $renderer->setFormTemplate('<form{attributes}><table class="categorytable" '.
                               'align="center" border="0">{content}</table></form>');
    $renderer->setHeaderTemplate("\n\t<tr>\n\t\t<td class=\"form_header\" ".
                                 "'align=\"center\" valign=\"middle\" ".
                                 "colspan=\"2\"><b>{header}</b></td>\n\t</tr>");
    $renderer->setElementTemplate('<tr><td align="left" class="input_label">{label}'.
        '<!-- BEGIN required --><span style="color: #ff0000">*</span>'.
        '<!-- END required --></td><td align="left">{element}'.
        '<!-- BEGIN error --><br /><span style="color: #ff0000;font-size:10px">'.
        '{error}</span><!-- END error --></td></tr>');
    $form->accept($renderer);

    /**
     * Visualizzo il FORM
     */
    $form->display();
}
?>

<script type="text/javascript">
try {
    document.getElementById('name').focus();
} catch (e) {}
</script>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>