<?php
/**
 * Console eventi
 *
 * Questa pagina viene acceduta direttamente dagli 'Admin Tools' e mostra a
 * video il log degli eventi registrati: le operazioni di autenticazioni o
 * modifica della base dati, sono esempi di attivita' che generano una voce
 * nel log.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: log_console.php 26 2009-09-08 10:36:06Z unixo $
 * @link     http://commerce.devzero.it
 * @see      admin.php
 * @todo     Completare la lista delle priorita'
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
 * Istanzio un nuovo oggetto Page, vi assegno il titolo e stampo il menu ed il
 * blocco di sinistra contenente il menu.
 */
$page = new FV_Page('Admin tools');
$page->printHead();
$page->printMenu();
?>

<table width="100%" class="central_box">
<tr><td><h1 class="page_title">Log console</h1></td></tr>
<tr><td>
    <?php
        date_default_timezone_set('Europe/Rome');
        $datagrid =& new Structures_DataGrid(25);
        $datagrid->addColumn(new Structures_DataGrid_Column("date", "logtime", 
                                    "logtime", array('width' => '100', 
                                    'align' => 'center'), null, 'printDate'));
        $datagrid->addColumn(new Structures_DataGrid_Column("level", "priority", 
                                    "priority", array('align' => 'center', 
                                    'width' => '90'), null, 'printPriority'));
        $datagrid->addColumn(new Structures_DataGrid_Column("event", "message", 
                                    null, array('width' => '250'), null));
        
        $options = array('dsn' => $dbURL);
        $datagrid->bind("SELECT * FROM log_table ORDER BY logtime DESC", 
                        $options);

        $tableAttribs   = array('align' => 'center', 'width' => '100%',
            'cellspacing' => '0', 'cellpadding' => '4', 'class' => 'datagrid');
        $headerAttribs  = array('bgcolor' => '#CCCCCC');
        $evenRowAttribs = array('bgcolor' => '#EEEEEE');
        $oddRowAttribs  = array('bgcolor' => '#FFFFFF');
        
        $table       =  new HTML_Table($tableAttribs);
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
 * Funzione di callback: ora e data
 *
 * Stampa la data dell'evento, formattando il valore come 
 * giorno/mese/anno ore:minuti.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printDate($params)
{
    extract($params);
    return date_format(date_create($record['logtime']), 'd/m/Y H:i');
}

/**
 * Funzione di callback: priorita'
 *
 * Stampa una stringa corrispondente al livello di criticita' dell'evento.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printPriority($params)
{
    extract($params);
    switch ((int) $record["priority"]) {
    case PEAR_LOG_INFO: return "info";
    case 5: return "notice";
    case PEAR_LOG_WARNING: return "warning";
    default: return "unknown";
    }
}

?>

