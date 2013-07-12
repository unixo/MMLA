<?php
/**
 * Pagina principale [default]
 *
 * Homepage del sito, tema di default.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link     http://www.dezero.it/ecommerce/index.php
 */
?>
<table border="0" class="central_box">
<?php
$count   = 7;
$columns = 2;
$max     = 2;
$start   = 2;

$items = executeQuery('SELECT * FROM products LIMIT ?', $count);
if (count($items) === 0) {
    echo '<tr><td align="center">no products have been loaded '.
         'into store yet</td></tr>';
} else {
    echo '<tr><td colspan="2"><h1 class="page_title">'.
         $tr->get('from_store', 'main').'</h1></td></tr>';
    echo '<tr><td colspan="'. $columns .'" height="30">&nbsp;</td></tr>'. 
         "\n";
    for ($row=0; $row < 3; $row++) {
        echo "\t<tr>";
        for ($col=0; $col < $columns; $col++) {
            $item = $items[$row+$col];
            echo '<td align="center" width="20%" class="showcell">'.
                 '<a href="detail.php?pid=' . $item["pid"] . '">'.
                 '<img alt="product detail" border="0" width="90" src="'. 
                 CONTENT_URL . '/uploads/'. $item["image"] . '"/></a></td>';
        }
        echo "</tr>\n\t<tr>";
        for ($col=0; $col < $columns; $col++) {
            $item = $items[$row+$col];
            echo '<td class="showcell"><a href="detail.php?pid='.
                 $item["pid"] . '">' . $item["name"] . '</a></td>';
        }
        echo "</tr>\n\t<tr>";
        for ($col=0; $col < $columns; $col++) {
            $item = $items[$row+$col];
            echo '<td class="showcell_price"> &euro; '.
                 currency($item["price"]) .'</td>';
        }
        echo "</tr>\n\t<tr>". '<td colspan="'.$columns.
             '" height="35">&nbsp;</td></tr>';
    }
}       
?>  
</table>
