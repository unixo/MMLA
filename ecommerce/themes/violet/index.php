<?php
/**
 * Pagina principale [violet]
 *
 * Homepage del sito, tema "violet".
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: index.php 10 2009-08-25 15:06:34Z unixo $
 * @link     http://commerce.devzero.it
 */
/**
 */

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings  = $tr->getPage('admin', $_SESSION['lang']);
$featured = executeQuery('SELECT * FROM products ORDER BY rating DESC LIMIT 1');
$products = executeQuery('SELECT * FROM products ORDER BY pid DESC LIMIT 6');
?>

<div class="content">
    <div id="feature">
       <div class="image">
         <a href="detail.php?pid=1">
         <img border="0" width="130" alt="product image"
              src="<?php echo CONTENT_URL; ?>/uploads/
              <?php echo $featured[0]['image']; ?>"/>
         </a>
       </div>
       <div class="description">
         <h3><?php echo $featured[0]['name']; ?></h3>
         <?php echo substr($featured[0]['descr'], 0, 100); ?>
         <br/>
       </div>
    </div>


<?php
for ($i=0; $i<6; $i++) {
    echo '<div class="product-box">'. "\n" .'<h3>Featured item</h3>'. "\n".
         '   <div class="product-image">' . "\n";
    echo '<a href="detail.php?pid='. $products[$i]['pid'] .'">'.
         '<img width="120" border="0" src="' . CONTENT_URL . '/uploads/'. 
         $products[$i]['image'] . '" alt="product image"/></a></div>';
    echo '<div class="product-title">' . "\n".
         '<a href="detail.php?pid='. $products[$i]['pid'] .'">' .
         $products[$i]['name'] . '</a>'.
         '<h2>'. $tr->get('price', 'new_product') . '&nbsp;&euro;&nbsp;'. 
         currency($products[$i]['price']) . '</h2>'.
         '<img border="0" alt="add to cart" '.
         'src="'. THEME_IMAGES. 'add-to-cart-purp.jpg"/>'.
         '</div>' . "\n";
    echo '</div>';
}
?>

</div>