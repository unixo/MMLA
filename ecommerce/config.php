<?php
/**
 * File di configurazione del sito.
 *
 * Il file 'config.php' contiene diverse costanti che determinano il 
 * funzionamento dell'intero sito, tra cui le credenziali di connessione alla
 * base dati.
 *
 * PHP Version 5
 *
 * @category Common
 * @package  UrbinoCommerce
 * @filesource
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: config.php 32 2009-09-08 14:02:18Z unixo $
 * @link     http://commerce.devzero.it
 */

@session_start();

/**
 * Disabilito la generazione degli errori sul browser: evito qualunque sorta
 * di information leackage.
 */
error_reporting(E_ALL);
ini_set('display_errors', false);
ini_set('html_errors', false);


// {{{ constants
/**
 * Costante: errore nel carrello utente
 * @name ERR_BASKET
 */	
define('ERR_BASKET', 10);
/**
 * Costante: errore nei parametri passati
 * @name ERR_PARAM
 */
define('ERR_PARAM', 20);
/**
 * Costante: errore durante la comunicazione con il database
 * @name ERR_DATABASE
 */
define('ERR_DATABASE', 30);
/**
 * Configurazione: Feed RSS dei prodotti
 * @name CONFIG_RSS
 */
define('CONFIG_RSS', 'rss');
/**
 * Configurazione: Nome del tema corrente
 * @name CONFIG_THEME
 */
define('CONFIG_THEME', 'theme');
/**
 * Configurazione: Localizzazione attiva
 * @name CONFIG_LANG
 */
define('CONFIG_LANG', 'lang');
/**
 * Configurazione: Server SMTP
 *
 * FQDN o indirizzo IP del server SMTP per l'invio di mail
 * @name CONFIG_SMTP_SERVER
 */
define('CONFIG_SMTP_SERVER', 'smtp_host');
/**
 * Configurazione: Porta TCP del server SMTP
 *
 * Porta del server SMTP per l'invio di mail (il default e' 25)
 * @name CONFIG_SMTP_PORT
 */
define('CONFIG_SMTP_PORT', 'smtp_port');
/**
 * Configurazione: Username per SMTP autenticato
 * @name CONFIG_SMTP_USER
 */
define('CONFIG_SMTP_USER', 'smtp_user');
/**
 * Configurazione: Password per SMTP autenticato
 * @name CONFIG_SMTP_PASS
 */
define('CONFIG_SMTP_PASS', 'smtp_pass');
/**
 * Configurazione: Stringa di HELO per server SMTP
 *
 * Questa stringa viene inviata quando un'istanza della classe Mail apre una 
 * connessione verso il server SMTP.
 * @name CONFIG_SMTP_HELO
 */
define('CONFIG_SMTP_HELO', 'smtp_helo');
// }}}


/**
 * URL per la connessione alla base dati.
 * @global string $dbURL 
 * @name $dbURL
 */
$dbURL = 'mysql://dbusername:dbpassword@dbserver/dbname';


/**
 * NON MODIFICARE IL CODICE SOTTOSTANTE
 */

/**
 * Istanzio un oggetto MDB2 per la connessione al database: uso connessioni
 * persistenti al database per migliorare le prestazioni; attivo inoltre 
 * l'opzione di racchiudere tra virgolette gli identificatori per evitare
 * possibili SQL Injection.
 */
$mdb2_options = array('persistent' => true, 'quote_identifier' => true);
$db_conn      =& MDB2::factory($dbURL, $mdb2_options);
if (PEAR::isError($db_conn)) {
    die('Error while connecting : ' . $db_conn->getMessage());
}


/**
 * Creo un'istanza dell'oggetto Log per registrare nel database gli eventi
 * generati dagli utenti autenticati. Passo al costruttore un vettore di
 * configurazione, con il riferimento al DSN del database.
 */
$conf['dsn'] = $dbURL;
$logger      = &Log::singleton('sql', 'log_table', 'ident', $conf);

// {{{ constants

/**
 * Percorso fisico del filesystem in cui e' installato il sito
 */
if (!defined('ABSPATH') ) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

if (dirname($_SERVER['SCRIPT_NAME']) == '/') {
    /**
     * URL del sito
     * @name CONTENT_URL
     */
    define('CONTENT_URL', '');
} else {
    /**
     * @ignore 
     */
    define('CONTENT_URL', dirname($_SERVER['SCRIPT_NAME']));
}
   
/**
 * Leggo dal database il nome del tema attualmente in uso e definisco delle
 * costanti che puntano al percorso fisico del tema, nonché la directory
 * contenente le immagini del tema selezionato.
 */ 
$result = executeQuery("SELECT value FROM config WHERE attrib='theme'");
/**
 * Nome del tema corrente
 * @name THEME
 */
define('THEME', $result[0]['value']);
/**
 * Percorso fisico dove sono presenti i temi
 * @name THEMES_PATH
 */
define('THEMES_PATH', ABSPATH . 'themes/');
/**
 * Percorso fisico del tema corrente
 * @name THEME_PATH
 */
define('THEME_PATH', THEMES_PATH . THEME . '/');
/**
 * URL del tema corrente
 * @name THEME_BASEURL
 */
define('THEME_BASEURL', CONTENT_URL . '/themes/' . THEME);
/**
 * URL delle immagini del tema corrente
 * @name THEME_IMAGES
 */
define('THEME_IMAGES', CONTENT_URL . '/themes/' . THEME . '/images/');
/**
 * URL dei file di stile CSS del tema corrente
 * @name THEME_CSS
 */
define('THEME_CSS', CONTENT_URL . '/themes/' . THEME . '/css/');

// }}}

/**
 * Descrivo i parametri per inizializzare il motore di traduzione
 */
$params = array(
  'langs_avail_table'     => 'langs',
  'lang_id_col'           => 'id',
  'lang_name_col'         => 'name',
  'lang_meta_col'         => 'meta',
  'lang_errmsg_col'       => 'error_text',
  'lang_encoding_col'     => 'encoding',  
  'strings_default_table' => 'strings',
  'string_id_col'         => 'string_id',
  'string_page_id_col'    => 'page_id',
  'string_text_col'       => '%s',
);

/**
 * Inizializzo il motore di traduzione: imposto la lingua di base e verifico
 * se la sessione corrente ha gia' una preferenza di lingua.
 */
$tr =& Translation2::factory('MDB2', $dbURL, $params);
$tr->setCharset('utf8');
if (!isset($_SESSION['lang'])) {
    $tr->setLang('en');
    $_SESSION['lang'] = 'en';
} else {
    $tr->setLang($_SESSION['lang']);  
}

?>