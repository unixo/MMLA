<?php
/**
 * Feed RSS
 *
 * Qualora l'amministratore abbia attivato la generazione del feed RSS dei
 * prodotti, nella pagina principale verra' incluso il feed nell'HEAD della
 * pagina, nonche' il link nella barra dei menu.
 * Questa pagina si occupa di prelevare gli ultimi prodotti inseriti nel DB e
 * formattarli in XML.
 *
 * PHP Version 5
 *
 * @category RSS
 * @package  UrbinoCommerce
 * @author   Ferruccio Vitale <unixo@devzero.it>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version  SVN: $Id: rss.php 9 2009-08-25 15:05:48Z unixo $
 * @link     http://commerce.devzero.it
 */
require_once dirname(__FILE__).'/includes/common.php';
require_once 'XML/Serializer.php';

/**
 * Definizione delle proprieta' del feed XML+RSS
 */
$options = array(
    XML_SERIALIZER_OPTION_MODE => XML_SERIALIZER_MODE_SIMPLEXML,
    XML_SERIALIZER_OPTION_INDENT => "    ",
    XML_SERIALIZER_OPTION_LINEBREAKS => "\n",
    XML_SERIALIZER_OPTION_TYPEHINTS => false,
    XML_SERIALIZER_OPTION_XML_DECL_ENABLED => true,
    XML_SERIALIZER_OPTION_XML_ENCODING => "UTF-8",
    XML_SERIALIZER_OPTION_ROOT_NAME => 'rss',
    XML_SERIALIZER_OPTION_DEFAULT_TAG => "item",
    XML_SERIALIZER_OPTION_ROOT_ATTRIBS => array('version' => "2.0",
      'xmlns:dc' => "http://purl.org/dc/elements/1.1/",
      'xmlns:sy' => "http://purl.org/rss/1.0/modules/syndication/",
      'xmlns:content' => "http://purl.org/rss/1.0/modules/content/",
      'xmlns:atom' => "http://www.w3.org/2005/Atom")
);

/**
 * Lettura degli ultimi 25 prodotti presenti nello store e creazione di un
 * vettore corrispondenti al singolo articolo del feed.
 */
$records = executeQuery('SELECT * FROM products ORDER BY pid DESC LIMIT 25');
$stories = array();
foreach ($records as $row) {
    $stories[] = array('title' => $row['name'],
                       'link'  => SERVER_URL.'detail.php?pid='.$row['pid'],
                       'description' => $row['descr']);
}

/**
 * Creazione del "channel" del feed
 */
$data['channel'] = array(
            "title" => "/dev/zero Store: 25 Just Added Products",
            "link" => SERVER_URL,
            "description" => "/dev/zero Store: 25 Just Added Products",
            'language' => 'en', 'generator' => '/dev/zero ecommerce',
            'copyright' => 'Copyright 2009 /dev/zero',
            $stories);

/**
 * Creazione instanza XML del feed: se l'operazione riesce con successo, invio
 * al browser la rappresentazione xml dei dati.
 */
$serializer = new XML_Serializer($options);
if ($serializer->serialize($data)) {
    header('Content-type: text/xml');
    echo $serializer->getSerializedData();
}

?>