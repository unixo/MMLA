<?php
/**
 * Pagina 'Login'
 *
 * La pagina 'Login' permette l'autenticazione di un utente all'interno del 
 * sito
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @filesource
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: login.php 29 2009-09-08 10:37:33Z unixo $
 * @link     http://commerce.devzero.it
 * @see      FV_Page, FV_User
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'HTML/QuickForm.php';

/**
 * Se la variabile di sessione 'user' e' stata gia' registrata vuol dire che
 * l'utente e' gia' autenticato: in questo caso effettuo un redirect alla
 * pagina che consente la modifica dell'anagrafica.
 */
if (isset($_SESSION['user']) && !$_SESSION['user']->isDummy()) {
    redirectToURL('edit.php');
}

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('login', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: per il login
 * evito di stampare i due box, focalizzo l'attenzione solo sul form.
 */
$page                = new FV_Page($strings['page_title'], array('login.css'));
$page->printRightBox = $page->printLeftBox = false;
$page->printHead();
$page->printMenu();

$form = new HTML_QuickForm('loginform', 'post');

/**
 * Creo gli elementi che compongono il form e li aggiungo a $form; il valore
 * testuale del campo e' localizzato.
 */
$form->addElement('header', 'login_header', $strings['login_header']);
$form->addElement('text', 'login', $strings['username'], array('id' => 'login'));
$form->addElement('password', 'password', $strings['password']);
$form->addElement('hidden', 'redirect');
$form->addElement('submit', null, $strings['auth'], array('class' => 'mysubmit'));

/**
 * Imposto i valori di default degli elementi del FORM.
 */
$form->setDefaults(array('redirect' => $_SERVER['HTTP_REFERER']));

/**
 * Imposto le regole di validazione del form (sintassi, semantica e campi
 * obbligatori)
 */
$form->addRule('login', $strings['login_required'], 'required');
$form->addRule('password', $strings['pass_required'], 'required');
$form->applyFilter('login', 'trim');

/**
 * Versione localizzata del messaggio di campo obbligatorio
 */
$form->setRequiredNote('<span style="font-size:80%; color: red">*</span> '.
                       $strings['required_note']);

if ($form->validate()) {
    /**
     * Applico le funzioni 'addslashes' e 'htmlentities' al valore di ogni
     * campo del form per prevenire SQL Injection e XSS
     */
    $form->applyFilter('__ALL__', 'addslashes');
    $form->applyFilter('__ALL__', 'htmlentities');
     
    /**
     * Verifico se sto effettuando l'autenticazione di un visitatore che ha
     * gia' inserito qualche prodotto nel proprio carrello: in questo caso
     * passo i dati del carrello alla funzione di autenticazione, in modo tale
     * che il carrello venga associato alla nuova istanza di FV_User.
     */
    $nonauth_basket = null;
    if (isset($_SESSION['user'])) {
        $nonauth_basket = $_SESSION['user']->basket->items();
    }

    /**
     * Verifico se le credenziali specificate sono valide: in caso positivo,
     * se l'utente ha i privilegi di amministratore, redirigo la navigazione
     * verso gli AdminTool o alla pagina principale (o eventuale REFERER) in
     * caso contrario.
     */
    if (FV_User::validateUser($form->exportValue('login'),
                              $form->exportValue('password'),
                              $nonauth_basket)) {
        if ($_SESSION['user']->isAdmin() === true) {
            $link = 'admin.php';
        } else {
            $tmp = $form->exportValue('redirect');
            if (substr($tmp, 0, strlen(SERVER_URL)) !== SERVER_URL) {
                $link = 'index.php';
            } else {
                $link = str_replace(SERVER_URL, '', $tmp);
            }
        }
        redirectToURL($link);
    }
    /**
     * Arrivo a questo punto solo se le credenziali sono errate: imposto
     * l'errore nel FORM e proseguo con la visualizzazione.
     */
    $form->setElementError('password', 'invalid credentials');
}

/**
 * Imposto un template personalizzato per il form, in modo che possa
 * modificarne l'apparenza tramite CSS.
 */
$renderer =& $form->defaultRenderer(); 
$renderer->setFormTemplate('<form{attributes}><table class="logintable" '.
                           'align="center" border="0">{content}</table></form>');
$renderer->setHeaderTemplate("\n\t<tr>\n\t\t<td class=\"form_header\" ".
                             "'align=\"center\" valign=\"middle\" ".
                             "colspan=\"2\"><b>{header}</b></td>\n\t</tr>");
$renderer->setElementTemplate('<tr><td align="left" class="input_label">{label}'.
                              '<!-- BEGIN required --><span style="color: '.
                              '#ff0000">*</span><!-- END required --></td>'.
                              '<td align="left">{element}<!-- BEGIN error -->'.
                              '<br /><span style="color: #ff0000;font-size: '.
                              '10px">{error}</span><!-- END error --></td></tr>');
$form->accept($renderer);

/**
 * Visualizzo il FORM
 */
$form->display();
?>

<script type="text/javascript">
try {
    document.getElementById('login').focus();
} catch (e) {}
</script>

<?php
echo '<div id="login_links"><p style="line-height: 10px;"><a href="forgot.php">'. 
     $strings['forgot_pwd'] .'</a></p><p style="line-height: 10px;">'. 
     $strings['new_user'] . '&nbsp;<a href="register.php">'.$strings['register'] .
     '!</a></p></div>';

/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>
