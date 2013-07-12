<?php
/**
 * Classe FV_user
 *
 * PHP Version 5
 *
 * @category Class
 * @package  UrbinoCommerce
 * @filesource
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: User.php 17 2009-08-27 05:51:12Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once 'Mail.php';

/**
 * Classe FV_User
 *
 * L'oggetto FV_User rappresenta un utente contenuto nella base dati.
 *
 * Esempio d'utilizzo della classe
 * <code>
 * require_once 'User.php';
 *
 * if (FV_User::validateUser('username', 'password')) {
 *     $user =& $_SESSION['user'];
 *     if ($user->isAdmin() === true) {
 *         echo "Logged user is an administrator\n";
 *     }
 *     $user->basket->add(12);
 *     $user->logout();
 * }
 * </code>
 *
 * @category Class
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link     http://commerce.devzero.it
 * @uses     FV_Basket Rappresentazione del carrello utente
 * @uses     executeQuery API per interfacciarsi con il database
 * @uses     getSMTPSettings Per ottenere le impostazione del server SMTP per
 *           inviare mail all'utente
 */
class FV_User
{
    const DUMMY_LOGIN        = "johndoe";
    const PASSWD_OK          =  1;
    const PASSWD_INVALIDCHAR = -1;
    const PASSWD_NOTVARIETY  = -2;
    const PASSWD_TOOSHORT    = -3;
    /**
     * Nome dell'utente
     */
    public $firstName;
    /**
     * Cognome dell'utente
     */
    public $lastName;
    /**
     * Indirizzo email
     */
    public $email;
    /**
     * Nome utente (prima parte delle credenziali d'accesso)
     *
     * @see $pass
     */
    public $login;
    /**
     * Password (seconda parte delle credenziali d'accesso)
     *
     * @see $login
     */
    public $pass;
    public $address, $cap, $city, $state;
    /**
     * Abilitazione all'accesso: se vale uno, l'account e' stato attivato e
     * l'utente puo' regolarmente accedere al sito.
     */
    public $valid;
    /**
     * Istanza della classe FV_Basket: rappresenta il carrello dell'utente
     *
     * @see FV_Basket
     */
    public $basket;
    /**
     * Identificativo utente (uid): tale valore e' univoco all'interno della
     * tabella 'users.
     */
    public $uid;
    /**
     * Livello di privilegio: se vale uno, l'utente e' un amministratore
     */
    private $_level;
    private $_securecode;
    private $_dummy;
    
    /**
     * La funzionalita' di clone di una classe non e' ammessa.
     *
     * @return void
     * @access public
     */
    public function __clone()
    {
        trigger_error('Clone is not allowed for this class', E_USER_ERROR);
    }

    /**
     * Rappresentazione della classe sotto forma di stringa.
     *
     * @return string Descrizione dell'istanza
     * @access public
     */
    public function __toString()
    {
        return 'Class FV_User ('.
               isset($this->firstName)?$this->firstName:'invalid'.')';
    }

    /**
     * Controllo login
     *
     * La funzione si occupa di verificare se il login specificato come argomento
     * e' stato gia' utilizzato da qualche altro utente: tale valore, infatti,
     * deve essere univoco all'interno della tabella 'users'.
     *
     * @param string $user Login da verificare
     *
     * @return boolean Restituisce true se la login e' gia' in uso
     * @access public
     * @static
     */
    public static function validateLogin($user)
    {
        $login   = htmlentities($user);
        $results = executeQuery('SELECT * FROM users WHERE login=?', $login);
        if (count($results) == 0) {
            return true;
        }
            
        return false;
    }
   
    /**
     * Controllo robustezza password
     *
     * La funzione si occupa di verificare se la password specificata risulta
     * abbastanza robusta; vengono effettuati diversi controlli, tra cui:
     * - lunghezza minima della password (default 8 caratteri)
     * - presenza caratteri speciali (default sono ammessi '_-.!#$%&@')
     * - varieta' dei caratteri usati (devono essere presenti numeri, lettere e
     *   caratteri speciali)
     *
     * @param string  $pwd      Password da verificare
     * @param integer $minLen   Lunghezza minima ammessa
     * @param integer $minStr   Varieta' minima di caratteri
     * @param string  $specials Set di caratteri speciali ammessi
     *
     * @return boolean Restituisce true se la login e' gia' in uso
     * @access public
     * @static
     */
    public static function validatePasswordStrongness($pwd, $minLen = 8, 
                                                      $minStr = 3, 
                                                      $specials = '_-.!#$%&@')
    {
        $result = FV_User::PASSWD_OK;
        if (strlen($pwd) >= $minLen) { 
            $specials = preg_replace('/(\W|-)/', "\\\\$1", $specials); 
            $invalid  = "/[^a-zA-Z0-9$specials]/"; 
            if (preg_match($invalid, $pwd)) {
                /** Sono presenti caratteri non ammessi */
                $result = FV_User::PASSWD_INVALIDCHAR; 
            } else { 
                $strength = 0; 
                foreach (array("a-z", "A-Z", "0-9", "$specials") as $chars) { 
                    if (preg_match("/[$chars]/", $pwd)) { 
                        $strength++; 
                    } 
                } 
                if ($strength < $minStr) { 
                    /** non e' presente una varieta' sufficiente di caratteri */
                    $result = FV_User::PASSWD_NOTVARIETY;
                } 
            } 
        } else { 
            /** la password non e' sufficientemente lunga */
            $result = FV_User::PASSWD_TOOSHORT;
        } 
        return $result;
    }
    
    /**
     * Funzione factory
     *
     * Crea un'istanza della classe e ne valorizza il contenuto con i valori
     * del vettore passato come parametro (per esempio: una record del DB).
     *
     * @param array $vector Vettore con i valori
     * @param mixed $basket Istanza della classe FV_Basket
     *
     * @return void
     * @static
     * @access public
     */
    static function &factoryFromArray($vector, $basket = null)
    {
        $user = &new FV_User();
        $user->_setDummy(false);
        $user->_getValuesFrom($vector, $basket);
        
        return $user;
    }

    /**
     * Aggiornamento variabili membro
     *
     * La funzione rilegge dalla base dati il record dell'utente e aggiorna le
     * proprie variabili membro: questa procedura trova utilita' nel form per
     * l'aggiornamento del proprio profilo.    
     *
     * @return void
     * @throws PEAR_Exception Viene generata qualora l'UID non sia valido
     * @access public
     */
    function reloadData()
    {
        if (!isset($this->uid)) {
            throw new PEAR_Exception('Invalid user ID', ERR_PARAM);
        }
        $record = executeQuery('SELECT * FROM users WHERE uid=?', $this->uid);
        $this->_getValuesFrom($record[0]);
    }

    /**
     * Funzione factory
     *
     * Crea un'istanza della classe per rappresentare un visitatore occasionale
     * ovvero un utente che non si ancora autenticato.
     *
     * @return void
     * @static
     * @access public
     */
    static function &dummyUser()
    {
        $user =& new FV_User();
        $user->_setDummy(true);
        $user->_setLevel(1);
        $user->basket = new FV_Basket(FV_User::DUMMY_LOGIN);
        
        return $user;
    }
    
    /**
     * Livello di privilegio utente
     *
     * Se il parametro vale zero, l'utente acquisisce i privilegi di un
     * amministratore del sito; la funzione risulta utile quando viene creato
     * un utente "dummy", o fittizio, per imporre un privilegio basso (non da
     * amministratore).
     *
     * @param integer $i Livello di privilegio utente
     *
     * @return void
     * @access private
     */
    private function _setLevel($i)
    {
        $this->_level = $i;
    }

    /**
     * Impostazione utenza fittizia
     *
     * Se il parametro vale "true", l'istanza della classe rappresenta 
     * un'utenza "dummy", o fittizia, ovvero un utente non ancora autenticato
     * ma che puo' tuttavia utilizzare il carrello.
     *
     * @param boolean $value Se vale "true", l'utente e' fittizio
     *
     * @return void
     * @access private
     */
    private function _setDummy($value)
    {
        $this->_dummy = $value;
    }
    
    /**
     * Getter della variabile membro "dummy"
     *
     * Restituisce "true" se l'istanza della classe rappresenta un'utente
     * "dummy", o fittizio.
     *
     * @return boolean True se l'utente e' fittizio
     * @access public
     */
    function isDummy()
    {
        return $this->_dummy;
    }

    /**
     * Validazione credenziali
     *
     * La funzione si occupa di verificare se le credenziali specificate 
     * corrispondono ad un utente registrato ed abilitato al login: il campo
     * 'valid' della tabella indica, se vale 1, che l'utente puo' collegarsi.
     *
     * @param string  $user   Password da verificare
     * @param integer $pass   Lunghezza minima ammessa
     * @param mixed   $basket Istanza della classe FV_Basket
     *
     * @return boolean Restituisce true se la login e' gia' in uso
     * @access public
     * @static
     */
    public static function validateUser($user, $pass, $basket = null)
    {
        $row = executeQuery('SELECT * FROM users WHERE login=? AND '.
                            'password=PASSWORD(?) AND valid=1', 
                             array(htmlentities($user), $pass));
        if (count($row) === 0) {
            return false;
        }
        $_SESSION['user'] =& FV_User::factoryFromArray($row[0], $basket);

        return true;
    }
    
    /**
     * Validazione email
     *
     * La funzione si occupa di verificare che la email specificata sia
     * sintatticamente corretta: questo prevede di verificare la presenza del
     * carattere '@', nonche' il formato del dominio e dell'utente.
     *
     * @param string $email Email da verificare
     *
     * @return boolean Restituisce true se la login e' gia' in uso
     * @access public
     * @static
     */
    public static function checkEmail($email)
    {
        /**
         * Prima di tutto controllo che sia presente il carattere '@' e che
         * la lunghezza dell'indirizzo sia valida: sara' sufficiente effettuare
         * un match con la regexp (estesa) che descrive un email
         */
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            return false;
        }
        /**
         * Divido in due parti l'indirizzo: utente e dominio
         */
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=".
                      "?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", 
                      $local_array[$i])) {
                return false;
            }
        }
        /**
         * Controllo se il dominio e' rappresentato da un indirizzo IP: se non
         * lo e', controllo la correttezza della sintassi.
         */
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                /**
                 * C'e' solo un punto: non puo' essere ne' un dominio corretto
                 * né un indirizzo IP.
                 */
                return false;
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|".
                          "([A-Za-z0-9]+))$", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Abilitazione utente
     *
     * La funzione si occupa di abilitare o disabilitare la funzione di 
     * login per l'utente corrispondente al parametro $uid: di fatto, pur
     * inserendo delle credenziali valide in fase di login, solo un utente che
     * ha il campo 'valid' pari ad uno puo' proseguire.
     *
     * @param string  $id     Identificativo utente da abilitare/disabilitare
     * @param bollean $enable Se vale true, l'utente viene abilitato.
     *
     * @return void
     * @throws PEAR_Exception Viene generata qualora il parametro non sia valido
     * @access public
     * @static
     */
    public static function enableUser($id, $enable = true)
    {
        $uid = htmlentities($id);
        if (ctype_digit($uid) === false) {
            throw new PEAR_Exception('Invalid user ID', ERR_PARAM);
        }
        $value = $enable?1:0;
        executeQuery('UPDATE users SET valid=? WHERE uid=?', array($value, $uid));
    }
    
    /**
     * Legge i valori contenuti nel vettore passato come argomento e valorizza
     * l'istanza della classe. Se il parametro $basket non e' nullo, vuol dire
     * che un utente "dummy" si e' appena autenticato: il contenuto del carrello
     * viene dunque associato alla nuova istanza.
     *
     * @param array $array  Vettore con i valori da assegnare
     * @param mixed $basket Istanza della classe FV_Basket
     *
     * @return void
     * @access private
     */
    private function _getValuesFrom($array, $basket = null)
    {
        $this->firstName   = $array["first_name"];
        $this->lastName    = $array["last_name"];
        $this->login       = $array["login"];
        $this->pass        = $array["password"];
        $this->email       = $array["email"];
        $this->address     = $array["address"];
        $this->city        = $array["city"];
        $this->cap         = $array["cap"];
        $this->state       = $array["state"];
        $this->valid       = $array["valid"];
        $this->_level      = $array["level"];
        $this->uid         = $array["uid"];
        $this->_securecode = $array["securecode"];
        
        if ($basket !== null) {
            $this->basket = new FV_Basket($this->login);
            foreach ($basket as $product) {
                $this->basket->add($product['pid']);
            }
        } else {
            if (isset($array["basket"])) {
                $this->basket = unserialize($array["basket"]);
            } else {
                $this->basket = new FV_Basket($this->login);
            }
        }
    }
    
    /**
     * Privilegio utente
     *
     * La funzione restituisce true se l'utente che effettua la richiesta e'
     * un amministratore.
     *
     * @return boolean Restituisce true se l'utente e' un amministratore.
     * @access public
     */
    function isAdmin()
    {
        return ($this->_level == 0);
    }
    
    /**
     * Invia la mail per attivazione account
     *
     * @param string $param Login cui mandare la mail
     *
     * @return void
     * @static
     */
    static function sendVerificationEmail($param)
    {
        global $logger;

        $login = htmlentities($param);
        if (ctype_alnum($login) === false) {
            throw new PEAR_Exception('Invalid user login', ERR_PARAM);
        }
        $record = executeQuery('SELECT * FROM users WHERE login=?', $login);
        if (count($record) === 0) {
            throw new PEAR_Exception('Invalid user login', ERR_PARAM);
        }

        $code = generateSecureCode();
        executeQuery('UPDATE users SET securecode=? WHERE uid=?',
                     array($code, $record[0]['uid']));

        $mail_obj =& Mail::factory('smtp', getSMTPSettings());

        $headers['From']    = 'ecommerce@devzero.it';
        $headers['To']      = $record[0]['email'];
        $headers['Subject'] = 'Confirmation email';
        $recipients         = $record[0]['email'];
        $body               = "Thank you for registering with /dev/zero!!\n\n".
                              " To activate your account you must click on".
                              " this link:\n".
                              SERVER_URL.'confirm.php?code='.$code;

        $mail_obj->send($recipients, $headers, $body);
        $logger->log('A confirmation email has been sent to '.$login, 
                     PEAR_LOG_NOTICE);
    }

    /**
     * Invio nuove credenziali
     *
     * Se l'utente dimentica/perde la propria password d'accesso puo' sempre
     * chiedere al sistema di inviare via mail una nuova password generata
     * in modo pseudo-casuale.
     *
     * @param string $param Login dell'utente
     *
     * @return void
     * @throws PEAR_Exception Viene generata qualora l'UID non sia valido
     * @access public
     * @static
     */
    static function forgotPassword($param)
    {
        global $logger;
        
        $login = htmlentities($param);
        if (ctype_alnum($login) === false) {
            throw new PEAR_Exception('Invalid user login', ERR_PARAM);
        }
        $record = executeQuery('SELECT * FROM users WHERE login=?', $login);
        if (count($record) === 0) {
            throw new PEAR_Exception('Invalid user login', ERR_PARAM);
        }
        $passwd = generatePassword();
        executeQuery('UPDATE users SET password=PASSWORD(?) WHERE uid=?',
                     array($passwd, $record[0]['uid']));

        $mail_obj =& Mail::factory('smtp', getSMTPSettings());

        $headers['From']    = 'ecommerce@devzero.it';
        $headers['To']      = $record[0]['email'];
        $headers['Subject'] = 'New password';
        $recipients         = $record[0]['email'];
        $body               = "Your password to access /dev/zero ".
                              "has been updated\n\n".
                              " To login use these values:\n".
                              "Username: ".$record[0]['login']."\n".
                              "Password: ".$passwd;

        $mail_obj->send($recipients, $headers, $body);
        $logger->log('A new password has been sent to '.$login, PEAR_LOG_NOTICE);
        return true;
    }

    /**
     * Logout utente
     *
     * Effettua la serializzazione del carrello e la scrive sul database, in
     * modo tale da ripristinarne il contenuto al successivo login.
     *
     * @return void
     * @access public
     */
    function logout()
    {
        if (!$this->_dummy) {
            $str = serialize($this->basket);
            executeQuery('UPDATE users SET basket = ? WHERE uid=?',
                         array($str, $this->uid));
        }
    }
}

?>