<?php
/**
 * Elenco prodotti
 *
 * La pagina visualizza l'elenco di tutti i prodotti disponibili nello store,
 * suddividendo per pagine l'intero result set.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: products.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'Structures/DataGrid.php';
require_once 'HTML/Table.php';

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('products', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array('products.css'));
$page->printHead();
$page->printMenu();
?>

<table border="0" class="central_box" width="100%">
<tr><td><h1 class="page_title"><?php echo $strings['page_title']; ?>
        </h1></td></tr>
<tr><td>
<?php
/**
 * Creo una tabella con quattro colonne per stampare l'elenco dei 
 * prodotti; ad ogni colonna e' associato un formattatore, definito in
 * coda alla pagina.
 * La tabella viene inoltre seguita dal Pager, ogni pagina contiene
 * otto record.
 */
$datagrid =& new Structures_DataGrid(8);
$datagrid->addColumn(new Structures_DataGrid_Column(null, "image", 
                            null, array('width' => '80', 
                            'align' => 'center'), null, 'printImage'));
$datagrid->addColumn(new Structures_DataGrid_Column(null, "name", 
                            null, array('width' => '320'), null, 
                            'printTitle'));
$datagrid->addColumn(new Structures_DataGrid_Column(null, "price", null, 
                            array('width' => '90', 'align' => 'right'), 
                            null, 'printCurrency'));
$datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, 
                            array('width' => '20', 'align' => 'center'), 
                            null, 'printBasket'));

/**
 * SELECT di default da eseguire per ottenere la lista dei prodotti
 * disponibili nello store; qualora la pagina venga richiamata da
 * "categories.php" (ricerca per categorie), viene accodata la WHERE
 * per effettuare il filtro.
 */
$sql = "SELECT * FROM products";
if (isset($_GET['cid'])) {
    $cid = htmlentities($_GET["cid"]);
    if (ctype_digit($cid)) {
        $sql .= " WHERE cid = ". $cid;
    }
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

$tableBody->altRowAttributes(0, $evenRowAttribs, $oddRowAttribs, true);
echo $table->toHtml();
echo '<br/><center>';
$log_render = $datagrid->render(DATAGRID_RENDER_PAGER);
echo '</center><br/>';
?>
    </td></tr>
</table>

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
function printImage($params)
{
    extract($params);
    return '<a href="detail.php?pid='. $record["pid"] . '">'.
           '<img alt="prdimg" width="90" height="90" border="0" src="'.
           CONTENT_URL.'/uploads/' . $record["image"] . '"/></a>';
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
function printTitle($params)
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
function printCurrency($params)
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
function printBasket($params)
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
