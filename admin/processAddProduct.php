<?php
session_start();
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->Load('../products.xml');
$products = $xml->getElementsByTagName('products')->item(0);
$categories = $products->childNodes;

if (isset($_POST['addProduct'])) {
    $productsLen = $products->getElementsByTagName('product');
    if ($productsLen->length > 0) {
        $prod_id = $productsLen->length + 1;
    } else {
        $prod_id = 1;
    }
    $prod_name = $_POST['prodname'];
    $prod_price = $_POST['prodprice'];
    $prod_qty = $_POST['prodqty'];
    
    $name = $_FILES['prodimg']['name'];
    $tmp_name = $_FILES['prodimg']['tmp_name'];
    if ($name) {
        $location = 'images/'.$name;
        move_uploaded_file($tmp_name, '../'.$location);
    }    
    
    $prod_cat = $_POST['prodcat'];
    
    foreach ($categories as $category) {
        if ($category->nodeName == $prod_cat) {
            $productDiv = $xml->createElement('product');
            $productDiv->setAttribute("prodID", $prod_id);
            $newName = $xml->createElement('prodName');
            $newName->appendChild($xml->createTextNode($prod_name));
            $newPrice = $xml->createElement('prodPrice');
            $newPrice->appendChild($xml->createTextNode($prod_price));
            $newImg = $xml->createElement('prodImg');
            $newImg->appendChild($xml->createTextNode($location));
            $newQty = $xml->createElement('prodQty');
            $newQty->appendChild($xml->createTextNode($prod_qty));
            
            $productDiv->appendChild($newName);
            $productDiv->appendChild($newPrice);
            $productDiv->appendChild($newImg);
            $productDiv->appendChild($newQty);
            $category->appendChild($productDiv);
            header ('Location: index-admin.php?fromAddProd=1');
        }
    }        
}

if (isset($_GET['cmdCode'])) {
    $cmdCode = $_GET['cmdCode'];
    if ($cmdCode == 2) {
        $prodID = $_GET['prodID'];
        foreach ($products->getElementsByTagName('product') as $item) {
            if ($prodID == $item->getAttribute("prodID")) {
                $prodNum = $item->getAttribute("prodID");
                $prodName = $item->getElementsByTagName("prodName")->item(0)->firstChild->nodeValue;
                $prodPrice = $item->getElementsByTagName("prodPrice")->item(0)->firstChild->nodeValue;
                $prodQty = $item->getElementsByTagName("prodQty")->item(0)->firstChild->nodeValue;
                echo $prodNum.'*'.$prodName.'*'.$prodQty.'*'.number_format($prodPrice,2);
            }
        }
    } else if ($cmdCode == 3) {
        $prodID = $_GET['prodID'];
        $newQty = $_GET['newQty'];
        $newPrice = $_GET['newPrice'];
        
        foreach ($products->getElementsByTagName('product') as $item) {
            if ($prodID == $item->getAttribute('prodID')) {
                $item->getElementsByTagName('prodQty')->item(0)->firstChild->nodeValue = $newQty;
                $item->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue = $newPrice;
                echo 'Successfully updated!*'.number_format($newPrice, 2);
            }
        }
    }
}

$xml->Save('../products.xml');
?>