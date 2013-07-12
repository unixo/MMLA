<?php
/**
 * Inserimento nuovo prodotto
 *
 * Questa pagina viene richiamata direttamente dagli 'Admin Tools' al fine di
 * inserire un nuovo prodotto nel database.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: new_product.php 23 2009-09-08 10:31:27Z unixo $
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
$strings = $tr->getPage('new_product', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array("products.css"),
                    array('wysiwyg.js'));
$page->printHead();
$page->printMenu();

/**
 * Creo un vettore associativo contenente tutte le categorie disponibili
 */
$results = executeQuery("SELECT * FROM categories ORDER BY name");
foreach ($results as $row) {
    $categories[$row['cid']] = $row['name'];
}

$form = new HTML_QuickForm('newprdform', 'post');

/**
 * Creo gli elementi che compongono il form e li aggiungo a $form; il valore
 * testuale del campo e' localizzato.
 */
$form->addElement('header', 'product_header', $strings['page_title']);
$form->addElement('text', 'name', $strings['product_name'], 
                  array('id'=>'prd_name'));
$form->addElement('select', 'cid', $strings['category'], $categories);
$form->addElement('textarea', 'descr', $strings['description'], 
                  'wrap="soft" rows="10" cols="55" id="descr"');
$form->addElement('text', 'price', $strings['price'], 'class="num_field"');
$form->addElement('text', 'availability', $strings['availability'], 
                  'class="num_field"');
$form->addElement('text', 'ship_time', $strings['ship_time'], 'class="num_field"');
$form->addElement('file', 'image', $strings['image']);
$form->addElement('submit', null, $strings['insert'], array('class' => 'mysubmit'));

/**
 * Imposto le regole di validazione del form (sintassi, semantica e campi
 * obbligatori)
 */
$form->addRule('name', $strings['name_required'], 'required');
$form->addRule('price', $strings['price_required'], 'required');
$form->addRule('availability', $strings['avail_required'], 'required');
$form->addRule('ship_time', $strings['shiptime_required'], 'required');
$form->addRule('image', $strings['image_required'], 'uploadedfile');
$form->addRule('image', $strings['file_too_big'], 'maxfilesize', 204800);

/**
 * Imposto i valori di default degli elementi del FORM.
 */
$form->setDefaults(array('price' => '0.0', 'availability' => '0', 
                         'ship_time' => '24'));

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

        $sql  = "INSERT INTO products VALUES (0, ?, ?, ?, ?, ?, ?, 0, ?)";
        $data = array($form->exportValue('cid'), $form->exportValue('name'), 
                      $form->exportValue('descr'), $form->exportValue('price'),
                      $form->exportValue('availability'), 
                      basename($fileInfo['name']),
                      $form->exportValue('ship_time'));

        executeQuery($sql, $data);
        $logger->log("A new product has been added by ". 
                     $_SESSION["user"]->login, PEAR_LOG_NOTICE);
        redirectToURL("admin.php?tool=product");
    } else {
        die("file not uploaded");
    }
} else {
    /**
     * Imposto un template personalizzato per il form, in modo che possa
     * modificarne l'apparenza tramite CSS.
     */
    $renderer =& $form->defaultRenderer(); 
    $renderer->setFormTemplate('<form{attributes}><table class="newprdtable" '.
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
        document.getElementById('prd_name').focus();
    } catch (e) {}

    generate_wysiwyg('descr');
</script>

<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>