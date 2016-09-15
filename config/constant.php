<?php

if(isset($_SERVER['HTTP_HOST'])){
    define('ASSETS_PATH','http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'/../assets');
    define('CDN_PATH','http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'/../cdn');

    define('PRODUCT_NAME','武汉理工大学计算机导论');
    define('PRODUCT_VERSION','1.0 alpha-1.0');
}

