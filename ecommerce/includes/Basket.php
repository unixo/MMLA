<?php
/**
 * Classe FV_Basket
 *
 * PHP Version 5
 *
 * @category Class
 * @package  UrbinoCommerce
 * @filesource
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: Basket.php 17 2009-08-27 05:51:12Z unixo $
 * @link     http://commerce.devzero.it
 */
/**
 * Classe FV_Basket
 *
 * L'oggetto FV_Basket rappresenta il carrello dell'utente corrente. La classe
 * non deve essere istanziata direttamente, ma solo a seguito di una corretta
 * fase di autenticazione e deve essere creata da parte della classe FV_User.
 * (il prefisso FV_ e' utile per evitare conflitti di nomi)
 *
 * Esempio d'utilizzo della classe
 * <code>
 * require_once 'Basket.php';
 *
 * $bsk = new FV_Basket('1');
 * $bsk->add(10);
 * echo 'Items present in basket: '. $bsk->itemsCount();
 * $bsk->remove(10);
 * </code>
 *
 * @category Class
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link     http://commerce.devzero.it
 * @see      FV_User
 * @uses     executeQuery API per leggere le informazioni sui prodotti
 */
class FV_Basket
{
    const VAT = 20;
    private $_uid;
    private $_items;
    
    /**
     * Costruttore di default della classe.
     *
     * @param integer $user Identificativo dell'utente.
     */
    function __construct($user)
    {
        $this->_items = array();
        $this->_uid   = $user;
    }
    
    /**
     * Restituisce il vettore degli oggetti contenuto nel carrello.
     *
     * @return array Vettore degli oggetti del carrello
     */
    function items()
    {
        return $this->_items;
    }
    
    /**
     * Restituisce il numero di oggetti presenti nel carrello; questo valore
     * non e' necessariamente pari al numero di oggetti contenuti nel vettore
     * {@link items} (Se aggiungo due volte lo stesso prodotto, sara' presente
     * solo una voce nel vettore, ma con il valore count pari a due).
     *
     * @return integer Numero di oggetti nel carrello
     */
    function itemsCount()
    {
        $count = 0;
        foreach ($this->_items as $item) {
            $count += $item['count'];
        }
        return $count;
    }
    
    /**
     * Restituisce l'importo totale del carrello, pari alla somma di tutti i
     * prodotti inseriti, senza IVA: il calcolo e' ottenuto iterando sulle
     * voci presenti nel vettore _items e moltiplcando il costo unitario (price)
     * per la quantita' del singolo prodotto (count)
     *
     * @return integer Importo totale del carrello senza IVA
     */
    function total()
    {
        $tot = 0;
        if (count($this->_items) > 0) {
            foreach ($this->_items as $item) {
                $tot += ($item['count'] * $item['price']);
            }
        }
        return $tot;
    }
    
    /**
     * Restituisce l'importo dell'IVA al totale del carrello. L'aliquota e'
     * determinata dalla costante 'IVA'.
     *
     * @return integer Importo dell'IVA
     */
    function totalVAT()
    {
        $tot = $this->total();
        return ($tot * FV_Basket::VAT)/100;
    }
    
    /**
     * Svuota il carrello dell'utente chiamante
     *
     * @return void
     */
    function emptyBasket()
    {
        $this->_items = array();
    }
    
    /**
     * Aggiunge un prodotto, identificato dal suo ID, al carrello dell'utente
     * chiamante; se il prodotto risulta gia' presente all'interno del
     * carrello, viene incrementato soltanto il valore 'count' della voce
     * esistente.
     * 
     * @param integer $pid Identificativo del prodotto
     *
     * @return void
     * @throws PEAR_Exception Viene generata qualora il prodotto non esista o
     *                        se il parametro non e' del tipo corretto
     */
    function add($pid)
    {
        $my_pid = htmlentities($pid);
        if (!ctype_digit($my_pid)) {
            throw new PEAR_Exception('Invalid product number', ERR_PARAM);
        }
        
        $results = executeQuery('SELECT * FROM all_products '.
                                 'WHERE pid=? AND availability>0', $pid);
        if (count($results) === 0) {
            throw new PEAR_Exception('Noexistent or not available product', 
                                     ERR_BASKET);
        }

        $row   = $results[0];            
        $found = false;
        foreach ($this->_items as $i => $item) {
            if ($item['pid'] === $pid) {
                $found = true;
                $this->_items[$i]['count']++;
                break;
            }
        }
        if (!$found) {
            $row["count"] = 1;
            array_push($this->_items, $row);
        }
    }
    
    /**
     * Rimuove dal carrello il prodotto identificato dal parametro.
     * 
     * @param integer $pid Identificativo del prodotto
     *
     * @return boolean Restituisce true se il prodotto e' stato rimosso
     * @throws PEAR_Exception Viene generata qualora il codice non sia valido
     */
    function remove($pid)
    {
        $my_pid = htmlentities($pid);
        if (!ctype_digit($my_pid)) {
            throw new PEAR_Exception('Invalid product number', ERR_PARAM);
        }
        foreach ($this->_items as $i => $item) {
            if ($item['pid'] == $pid) {
                unset($this->_items[$i]);
                return true;
            }
        }
        return false;
    }
}

?>