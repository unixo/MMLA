<?php
/**
 * Elenco transazioni effettuate
 *
 * L'amministratore del sito, tramite questa pagina, potra' visualizzare la
 * lista delle transazioni effettuate dagli utenti; per ogni voce sara' presente
 * il dettaglio dell'utente che ha effettuato l'ordine, il prodotto selezionato,
 * la quantita' e l'importo complessivo.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: transactions.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 * @see      admin.php
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'Structures/DataGrid.php';
require_once 'HTML/Table.php';

/**
 * Tutte le funzionalita' offerte dagli "Admin tools" sono ad esclusivo
 * appannaggio degli amministratori: in caso contrario effettuo un redirect
 * verso la pagina principale.
 */
needAuthenticatedUser(true, 'index.php');

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('trans', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title']);
$page->printHead();
$page->printMenu();

/**
 * Creo una tabella con l'elenco delle transazioni finora effettuate; aggiungo
 * un pager in coda.
 */
echo '<table width="100%" class="central_box">'.
     '<tr><td><h1 class="page_title">'.$strings['page_title'].
     '</h1></td><tr><tr><td>';
$datagrid =& new Structures_DataGrid(15);
$datagrid->addColumn(new Structures_DataGrid_Column('Date', 'date', 'date',
                                   array('width' => '80', '
                                   align' => 'center'), null, 'printTransDate'));
$datagrid->addColumn(new Structures_DataGrid_Column('User', 'user', 'user',
                                   array('width' => '90'), null));
$datagrid->addColumn(new Structures_DataGrid_Column('Product', 
                                   'name', 'name', 
                                   array('width' => '160'), null,
                                   'printProductName'));
$datagrid->addColumn(new Structures_DataGrid_Column('count', 'count', 
                                   null, array('width' => '30', 
                                   'align'=>'center'), null));
$datagrid->addColumn(new Structures_DataGrid_Column('total', 'total', 
                                   null, array('width' => '60', 
                                   'align'=>'right'), null, 'printTotal'));


$options = array('dsn' => $dbURL);
$datagrid->bind("SELECT T.date, CONCAT(U.first_name, ' ', U.last_name) AS user,".
                " P.pid, P.name, T.count, T.total FROM transactions T, users U,".
                " products P WHERE U.uid = T.uid and T.pid = P.pid ORDER BY tid", 
                $options);

/**
 * Proprieta' della tabella da stampare e colori delle righe della
 * tabella stessa: vengono definiti due colori, uno per le righe pari
 * ed un secondo per quelle dispari.
 */
$tableAttribs   = array('align' => 'center', 'width' => '100%', 
                        'cellspacing' => '0', 'cellpadding' => '4', 
                        'class' => 'datagrid');
$evenRowAttribs = array('bgcolor' => '#F7F7F7', 'style' => 'height:80px');
$oddRowAttribs  = array('bgcolor' => '#FFFFFF', 'style' => 'height:80px');

/**
 * Creo la tabella tramite PEAR con gli attributi appena definiti.
 */
$table       = new HTML_Table($tableAttribs);
$tableHeader =& $table->getHeader();
$tableBody   =& $table->getBody();

$rendererOptions = array('sortIconASC' => '&uArr;', 
                         'sortIconDESC' => '&dArr;');
$datagrid->fill($table, $rendererOptions);

$tableHeader->setRowAttributes(0, $headerAttribs);
$tableBody->altRowAttributes(0, $evenRowAttribs, $oddRowAttribs, true);
echo $table->toHtml();
echo '<br/><center>';
$log_render = $datagrid->render(DATAGRID_RENDER_PAGER);
echo '</center><br/></td></tr></table>';

/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();


/**
 * Funzione di callback: ora e data
 *
 * Stampa la data dell'evento, formattando il valore come 
 * giorno/mese/anno ore:minuti.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printTransDate($params)
{
    extract($params);
    return date_format(date_create($record['date']), 'd/m/Y H:i');
}


/**
 * Funzione di callback: nome prodotto
 *
 * Stampa il nome del prodotto, in modo tale che sia un link alla pagina di
 * modifica della scheda del prodotto stesso.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printProductName($params)
{
    return '<a href="'.CONTENT_URL.'/edit_product.php?pid='.
           $params['record']['pid']. '">'.
           html_entity_decode($params['record']['name']) . '</a>';
}


/**
 * Funzione di callback: Totale transazione
 *
 * Stampa, nell'ultima colonna della tabella, il totale della transazione.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printTotal($params)
{
    return currency($params['record']['total']);
}

?>