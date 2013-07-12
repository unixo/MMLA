<?php
/**
 * Modifica profilo utente
 *
 * Tramite questa pagina, un utente autenticato puo' modificare i dati del
 * proprio profilo.
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: edit.php 27 2009-09-08 10:36:23Z unixo $
 * @link     http://commerce.devzero.it
 * @see      FV_User, FV_page
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'HTML/QuickForm.php';

needAuthenticatedUser(false, 'login.php');

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('edit', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array('edit.css'));
$page->printHead();
$page->printMenu();

$form = new HTML_QuickForm('editform', 'post');

/**
 * Creo gli elementi che compongono il form e li aggiungo a $form; il valore
 * testuale del campo e' localizzato.
 */
$form->addElement('header', 'profile_header', $strings['form_header']);
$form->addElement('text', 'first_name', $strings['first_name']);
$form->addElement('text', 'last_name', $strings['last_name']);
$form->addElement('text', 'email', $strings['email']);
$form->addElement('password', 'password1', $strings['password']);
$form->addElement('password', 'password2', $strings['password']);
$form->addElement('header', 'address_header', $strings['address_group']);
$form->addElement('text', 'address', $strings['address']);
$form->addElement('text', 'cap', $strings['cap']);
$form->addElement('text', 'city', $strings['city']);
$form->addElement('text', 'state', $strings['state']);
$form->addElement('submit', null, $strings['update'], array('class' => 'mysubmit'));

/**
 * Imposto le regole di validazione del form (sintassi, semantica e campi
 * obbligatori)
 */
$form->addRule('first_name', $strings['fname_required'], 'required');
$form->addRule('last_name', $strings['lname_required'], 'required');
$form->addRule('email', $strings['email_required'], 'required');
$form->addRule('password1', $strings['pass_required'], 'required');
$form->addRule('password2', $strings['pass_required'], 'required');
$form->addRule(array('password1', 'password2'), 
               $strings['passwd_err'], 'compare');

/**
 * Prima del POST, chiamo la funzione 'trim' per il contenuto di ogni campo
 */
$form->applyFilter('__ALL__', 'trim');

/**
 * Versione localizzata del messaggio di campo obbligatorio
 */
$form->setRequiredNote('<span style="font-size:80%; color: red">*</span> '.
                       $strings['required_note']);

/**
 * Imposto i campi del FORM con i valori dell'istanza 'user' contenuta tra le
 * variabili di sessione.
 */
$user =& $_SESSION['user'];
$form->setDefaults(array('first_name' => $user->firstName,
                         'last_name'  => $user->lastName,
                         'email'      => $user->email,
                         'password1'  => $user->pass,
                         'password2'  => $user->pass,
                         'address'    => $user->address,
                         'cap'        => $user->cap,
                         'city'       => $user->city,
                         'state'      => $user->state));

if ($form->validate()) {
    /**
     * Applico le funzioni 'addslashes' e 'htmlentities' al valore di ogni
     * campo del form per prevenire SQL Injection e XSS
     */
    $form->applyFilter('__ALL__', 'addslashes');
    $form->applyFilter('__ALL__', 'htmlentities');
    $sql    = "UPDATE users SET first_name=?, last_name=?, email=?, ".
              "address=?, cap=?, city=?, state=?, password=PASSWORD(?) ";
    $values = array($form->exportValue('first_name'), 
                    $form->exportValue('last_name'),
                    $form->exportValue('email'), $form->exportValue('address'), 
                    $form->exportValue('cap'), $form->exportValue('city'), 
                    $form->exportValue('state'), 
                    $form->exportValue('password1'));
    $sql   .= "WHERE uid = ?";
    array_push($values, $_SESSION['user']->uid);
    executeQuery($sql, $values);
    $_SESSION['user']->reloadData();
    redirectToURL('index.php');    
} else {
    /**
     * Imposto un template personalizzato per il form, in modo che possa
     * modificarne l'apparenza tramite CSS.
     */
    $renderer =& $form->defaultRenderer(); 
    $renderer->setFormTemplate('<form{attributes}><table class="usertable" '.
                               'align="center" border="0">{content}</table></form>');
    $renderer->setHeaderTemplate("\n\t<tr>\n\t\t<td class=\"form_header\" ".
                                 "'align=\"center\" valign=\"middle\" ".
                                 "colspan=\"2\"><b>{header}</b></td>\n\t</tr>");
    $renderer->setElementTemplate('<tr><td align="left" class="input_label">{label}'.
        '<!-- BEGIN required --><span style="color: #ff0000">*</span>'.
        '<!-- END required --></td><td align="left">{element}'.
        '<!-- BEGIN error --><br /><span style="color: #ff0000;font-size:10px">'.
        '{error}</span><!-- END error --></td></tr>');
    $form->accept($renderer);

    /**
     * Visualizzo il FORM
     */
    $form->display();
}

/**
 * Completo la struttura della pagina stampando il menu orizzontale inferiore
 * ed inviando il tutto al browser dell'utente.
 */
$page->printFooter();
?>