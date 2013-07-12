<?php
/**
 * Modulo per leggere le recensioni di un prodotto.
 *
 * Un utente che abbia gia' completato con successo il processo di autenticazione
 * puo' scrivere una recensione di un prodotto, fornendo un titolo, una breve
 * descrizione ed un 'rating' del prodotto stesso.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: read_reviews.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'Structures/DataGrid.php';
require_once 'HTML/Table.php';

$pid = 0;
if (!isset($_GET["pid"])) {
    redirectToURL("products.php");
} else {
    $pid = htmlentities($_GET["pid"]);
    if (!ctype_digit($pid)) {
        throw new PEAR_Exception('Invalid product ID', ERR_PARAM);
    }
}

$record = executeQuery("SELECT * FROM products WHERE pid=?", $pid);
if (count($record) == 0) {
    $msg = "Nonexistent product";
    redirectToURL("error.php?msg=". $msg);
}
$reviews = executeQuery('SELECT * FROM reviews WHERE pid=?', $pid);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page("Product review", array("review.css"));
$page->printHead();
$page->printMenu();
?>

<table width="100%" class="central_box">
<tr><td align="center">
    <table class="product" width="100%">
    <tr><td><img width="80" 
        src="<?php echo CONTENT_URL; ?>/uploads/<?php echo $record[0]["image"]; ?>">
        </td>
        <td align="left"><h2><?php echo $record[0]["name"]; ?></h2></td>
        <td align="right">
            <a href="detail.php?pid=<?php echo $record[0]["pid"]; ?>">
            <img src="<?php echo CONTENT_URL; ?>/images/product-overview-btn.png" 
            border="0">
        </a></td></tr>
    </table></td></tr>
    
<tr><td>
    <table class="product" width="100%">
    <tr valign="middle"><td width="30%"><h2>Customer rating</h2></td>
        <td width="50%"><?php printRating($record[0]["rating"]); ?>
           &nbsp;(<?php echo $tr->get('based_on', 'detail').'&nbsp;'.
           count($reviews). '&nbsp;'.$tr->get('reviews', 'detail'); ?>)
        </td>
        <td><a href="write_review.php?pid=<?php echo $pid; ?>">
           <?php echo $tr->get('write_review', 'detail'); ?></a></td></tr>
    </table></td></tr>
<tr><td>
    
<?php

    $datagrid =& new Structures_DataGrid(10);
    $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, 
                                array('width' => '100'), null, 
                                'printReviewTitle'));
    $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, 
                                array('width' => '300'), null, 'printDescr'));
    
    $options = array('dsn' => $dbURL);
    $datagrid->bind("SELECT CONCAT_WS(' ', users.first_name, users.last_name) ".
                    "AS fullname, reviews.* from reviews, users ".
                    "where users.uid = reviews.uid and reviews.pid=". $pid, 
                    $options);

    $tableAttribs   = array('align' => 'center', 'border' => 0,
                            'cellspacing' => '0', 'cellpadding' => '0', 
                            'class' => 'datagrid');
    $evenRowAttribs = array('bgcolor' => '#EEEEEE'); 
    $oddRowAttribs  = array('bgcolor' => '#FFFFFF');
    
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
 * Funzione di callback: Titolo
 *
 * Stampa il titolo della recensione e il punteggio, in scala da 1 a 5, 
 * assegnato dall'utente al prodotto.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printReviewTitle($params)
{
    global $tr;

    $html = '<table border="0"><tr><td>'. 
            printRating($params["record"]["rating"], false). '</td></tr>'.
            '<tr class="review_title"><td >'.$params["record"]["title"].
            '</td></tr><tr><td>'. $tr->get('written_by', 'reviews').'&nbsp;'.
            $params['record']['fullname'] .'</td></tr></table>';
    return $html;
}

/**
 * Funzione di callback: testo della recensione
 *
 * Stampa, nell'ultima colonna della tabella, il corpo della recensione.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printDescr($params)
{
    return '<span class="review_descr">'.nl2br($params["record"]["value"]).
           '</span>';
}
?>
