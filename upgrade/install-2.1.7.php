<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('_PS_VERSION_'))
    exit;

/**
* object module available
*/
function upgrade_module_2_1_7($object)
{
    $upgrade_version = '2.1.7';

    $object->upgrade_detail[$upgrade_version] = array();

    try {
    	// return false si erreur
        if (!Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'mr_method` ALTER `is_deleted` SET DEFAULT "0"'))
            $object->upgrade_detail[$upgrade_version][] = $object->l('Can\'t add new field in methodtable');

        return Configuration::updateValue('MONDIAL_RELAY', $upgrade_version);
    }
    catch (Exception $e) { 
    	return false;
    }
}