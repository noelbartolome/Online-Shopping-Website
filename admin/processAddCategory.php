<?php
session_start();
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->Load('../products.xml');
$products = $xml->getElementsByTagName('products')->item(0);
$categories = $products->childNodes;
$newCat = $_GET['newCat'];
$flag = 0;

foreach ($categories as $category) {
    if ($category->nodeType == 1) {
        if (strtolower($category->nodeName) == strtolower($newCat)) {
            echo 'Category existing';
            $flag = 1;
            break;
        }
    }    
}

if ($flag == 0) {
    $newDiv = $xml->createElement($newCat);
    $newDiv->appendChild($xml->createTextNode(" "));
    $products->appendChild($newDiv);
    echo 'Category Added';
    $xml->Save('../products.xml');
}
?>