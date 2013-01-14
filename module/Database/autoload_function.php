<?php
return function($sClass){
    static $aMap;
    if(!$aMap)$aMap = include __DIR__ . '/autoload_classmap.php';
    if(!isset($aMap[$sClass]))return false;
    return include $aMap[$sClass];
};