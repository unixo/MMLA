<?php
/**
 * Prodotti 'Top rated'
 *
 * Questa pagina visualizza, in forma tabellare, i prodotti a piu' elevato
 * rating, in ordine crescente.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: top_rated.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'Structures/DataGrid.php';
require_once 'HTML/Table.php';

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('top_rated', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title']);
$page->printHead();
$page->printMenu();
?>

<table border="0" class="central_box" width="100%">
<tr><td><h1 class="page_title">
        <?php echo $tr->get('top_products', 'top_products'); ?>
        </h1></td></tr>
<tr><td>
    <?php
        $datagrid =& new Structures_DataGrid(8);
        $datagrid->addColumn(new Structures_DataGrid_Column(null, "image", null, 
                                array('width' => '80', 'align' => 'center'), 
                                null, 'printTopImage'));
        $datagrid->addColumn(new Structures_DataGrid_Column(null, "name", null, 
                                array('width' => '300'), null, 'printTopTitle'));
        $datagrid->addColumn(new Structures_DataGrid_Column(null, "rating", null, 
                                array('width' => '160'), null, 
                                'printProductRating'));

        $options = array('dsn' => $dbURL);
        $datagrid->bind('SELECT * FROM products ORDER BY rating DESC', $options);

        $tableAttribs   = array('align' => 'center', 'width' => '100%',
                                'cellspacing' => '0', 'cellpadding' => '10',
                                'class' => 'datagrid');
        $evenRowAttribs = array('bgcolor' => '#F7F7F7', 'style' => 'height:100px;');
        $oddRowAttribs  = array('bgcolor' => '#FFFFFF', 'style' => 'height:100px;');

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
 * Stampa l'immagine del prodotto: nel campo del database e' contenuto solo il
 * nome del file che viene quindi visualizzato tramite il tag IMG.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printTopImage($params)
{
    extract($params);
    return '<a href="detail.php?pid='.$record["pid"].'">'.
           '<img alt="prdimg" width="70" border="0" src="'.CONTENT_URL.
           '/uploads/'.$record["image"].'"/></a>';
}

/**
 * Funzione di callback: titolo prodotto
 *
 * Stampa la descrizione del prodotto corrente.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printTopTitle($params)
{
    return '<table cellspacing="0" cellpadding="0" border="0"><tr><td><h4>'.
           $params['record']['name'].'</h4></td></tr><tr><td>'.
           html_entity_decode(substr($params['record']['descr'], 0, 150)).
           '</td></tr></table>';
}

/**
 * Funzione di callback: rating del prodotto
 *
 * Stampa, nell'ultima colonna della tabella dei prodotti, il rating del 
 * prodotto corrente, usando l'immagine della stella blu per rappresentare un
 * valore compreso nell'intervallo chiuso [0, 5], nonché il numero di 
 * recensioni associate.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printProductRating($params)
{
    global $tr;
    
    $pid     = $params['record']['pid'];
    $reviews = executeQuery('SELECT * FROM reviews WHERE pid=?', $pid);
    $html    = '<table border="0" align="center"><tr align="center"><td>' . 
               printRating($params['record']['rating'], false) . '</td></tr>'.
               '<tr align="center"><td>'. $tr->get('based_on', 'detail'). 
               '&nbsp; '. count($reviews). '&nbsp;'.
               $tr->get('reviews', 'detail') .'</td></tr></table>';
    return $html;
}

?>