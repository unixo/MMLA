<?php
/**
 * Modifica impostazioni sito
 *
 * Tramite questa pagina, un amministratore puo' modificare le impostazioni del
 * sito, come, per esempio, il tema utilizzato, attivare/disattivare i feed
 * RSS o le impostazioni del server SMTP.
 *
 * PHP Version 5
 *
 * @category AdminTool
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: settings.php 22 2009-09-08 10:30:58Z unixo $
 * @link     http://commerce.devzero.it
 * @see      admin.php, setConfigValue, getConfigValue
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'HTML/QuickForm.php';

needAuthenticatedUser(true, 'login.php');

/**
 * Leggo dal database i dati della localizzazzione della pagina
 */
$strings = $tr->getPage('settings', $_SESSION['lang']);

/**
 * Creo un'istanza della classe FV_Page per creare la pagina: inizio a 
 * stampare il menu principale e il box di sinistra.
 */
$page = new FV_Page($strings['page_title'], array('settings.css'));
$page->printHead();
$page->printMenu();

$form = new HTML_QuickForm('settingsform', 'post');

/**
 * Costruisco l'elenco dei temi disponibili leggendo la directory 'themes':
 * tengo in considerazione soltanto i file che non cominciano con il punto.
 */
$dh = opendir(THEMES_PATH);
while (($file = readdir($dh)) !== false) {
    if ($file != '.' && $file != '..' && $file[0] != '.') {
        $themes[$file] = $file;
    }
}
closedir($dh);

/**
 * Costruisco la lista delle lingue in cui il sito è stato tradotto, effettuando
 * una select dalla tabella 'langs'.
 */
$languages = executeQuery('SELECT id,name FROM langs ORDER BY name');
foreach ($languages as $row) {
    $langs[$row['id']] = $row['name'];
}

/**
 * Creo gli elementi che compongono il form e li aggiungo a $form; il valore
 * testuale del campo e' localizzato.
 */
$form->addElement('header', 'theme_header', $strings['theme_header']);
$form->addElement('select', 'theme', $strings['theme'], $themes);
$form->addElement('header', 'language_header', $strings['lang_header']);
$form->addElement('select', 'lang', $strings['lang'], $langs);
$form->addElement('header', 'rss_header', $strings['rss_header']);
$form->addElement('checkbox', 'rss', $strings['rss']);
$form->addElement('header', 'smtp_header', $strings['smtp_header']);
$form->addElement('text', 'smtp_server', $strings['smtp_server']);
$form->addElement('text', 'smtp_port', $strings['smtp_port']);
$form->addElement('text', 'smtp_user', $strings['smtp_user']);
$form->addElement('password', 'smtp_pwd', $strings['smtp_pass']);
$form->addElement('text', 'smtp_helo', $strings['smtp_helo']);
$form->addElement('submit', null, $strings['save'], array('class' => 'mysubmit'));

/**
 * Imposto i valori di default degli elementi del FORM.
 */
$config_smtp = getConfigValue('smtp%');
$config_rss  = (getConfigValue('rss') === 'on')?true:false;
$form->setDefaults(array('rss'         => $config_rss,
                         'lang'        => getConfigValue('lang'),
                         'theme'       => getConfigValue('theme'),
                         'smtp_server' => $config_smtp['smtp_host'],
                         'smtp_port'   => $config_smtp['smtp_port'],
                         'smtp_user'   => $config_smtp['smtp_user'],
                         'smtp_pwd'    => $config_smtp['smtp_pass'],
                         'smtp_helo'   => $config_smtp['smtp_helo']));

/**
 * Imposto le regole di validazione del form (sintassi, semantica e campi
 * obbligatori)
 */
$form->addRule('smtp_server', $strings['smtp_server_req'], 'required');
$form->addRule('smtp_port', $strings['smtp_port_req'], 'required');
$form->addRule('smtp_port', $strings['port_num_err'], 'numeric');

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
 * Controllo se il FORM e' stato gia' chiamato tramite metodo POST: entro in
 * modalita' di validazione del suo contenuto.
 */
if ($form->validate()) {
    /**
     * Applico le funzioni 'addslashes' e 'htmlentities' al valore di ogni
     * campo del form per prevenire SQL Injection e XSS
     */
    $form->applyFilter('__ALL__', 'addslashes');
    $form->applyFilter('__ALL__', 'htmlentities');

    /**
     * Trasformo il valore boolean del campo 'rss' nella stringa on/off
     */
    $rss_value = ($form->exportValue('rss') == true)?'on':'off';

    /**
     * Apporto le modifiche al database
     */
    setConfigValue(CONFIG_THEME, $form->exportValue('theme'));
    setConfigValue(CONFIG_LANG, $form->exportValue('lang'));
    setConfigValue(CONFIG_RSS, $rss_value);
    setConfigValue(CONFIG_SMTP_SERVER, $form->exportValue('smtp_server'));
    setConfigValue(CONFIG_SMTP_PORT, $form->exportValue('smtp_port'));
    setConfigValue(CONFIG_SMTP_USER, $form->exportValue('smtp_user'));
    setConfigValue(CONFIG_SMTP_PASS, $form->exportValue('smtp_pwd'));
    setConfigValue(CONFIG_SMTP_HELO, $form->exportValue('smtp_helo'));
    redirectToURL('admin.php');    
} else {
    /**
     * Imposto un template personalizzato per il form, in modo che possa
     * modificarne l'apparenza tramite CSS.
     */
    $renderer =& $form->defaultRenderer(); 
    $renderer->setFormTemplate('<form{attributes}><table class="settingstable" '.
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

