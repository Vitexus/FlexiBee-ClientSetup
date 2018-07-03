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

$configRaw = $prober->getConnectionOptions();

$oPage = new \Ease\TWB\WebPage(_('Initial Setup'));

if ($oPage->isPosted() && ( $prober->lastResponseCode == 200)) {
    $config = [
        'FLEXIBEE_URL' => $configRaw['url'],
        'FLEXIBEE_USER' => $configRaw['user'],
        'FLEXIBEE_PASSWORD' => $configRaw['password'],
        'FLEXIBEE_COMPANY' => $configRaw['company']
    ];
    if (file_put_contents($defconf, json_encode($config, JSON_PRETTY_PRINT))) {
        $prober->addStatusMessage(sprintf(_('Configuration was saved to %s.'),
                $defconf), 'success');
    } else {
        $prober->addStatusMessage(sprintf(_('Configuration was not saved to %s.'),
                $defconf), 'error');
    }
}

$connForm = new FlexiPeeHP\ui\ConnectionForm();
if ($oPage->isPosted()) {
    $connForm->fillUp($_POST);
} else {
    $connForm->fillUp($prober->getConnectionOptions());
}

$container = $oPage->addItem(new \Ease\TWB\Container());

$panelRow =  new \Ease\TWB\Row();
$panelRow->addColumn(6, $connForm);
$col2 = $panelRow->addColumn(6, new \Ease\TWB\Well($prober));
$col2->addItem(new \Ease\Html\PreTag(file_get_contents($defconf)));

$panel = $container->addItem(new Ease\TWB\Panel(_('Setup FlexiBee server'),
    'success', $panelRow, $oPage->getStatusMessagesAsHtml()));

$panel->addItem();

$oPage->draw();
