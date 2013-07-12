<?php
/**
 * Registrazione utente
 *
 * Un visitatore non autenticato puo' procedere alla registrazione del sito:
 * la procedura prevede di compilare un form con alcuni dati anagrafici, il
 * domicilio e la selezione delle credenziali d'accesso, per le quali viene
 * eseguito:
 * - controllo di univocita' sul login
 * - controllo della robustezza della password.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: register.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once dirname(__FILE__).'/extra/securimage/securimage.php';

/**
 * La registrazione di un nuovo utente e' possibile solo quando l'istanza
 * dell'oggetto FV_User rappresenta un'utente fittizio e non regolarmente
 * autenticato.
 */
if (isset($_SESSION['user']) === true) {
    if ($_SESSION['user']->isDummy() === false) {
        redirectToURL('edit.php');
    }
}

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('register', $_SESSION['lang']);

$error = false;

if (isset($_POST['insert'])) {
    /**
     * Eliminazione categoria: ottengo il 'category ID', verifico che il tipo
     * sia corretto e lo elimino dalla base dati.
     */
    foreach ($_POST as $key => $value) {
        $data[$key] = htmlentities($value);
    }

    /**
     * Verifico che i campi del FORM rispettino i vincoli imposti, ovvero:
     * - il captcha deve essere corretto
     * - i campi marcati sono considerati obbligatori
     * - le due password devono essere uguali
     * - la policy della privacy deve essere stata approvata
     * - l'email specificata deve essere sintatticamente corretta
     * - la login scelta deve essere univoca tra tutti gli utenti
     * - la password scelta deve essere sufficientemente robusta
     */
    $securimage = new Securimage();
    if (!$securimage->check($data['captcha_code'])) {
        $err_msg = $strings['invalid_code'];
        $error   = true;
    } else if (!strlen($data['first_name']) || !strlen($data['last_name']) ||
        !strlen($data['login']) || !strlen($data['email']) ||
        !strlen($data['passwd']) || !strlen($data['passwd2'])) {
        $err_msg = $strings['req_fields'];
        $error   = true;
    } else if ($data['passwd'] != $data['passwd2']) {
        $err_msg = $strings['err_pwd'];
        $error   = true;
    } else if ($data['privacy'] == false) {
        $err_msg = $strings['err_policy'];
        $error   = true;
    } else if (!FV_User::checkEmail($data['email'])) {
        $err_msg = $strings['err_email'];
        $error   = true;
    } else if (!FV_User::validateLogin($data['login'])) {
        $err_msg = $strings['err_login'];
        $error   = true;
    } else if (($ret = FV_User::validatePasswordStrongness($data['passwd'])) != 
               FV_User::PASSWD_OK) {
        switch ($ret) {
        case FV_User::PASSWD_TOOSHORT:
                $err_msg = $strings['err_pwd_short'];
            break;
        case FV_User::PASSWD_INVALIDCHAR:
                $err_msg = $strings['err_pwd_char'];
            break;
        case FV_User::PASSWD_NOTVARIETY:
                $err_msg = $strings['err_pwd_variety'];
            break;
        }
        $error = true;
    } else {
        $sql    = 'INSERT INTO users '.
                  '(uid, first_name, last_name, login, password, email, valid, '.
                  'level, cap, address, city, state, securecode) '.
                  'VALUES (0, ?, ?, ?, PASSWORD(?), ?, 0, 1, ?, ?, ?, ?, ?)';
        $values = array($data['first_name'], $data['last_name'], $data['login'], 
                    $data['passwd'], $data['email'], $data['cap'], $data['address'],
                    $data['city'], $data['state'], generateSecureCode());
        executeQuery($sql, $values);
        FV_User::sendVerificationEmail($data['login']);
        $logger->log('A new user has been registered ('.$data['login'].')', 
                     PEAR_LOG_NOTICE);
        redirectToURL('index.php');
    }
    
    extract($data);
}

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array('register.css'), 
                 array('common.js', 'frequency.js', 'passchk.js'));
$page->printHead();
$page->printMenu();
?>

<table border="0" class="central_box" width="100%">
<tr><td><h1 class="page_title"><?php echo $strings['page_title']; ?>
        </h1></td></tr>
<?php 
if ($error === true) {
    echo '<tr><td><h3 style="font-size: 12px; text-align: center; '.
         'color: red;">['.$err_msg.']</h3></td></tr>';
}
?>
<tr><td>
    <form id="loginform" name="passchk_form" action="register.php" 
          method="post" onSubmit="return defaultagree(this)">
         <label><strong><?php echo $strings['first_name']; ?></strong><br/>
         <input type="text" id="first_name" class="input_field" name="first_name"
                size="20" tabindex="10" 
                value="<?php echo isset($first_name)?$first_name:""; ?>"/></label>
         <label><strong><?php echo $strings['last_name']; ?></strong><br/>
         <input type="text" class="input_field" name="last_name" 
                size="20" tabindex="20" 
                value="<?php echo isset($last_name)?$last_name:""; ?>"/></label>
         <label><strong><?php echo $strings['email']; ?></strong><br/>
         <input type="text" class="input_field" name="email" 
                size="20" tabindex="30" 
                value="<?php echo isset($email)?$email:""; ?>"/></label>
         <label><strong>login</strong><br/>
         <input type="text" class="input_field" name="login" 
                size="20" tabindex="40" 
                value="<?php echo isset($login)?$login:""; ?>"/></label>
         <label><strong>password</strong><br/>
         <input type="password" name="passwd" id="user_pass" class="input_field" 
                size="20" tabindex="50" /></label>
         <span id="passchk_result"><?php echo $strings['loading']; ?> &hellip;
                </span><br/>
         <label><strong><?php echo $strings['repeat_pwd']; ?></strong><br/>
         <input type="password" name="passwd2" class="input_field" size="20" 
                tabindex="60" /></label>
         <label><?php echo $strings['address']; ?><br/>
         <input type="text" name="address" class="input_field" 
                size="20" tabindex="70" 
                value="<?php echo isset($address)?$address:""; ?>"/></label>
         <label>CAP<br/>
         <input type="text" name="cap" class="input_field_short" size="10" 
                tabindex="80" value="<?php echo isset($cap)?$cap:""; ?>"/>
                </label><br/>
         <label><?php echo $strings['city']; ?><br/>
         <input type="text" name="city" class="input_field_short" size="20" 
                tabindex="90" value="<?php echo isset($city)?$city:""; ?>"/>
                </label><br/>
         <label><?php echo $strings['state']; ?><br/>
         <input type="text" name="state" class="input_field_short" size="20" 
                tabindex="100" value="<?php echo isset($state)?$state:""; ?>"/>
                </label><br/>
         <label><?php echo $strings['sec_code']; ?><br/>
         <img id="captcha" 
            src="<?php echo CONTENT_URL; ?>/extra/securimage/securimage_show.php" 
            alt="CAPTCHA Image" /><br/>
         <input type="text" name="captcha_code" class="input_field_short" 
            style="margin-top: 5px" size="10" maxlength="6" tabindex="103"/>
            </label><br/><br/>
         <input type="checkbox" name="privacy" tabindex="105" 
                onClick="agreesubmit(this)"/>
         <label><?php echo $strings['policy1']; ?> <a href="privacy.php">
                <?php echo $strings['policy2']; ?></a></label>
                <br/><br/>
         <input type="reset" name="cancel" class="wp-submit" value="Reset" 
                tabindex="110" />
         <input type="submit" name="insert" class="wp-submit" 
                value="<?php echo $strings['save']; ?>" 
                tabindex="120" disabled/>
    </form></td></tr>
</table>
    
<script type="text/javascript">
    try {
        document.getElementById('first_name').focus();
    } catch (e) {}

    var checkobj

    function agreesubmit(el){
        checkobj=el
        if (document.all||document.getElementById){
            for (i=0;i<checkobj.form.length;i++){
                var tempobj=checkobj.form.elements[i]
                if(tempobj.type.toLowerCase()=="submit")
                    tempobj.disabled=!checkobj.checked
            }
        }
    }

    function defaultagree(el){
        if (!document.all&&!document.getElementById){
            if (window.checkobj&&checkobj.checked)
                return true
            else{
                alert("Please read/accept terms to submit form")
                return false
            }
        }
    }
    
    document.forms.passchk_form.privacy.checked=false
</script>
    
<?php
/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
