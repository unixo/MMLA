<?php
/**
 * Suggerimento ricerca
 *
 * Il campo ricerca in alto a dx sfrutta questa pagina per effettuare, in
 * tempo reale, una ricerca tra i prodotti: i risultati vengono proposti a
 * video, in un DIV, prima ancora che l'utente abbia premuto invio.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: search.php 9 2009-08-25 15:05:48Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';

/**
 * Invio prima di tutto gli header al browser: si tratta di un file formattato
 * in XML, con data antecedente a quella odierna; non dovra' essere conservata
 * nella cache del browser.
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/xml");

/**
 * Il parametro 'hint', passato via GET, verra' usato come filtro per la query
 */
$hint    = '%'.htmlentities($_GET['hint']).'%';
$records = executeQuery('SELECT * FROM all_products WHERE name like ? LIMIT 25', 
                        $hint);

/**
 * Formatto il result set in XML; nel caso di una ricerca che non ha prodotto
 * risultati, verra' restituito solo un feed vuoto, composto solo dal tag
 * 'results'.
 */
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
for ($i=0; $i<count($records); $i++) {
    echo '<rs id="'.$records[$i]['pid'].'" info="'.$records[$i]['cat_name'].
         '">'. $records[$i]['name'] ."</rs>\n";
}
echo '</results>';
?>