<?php
/**
 * Elenco funzioni comuni
 *
 * Il file 'common.php' viene importato da tutte le pagine del sito, in modo
 * tale da ereditare tutte le variabili globali definite nel file 'config.php'
 * e le definizioni di classi e funzioni.
 *
 * PHP Version 5
 *
 * @category Common
 * @package  UrbinoCommerce
 * @filesource
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: common.php 32 2009-09-08 14:02:18Z unixo $
 * @link     http://commerce.devzero.it/
 */
require_once 'MDB2.php';
require_once 'Log.php';
require_once 'Translation2.php';
require_once 'PEAR/Exception.php';
require_once dirname(__FILE__).'/Basket.php';
require_once dirname(__FILE__).'/User.php';
require_once dirname(__FILE__).'/Page.php';
require_once dirname(__FILE__).'/../config.php';


/**
 * Handler eccezioni
 *
 * Registra la funzione 'errorHandler' come gestore delle eccezioni di tipo
 * PEAR_Exception.
 */
PEAR_Exception::addObserver('errorHandler');

/**
 * Handler eccezioni
 *
 * La funzione viene richiamata qualora venga sollevata un'eccezione di tipo
 * PEAR_Exception o sue derivate: compito della funzione e' redirigere la 
 * navigazione dell'utente verso la pagina d'errore contenente il messaggio
 * dell'eccezione.
 *
 * @param PEAR_Exception $pear_exception Istanza dell'eccezione
 *
 * @return void
 */
function errorHandler($pear_exception)
{
    header('Location: error.php?msg='.$pear_exception->getMessage());
}

/**
 * Controllo autenticazione ed autorizzazione
 *
 * La funzione verifica se l'utente che ha effettuato la richiesta risulta
 * essere autenticato e quindi autorizzato a vedere il contenuto della pagina.
 * Il parametro needAdmin eleva ulteriomente il livello di protezione: qualora
 * valga true, l'utente deve essere sia autenticato che amministratore.
 * Qualora non vengano soddisfatte le suddette condizioni, viene effettuata
 * una redirezione della navigazione verso la URL specificata.
 *
 * @param boolean $needAdmin Se vale true, l'utente deve essere un amministratore
 * @param string  $link      Pagina verso la quale effettuare la redirezione
 *
 * @return void
 */
function needAuthenticatedUser($needAdmin = false, $link = 'index.php')
{
    if (isset($_SESSION['user']) === false) {
        redirectToURL($link);
    }
    if ($_SESSION['user']->isDummy() === true) {
        redirectToURL($link);
    }
    $isAdmin = $_SESSION['user']->isAdmin();
    if (($needAdmin === true) && ($isAdmin === false)) {
        redirectToURL($link);
    }
}

/**
 * Wrapper per interfacciarsi al database tramite istruzioni SQL
 * 
 * E' possibile effettuare una SELECT o una DML (UPDATE, INSERT). All'interno
 * della funzione viene fatto uso delle classi DB2 (PEAR) per creare lo
 * statement e, eventualmente, assegnare i valori alle rispettive
 * 'bind variables'. 
 * L'uso di questi accorgimenti previene possibili attacchi di tipo SQL
 * injection o XSS stored.
 *
 * Esempio d'utilizzo:
 * <code>
 * $results = executeQuery('SELECT * FROM tabella WHERE campo=?', $valore);
 * echo 'Numero di record trovati: '.count($results);
 * </code>
 *
 * @param string $sql    Comando SQL da eseguire
 * @param array  $values Valore delle bind variables
 *
 * @return array Result set ottenuto dalla query (se fosse una SELECT)
 */
function executeQuery($sql, $values = array())
{
    global $db_conn;

    $results = array();
    if (sizeof($values) > 0) {
        $statement = $db_conn->prepare($sql, null, MDB2_PREPARE_RESULT);
        $resultset = $statement->execute($values);
        $statement->free();
    } else {
        $resultset = $db_conn->query($sql);
    }
    if (PEAR::isError($resultset) === true) {
        throw new PEAR_Exception($resultset->getMessage(), ERR_DATABASE);
    }

	if (substr(strtolower($sql), 0, 6) === 'select') {
	    while (($row = $resultset->fetchRow(MDB2_FETCHMODE_ASSOC)) !== null) {
	        $results[] = $row;
	    }
	}
    $resultset->free();

    return $results;
}

/**
 * Formattazione valuta
 *
 * Formatta un numero usando la virgola come separatore decimale, il punto
 * come separatore delle migliaia e con due caratteri decimali.
 *
 * @param integer $num Numero da formattare
 *
 * @return integer Numero formattato
 */
function currency($num)
{
    return number_format($num, 2, ",", ".");
}


/**
 * Valutazione del prodotto
 *
 * Stampa la valutazione di un prodotto/recensione in una scala da 1 a 5: fa
 * uso di una particolare immagine a forma di 'stella', da ripetere fino a 
 * rappresentare il valore di rating.
 *
 * @param integer $value Valore di rating
 * @param boolean $print Se vale true, stampa il rating in output
 * @param integer $size  Dimensione dell'immagine (width)
 *
 * @return string Restituisce la stringa HTML del rating
 */
function printRating($value, $print = true, $size = 19)
{
    $html = "";
    for ($i=0; $i<$value; $i++) {
        $html .= '<img width="'.$size.'" src="'.CONTENT_URL.
                 '/images/blue_star.gif" alt="bstar"/>';
    }
    for (; $i<5; $i++) {
        $html .= '<img width="'.$size.'" src="'.CONTENT_URL.
                 '/images/gray_star.gif" alt="bstar"/>';
    }
        
    if ($print) {
        echo $html;
    }
    return $html;
}

/**
 * Generazione ID univoco
 *
 * Genera un identificativo univoco da associare all'utente che ha appena
 * terminato la procedura di registrazione: tale codice viene inviato via
 * mail all'indirizzo di posta specificato dall'utente, il quale, visitando il
 * link indicato, provvedera' ad attivare l'account.
 *
 * @return string Codice univoco
 */
function generateSecureCode()
{
    return md5(uniqid(mt_rand(), true));
}


/**
 * Generazione password
 *
 * Quando un'utente dimentica la propria password d'accesso, il sistema puo'
 * generare una nuova password che sia comunque conforme alle linee guida
 * del sito. La password verra' dunque inviata via mail all'utente.
 *
 * @param integer $length Lunghezza della password da generare
 *
 * @return string Password generata
 */
function generatePassword($length = 8)
{
    $chars    = 'zaqwsxcderfvbgtyhnmjuiklop0147852369_-.#$%@'.
                'QAZWSXEDCRFVTGBYHNUJMIKLOP';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
         $password .= $chars[(rand() % strlen($chars))];
    }
    return $password;
}


/**
 * Funzione di redirezione
 *
 * Funzione per redirigere la navigazione dell'utente alla pagina richiesta:
 * l'operazione viene effettuata inviando al browser l'header 'Location' e
 * terminando successivamente lo script con la funzione exit(), in modo tale
 * che non venga generato ulteriore output.
 *
 * @param string $url Pagina da visitare
 *
 * @return void
 */
function redirectToURL($url)
{
    header('Location: '.SERVER_URL.$url);
    exit();
}


/**
 * Lettura parametro di configurazione
 *
 * Dato un parametro di configurazione salvato nella tabella 'config' della
 * base dati, la funzione ne restituisce il valore; se il parametro contiene
 * il carattere '%', la funzione restituisce un vettore di parametri che
 * corrispondono all'espressione regolare.
 *
 * @param string $attrib Parametro da ricercare
 *
 * @return mixed Valore del parametro o vettore di valori
 */
function getConfigValue($attrib)
{
    if (strstr($attrib, '%')) {
        $sql     = 'SELECT * FROM config WHERE attrib LIKE ?';
        $records = executeQuery($sql, $attrib);
        foreach ($records as $row) {
            $results[$row['attrib']] = $row['value'];
        }
        return $results;
    } else {
        $sql     = 'SELECT * FROM config WHERE attrib = ?';
        $records = executeQuery($sql, $attrib);
        return $records[0]['value'];
    }
}


/**
 * Impostazione parametro di configurazione
 *
 * Dato un parametro di configurazione salvato nella tabella 'config' della
 * base dati, la funzione ne aggiorna il valore.
 *
 * @param string $attrib Parametro da modificare
 * @param string $value  Valore del parametro
 *
 * @return void
 */
function setConfigValue($attrib, $value)
{
    executeQuery('UPDATE config SET value=? WHERE attrib=?',
                 array($value, $attrib));
}


/**
 * Controllo esistenza istanza FV_User
 *
 * Diverse pagine del sito necessitano di un'istanza della classe FV_User, sia
 * che si tratti di un utente regolarmente autenticato che di un visitatore
 * che sta visionando il catalogo e inserisce i prodotti nel carrello senza
 * essere ancora identificato/registrato.
 *
 * @return void
 */
function checkSessionUser()
{
    if (!isset($_SESSION['user'])) {
        $_SESSION['user'] =& FV_User::dummyUser();
    }
}


/**
 * Impostazioni SMTP
 *
 * Le classi o le pagine che necessitano di inviare una mail faranno uso di
 * questa funzione per ottenere le impostazioni del server SMTP.
 * Il vettore associativo restituito conterra' il nome del server, le
 * credenziali per l'smtp-auth e la stringa di HELO da inviare.
 *
 * @return mixed Vettore associativo contenente i parametri del server SMTP
 */
function getSMTPSettings()
{
    $smtp['host']      = getConfigValue('smtp_host');
    $smtp['auth']      = true;
    $smtp['username']  = getConfigValue('smtp_user');
    $smtp['password']  = getConfigValue('smtp_pass');
    $smtp['localhost'] = getConfigValue('smtp_helo');
    
    return $smtp;
}

?>
