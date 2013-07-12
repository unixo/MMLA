<?php
/**
 * Ricerca estesa prodotti
 *
 * La pagina presenta un form che permette di ricercare uno o più prodotti
 * tramite parola chiave, estendendo la ricerca anche alla descrizione del
 * prodotto stesso.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: searchform.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'Structures/DataGrid.php';
require_once 'HTML/QuickForm.php';
require_once 'HTML/Table.php';

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('search', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array('search.css'));
$page->printHead();
$page->printMenu();

$form = new HTML_QuickForm('searchform', 'post');

/**
 * Creo gli elementi che compongono il form e li aggiungo a $form; il valore
 * testuale del campo e' localizzato.
 */
$form->addElement('header', 'search_header', $strings['search_header']);
$form->addElement('text', 'name', $strings['product_name'], array('id'=>'name'));
$form->addElement('checkbox', 'descr', $strings['descr_search']);
$form->addElement('submit', null, $strings['search'], array('class' => 'mysubmit'));

/**
 * Prima del POST, chiamo la funzione 'trim' per il contenuto di ogni campo
 */
$form->applyFilter('__ALL__', 'trim');

$do_query = false;
if (isset($_GET['name'])) {
    /**
     * Imposto i valori di default degli elementi del FORM.
     */
    $form->setDefaults(array('name' => $_REQUEST['name']));
    $do_query = true;
}

/**
 * Imposto un template personalizzato per il form, in modo che possa
 * modificarne l'apparenza tramite CSS.
 */
$renderer =& $form->defaultRenderer(); 
$renderer->setFormTemplate('<form{attributes}><table class="searchtable" '.
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

if ($form->validate() || $do_query) {
    /**
     * Applico le funzioni 'addslashes' e 'htmlentities' al valore di ogni
     * campo del form per prevenire SQL Injection e XSS
     */
    $form->applyFilter('__ALL__', 'addslashes');
    $form->applyFilter('__ALL__', 'htmlentities');
    
    $datagrid =& new Structures_DataGrid(10);
    $datagrid->addColumn(new Structures_DataGrid_Column(null, "image", 
                                null, array('width' => '80', 
                                'align' => 'center'), null, 'printSearchImage'));
    $datagrid->addColumn(new Structures_DataGrid_Column(null, "name", 
                                null, array('width' => '320'), null, 
                                'printSearchTitle'));
    $datagrid->addColumn(new Structures_DataGrid_Column(null, "price", null, 
                                array('width' => '90', 'align' => 'right'), 
                                null, 'printSearchCurrency'));
    $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, 
                                array('width' => '20', 'align' => 'center'), 
                                null, 'printSearchBasket'));

    /**
     * SELECT da eseguire per ottenere la lista dei prodotti ricercati; se il
     * campo 'descr' vale true estendo la ricerca anche alla descrizione del
     * prodotto.
     */
    $sql = 'SELECT * FROM products WHERE name LIKE "%'. 
           $form->exportValue('name') .'%"';
    if ($form->exportValue('descr')) {
        $sql .= 'OR descr LIKE "%'. $form->exportValue('name') .'%"';
    }

    /**
     * Imposto il DSN del database ed effettuo la query.
     */
    $options = array('dsn' => $dbURL);
    $datagrid->bind($sql, $options);

    /**
     * Proprieta' della tabella da stampare e colori delle righe della
     * tabella stessa: vengono definiti due colori, uno per le righe pari
     * ed un secondo per quelle dispari.
     */
    $tableAttribs   = array('align' => 'center', 'width' => '100%', 
                            'cellspacing' => '0', 'cellpadding' => '10', 
                            'class' => 'datagrid');
    $evenRowAttribs = array('bgcolor' => '#F7F7F7', 'style' => 'height:100px');
    $oddRowAttribs  = array('bgcolor' => '#FFFFFF', 'style' => 'height:100px');

    /**
     * Creo la tabella tramite PEAR con gli attributi appena definiti.
     */
    $table       = new HTML_Table($tableAttribs);
    $tableHeader =& $table->getHeader();
    $tableBody   =& $table->getBody();

    $datagrid->fill($table);
    /**
     * Stampo il contenuto della ricerca solo se il result set e' pieno,
     * viceversa un messaggio che avvisi l'utente.
     */
    if($datagrid->getRecordCount() > 0) {
        $tableBody->altRowAttributes(0, $evenRowAttribs, $oddRowAttribs, true);
        echo $table->toHtml();
        echo '<br/><center>';
        $log_render = $datagrid->render(DATAGRID_RENDER_PAGER);
        echo '</center><br/>';
    } else {
        echo '<center><span style="font-size: 14px; color:red">'.
             $strings['not_found'].'</span></center>';
    }
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


/**
 * Funzione di callback: immagine
 *
 * Viene richiamata, per ogni record, dall'oggetto DataGrid per stampare
 * l'immagine del singolo prodotto.
 *
 * @param mixed $params Vettore contenente il record corrente
 *
 * @return void
 */
function printSearchImage($params)
{
    extract($params);
    return '<a href="detail.php?pid='. $record["pid"] . '">'.
           '<img alt="prdimg" width="70" border="0" src="'.CONTENT_URL.
           '/uploads/' . $record["image"] . '"/></a>';
}

/**
 * Funzione di callback: nome
 *
 * Stampa il nome del prodotto corrente e la sua descrizione.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printSearchTitle($params)
{
    $html  = '<table border="0" cellpadding="0" cellspacing="0"><tr><td><strong>'.
             $params["record"]["name"] . '</strong></td></tr>';
    $html .= '<tr><td>'.
             html_entity_decode(substr($params["record"]["descr"], 0, 200)).
             '</td></tr></table>';
    return $html;
}

/**
 * Funzione di callback: prezzo
 *
 * Stampa il prezzo del prodotto, formattando l'importo con la funzione
 * currency
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 * @see currency
 */
function printSearchCurrency($params)
{
    return "&euro; " . currency($params["record"]["price"]);
}

/**
 * Funzione di callback: basket
 *
 * Viene richiamata, per ogni record, dall'oggetto DataGrid per stampare il
 * pulsante per aggiungere il prodotto al carrello; la funzione verifica
 * che la disponibilita' del prodotto sia maggiore a zero.
 *
 * @param mixed $params Vettore contenente il record corrente
 *
 * @return void
 */
function printSearchBasket($params)
{
    if ($params["record"]["availability"] > 0) {
        return '<a href="add.php?pid=' . $params["record"]["pid"] . 
                '"><img border="0" alt="basket" src="'. CONTENT_URL .
                '/images/carrello.gif"/></a>';
    } else {
        return '<img width="20" alt="No availability" src="'.CONTENT_URL.
               '/images/warning.gif"/>';
    }
}
?>