<?php
/**
 * Modifica prodotto
 *
 * Questa pagina viene richiamata direttamente dagli 'Admin Tools' al fine di
 * modificare un prodotto nel database.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: edit_product.php 30 2009-09-08 13:40:41Z unixo $
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
$strings = $tr->getPage('edit_product', $_SESSION['lang']);

/**
 * Ottengo dalla richiesta l'ID del prodotto di cui modificare la scheda:
 * verifico che il tipo sia corretto e procedo con la visualizzazione del FORM
 * contenente i dati del prodotto.
 */
$pid = htmlentities($_REQUEST['pid']);
if (!ctype_digit($pid)) {
    throw new PEAR_Exception('Invalid product ID', ERR_PARAM);
}
$record = executeQuery('SELECT * FROM products WHERE pid=?', $pid);
if (count($record) === 0) {
    throw new PEAR_Exception('Invalid product ID', ERR_PARAM);
}

/**
 * Creo un vettore associativo contenente tutte le categorie disponibili
 */
$results = executeQuery("SELECT * FROM categories ORDER BY name");
foreach ($results as $row) {
    $categories[$row['cid']] = $row['name'];
}

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array("products.css"),
                    array('wysiwyg.js'));
$page->printHead();
$page->printMenu();

$form = new HTML_QuickForm('editprdform', 'post');

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
$form->addElement('hidden', 'pid');
$form->addElement('submit', null, $strings['update'], array('class' => 'mysubmit'));

/**
 * Imposto le regole di validazione del form (sintassi, semantica e campi
 * obbligatori)
 */
$form->addRule('name', $tr->get('name_required', 'new_product'), 'required');
$form->addRule('price', $tr->get('price_required', 'new_product'), 'required');
$form->addRule('availability', $tr->get('avail_required', 'new_product'), 
               'required');
$form->addRule('ship_time', $tr->get('shiptime_required', 'new_product'), 
               'required');
$form->addRule('image', $tr->get('file_too_big', 'new_product'), 'maxfilesize',
               204800);

/**
 * Imposto i valori di default degli elementi del FORM.
 */
$form->setDefaults(array('name' => stripslashes($record[0]['name']), 
                   'descr' => $record[0]['descr'], 'price' => $record[0]['price'], 
                   'availability' => $record[0]['availability'], 
                   'ship_time' => $record[0]['ship_time'],
                   'pid' => $record[0]['pid'], 'cid' => $record[0]['cid']));

/**
 * Prima del POST, chiamo la funzione 'trim' per il contenuto di ogni campo
 */
$form->applyFilter('__ALL__', 'trim');

/**
 * Versione localizzata del messaggio di campo obbligatorio
 */
$form->setRequiredNote('<span style="font-size:80%; color: red">*</span> '.
                       $tr->get('required_note', 'new_product'));

if ($form->validate()) {
    /**
     * Creo un vettore con i valori contenuti nel form da associare ad una
     * istruzione UPDATE per aggiornare il database; se l'utente specifica una
     * nuova immagine, a tale vettore verra' aggiunta l'informazione relativa
     * al file, viceversa verra' creata una UPDATE senza la voce "SET image".
     */
    $values = array($form->exportValue('name'), $form->exportValue('cid'), 
                    $form->exportValue('descr'), $form->exportValue('price'), 
                    $form->exportValue('availability'), 
                    $form->exportValue('ship_time'));

    $file =& $form->getElement('image'); 
    if ($file->isUploadedFile()) {
        $file->moveUploadedFile(dirname(__FILE__).'/uploads/');
        $fileInfo = $file->getValue();
        $sql      = 'UPDATE products SET name=?, cid=?, descr=?, '.
                    'price=?, availability=?, ship_time=?, image = ? WHERE pid=?';
        array_push($values, $fileInfo['name']);
    } else {
        $sql = 'UPDATE products SET name=?, cid=?, descr=?, '.
               'price=?, availability=?, ship_time=? WHERE pid=?';      
    }
    /**
     * Il PID del prodotto deve essere l'ultimo valore del vettore, in quanto
     * corrisponde all'ultima "bind variable" dell'istruzione UPDATE.
     */
    array_push($values, $form->exportValue('pid'));

    executeQuery($sql, $values);
    redirectToURL('detail.php?pid='.$form->exportValue('pid'));
} else {
    /**
     * Imposto un template personalizzato per il form, in modo che possa
     * modificarne l'apparenza tramite CSS.
     */
    $renderer =& $form->defaultRenderer(); 
    $renderer->setFormTemplate('<form{attributes}><table class="editprdtable" '.
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