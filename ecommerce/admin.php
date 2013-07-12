<?php
/**
 * Pagina principale di amministrazione
 *
 * Un utente con i privilegi di amministrazione usera' questa pagina per poter
 * gestire i dati contenuti nella base dati. Tramite questa pagina sara'
 * quindi possibile:
 * - Visualizzare, inserire ed eliminare una categoria di prodotti
 * - Visualizzare, inserire ed eliminare i prodotti
 * - Visualizzare, disabilitare/abilitare ed eliminare gli utenti registrati
 * - Visualizzare i log dell'applicativo web
 * - Modificare le impostazioni del sito, quale il tema.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @filesource
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: admin.php 24 2009-09-08 10:33:59Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'Structures/DataGrid.php';
require_once 'HTML/Table.php';

/**
 * Tutte le funzionalita' offerte dagli 'Admin tools' sono ad esclusivo
 * appannaggio degli amministratori: in caso contrario effettuo un redirect
 * verso la pagina principale.
 */
needAuthenticatedUser(true);

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings        = $tr->getPage('admin', $_SESSION['lang']);
$headerAttribs  = array('bgcolor' => '#CCCCCC');
$evenRowAttribs = array('bgcolor' => '#EEEEEE');
$oddRowAttribs  = array('bgcolor' => '#FFFFFF');

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title']);
$page->printHead();
$page->printMenu();

/**
 * Controllo se e' stato specificato una sottopagina specifica
 */
if (isset($_GET['tool'])) {
    /**
     * Visualizzo l'elenco delle categorie
     */
    if ($_GET['tool'] == 'category') {
        echo '<table width="100%" class="central_box">';
        echo '<tr><td><h1 class="page_title">Categories</h1></td><tr>';
        echo '<tr><td>';
        $datagrid =& new Structures_DataGrid(20);
        $datagrid->addColumn(new Structures_DataGrid_Column('ID', 'cid', 'cid',
                                           array('width' => '50', 
                                           'align' => 'right'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('name', 'name', 
                                           'name', null, null));
        $datagrid->addColumn(new Structures_DataGrid_Column(null, null, null, 
                                            array('width' => '50'), null, 
                                           'printDeleteCat'));
        
        $options = array('dsn' => $dbURL);
        $datagrid->bind('SELECT * FROM categories', $options);

        $tableAttribs = array('align' => 'center', 'width' => '80%',
                              'cellspacing' => '0', 'cellpadding' => '4', 
                              'class' => 'datagrid');
        
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
    } else if ($_GET['tool'] == 'user') {
        /**
         * Visualizzo l'elenco degli utenti registrati al sito
         */
        echo '<table width="100%" class="central_box">';
        echo '<tr><td><h1 class="page_title">Registered users</h1></td><tr>';
        echo '<tr><td>';
        $datagrid =& new Structures_DataGrid(20);
        $datagrid->addColumn(new Structures_DataGrid_Column('uid', 'uid', 'uid', 
                                           array('width' => '50',
                                                 'align' => 'right'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('First name', 
                                           'first_name', 'first_name', 
                                           array('width' => '100'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('Last name', 
                                           'last_name', 'last_name', 
                                           array('width' => '100'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('Login', 'login', 
                                           'login', array('width' => '100', 
                                           'align'=>'center'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('Enabled', 'valid',
                                           'valid', array('align'=>'center', 
                                           'width' => '30'), null, 
                                           'printUserValidity'));
        $datagrid->addColumn(new Structures_DataGrid_Column('Admin', 'level',
                                           'level', array('align'=>'center', 
                                           'width' => '30'), null,
                                           'printUserLevel'));
        $datagrid->addColumn(new Structures_DataGrid_Column('Actions', null, 
                                           null, array('width' => '90', 
                                           'align'=>'center'), null,
                                           'printUserActions'));
        
        $options = array('dsn' => $dbURL);
        $datagrid->bind('SELECT * FROM users', $options);

        $tableAttribs = array('align' => 'center', 'width' => '100%',
            'cellspacing' => '0', 'cellpadding' => '4', 'class' => 'datagrid');
        
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
    } else if ($_GET['tool'] == 'product') {
        /**
         * Visualizzo il catalogo dei prodotti inseriti nello store
         */
        echo '<table width="100%" class="central_box">';
        echo '<tr><td><h1 class="page_title">Products</h1></td><tr>';
        echo '<tr><td>';
        $datagrid =& new Structures_DataGrid(15);
        $datagrid->addColumn(new Structures_DataGrid_Column('ID', 'pid', 'pid',
                                           array('width' => '20', '
                                           align' => 'right'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('Name', 'name', 'name',
                                           array('width' => '100'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('Availability', 
                                           'availability', 'availability', 
                                           array('width' => '40'), null));
        $datagrid->addColumn(new Structures_DataGrid_Column('Actions', null, 
                                           null, array('width' => '60', 
                                           'align'=>'center'), null, 
                                           'printProductActions'));
        
        $options = array('dsn' => $dbURL);
        $datagrid->bind('SELECT * FROM products', $options);

        $tableAttribs = array('align' => 'center', 'width' => '100%',
            'cellspacing' => '0', 'cellpadding' => '4', 'class' => 'datagrid');
        
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
    } 
} else {
    /**
     * Se non e' stata specificata alcuna sottopagina, visualizzo il menu di
     * amministrazione.
     */
    echo '<table width="100%" class="central_box">'.
         '<tr><td><h1 class="page_title">'.$tr->get('admin_tool', 'index').
         '</h1></td><tr><tr><td><a href="settings.php">'.
         $strings['settings'].'</a></td><tr>'.
         '<tr><td><a href="admin.php?tool=product">'.$strings['browse_prd'].
         '</a></td><tr><tr><td><a href="new_product.php">'.
         $strings['insert_prd'].'</a></td><tr>'.
         '<tr><td><a href="admin.php?tool=category">'.$strings['browse_cat'].
         '</a></td></tr>'.
         '<tr><td><a href="new_category.php">'.$strings['insert_cat'].
         '</a></td></tr>'.
         '<tr><td><a href="admin.php?tool=user">'.$strings['browse_users'].
         '</a></td></tr>'.
         '<tr><td><a href="transactions.php">'.$strings['trans_list'].
         '</a></td></tr>'.
         '<tr><td><a href="log_console.php">'.$strings['log_console'].
         '</a></td></tr>'.
         '<tr><td><a href="delete.php?obj=log">'.$strings['purge_logs'];
    echo <<<EOB
    </td></tr>
</table>
EOB;
} 

/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();

 
/**
 * Funzione di callback: produce, nell'ultima colonna della tabella delle
 * categorie, il link per eliminare la singola categoria.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printDeleteCat($params)
{
    return '<a href="delete.php?obj=cat&cid='. $params['record']['cid'].
           '"><img border="0" width="20" alt="delete" src="'.CONTENT_URL.
           '/images/delete.png"/></a>';
}

/**
 * Funzione di callback: stampa il valore 'yes' se l'utente risulta attivo.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printUserValidity($params)
{
    return $params['record']['valid']?'yes':'';
}

/**
 * Funzione di callback: stampa il valore 'yes' se l'utente ha i privilegi
 * di amministrazione.
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printUserLevel($params)
{
    return $params['record']['level']==0?'yes':'';
}

/**
 * Funzione di callback: azioni utente
 *
 * Produce, nell'ultima colonna della tabella degli utenti,
 * le possibili azioni da compiere (attivazione, disattivazione,
 * eliminazione).
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printUserActions($params)
{
    extract($params);
    $dis_value = $record['valid']?'dis':'ena';
    return '<a href="user.php?op='. $dis_value .'&uid='. $record['uid'] 
        .'"><img width="20" border="0" title="Enable/Disable" src="'. 
        CONTENT_URL .'/images/shutdown.png"/></a>&nbsp;'.
        '<a href="user.php?op=mail&login='. $record['login'] .
        '"><img width="20" border="0" title="Email activation code" '.
        'src="'.CONTENT_URL.'/images/mail.png"/></a>&nbsp;'.
        '<a href="delete.php?obj=user&uid='. $record['uid'] .
        '"><img width="20" border="0" title="Delete user" src="'. 
        CONTENT_URL .'/images/delete.png"/></a>';
}

/**
 * Funzione di callback: azioni prodotto
 *
 * Stampa, nell'ultima colonna della tabella dei prodotti, tutte le
 * le possibili azioni da compiere (incrementare/decrementare la
 * disponibilita', eliminazione)
 *
 * @param array $params Vettore associativo contenente il record corrente
 *
 * @return La stringa da stampare nella cella della tabella
 */
function printProductActions($params)
{
    extract($params);
    return '<a title="Increase" href="product.php?op=inc&pid='. $record['pid'] .'">'.
           '<img width="20" border="0" alt="increase" src="'. 
           CONTENT_URL. '/images/plus.gif"/></a>&nbsp;'.
           '<a title="Decrease" href="product.php?op=dec&pid='. $record['pid'] .'">'.
           '<img width="20" border="0" alt="decrease" src="'.CONTENT_URL.
           '/images/minus.gif"/></a>&nbsp;'.
           '<a title="Delete" href="product.php?op=del&pid='. $record['pid'] .'">'.
           '<img width="20" border="0" alt="delete" src="'.CONTENT_URL.
           '/images/delete.png"/></a>'.
           '<a title="Edit" href="edit_product.php?pid='.$record['pid']. '">'.
           '<img width="20" border="0" alt="edit" src="'.CONTENT_URL.
           '/images/edit.gif"/></a>';
}

?>

