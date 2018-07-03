<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../vendor/autoload.php';

$defconf = '/etc/flexibee/client.json';

\Ease\Shared::instanced()->loadConfig($defconf);


$prober = new \FlexiPeeHP\ui\StatusInfoBox(null, $_REQUEST);

$oPage = new \Ease\TWB\WebPage(_('Initial Setup'));

$connForm = new FlexiPeeHP\ui\ConnectionForm();
if ($oPage->isPosted()) {
    $connForm->fillUp($_POST);
} else {
    $connForm->fillUp($prober->getConnectionOptions());
}

$container = $oPage->addItem(new \Ease\TWB\Container());

$panel = $container->addItem(new Ease\TWB\Panel(_('Setup FlexiBee server'),
    'success', $connForm, $oPage->getStatusMessagesAsHtml()));
$panel->addItem(new Ease\Html\DivTag('<br>'));
$panel->addItem(new \Ease\TWB\Well($prober));

$configRaw = $prober->getConnectionOptions();

$config = [
    'FLEXIBEE_URL' => $configRaw['url'],
    'FLEXIBEE_USER' => $configRaw['user'],
    'FLEXIBEE_PASSWORD' => $configRaw['password'],
    'FLEXIBEE_COMPANY' => $configRaw['company']
];

$panel->addItem(new \Ease\Html\PreTag(json_encode($config, JSON_PRETTY_PRINT)));


$oPage->draw();

