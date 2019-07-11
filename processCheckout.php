<?php
session_start();
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->Load('products.xml');
$products = $xml->getElementsByTagName('products')->item(0);
$product = $products->getElementsByTagName('product');
$sub = 0;
$total = 0;
$itemsTotal = 0;

foreach ($_SESSION as $name=>$value) {
    if ($value > 0) {
        if (substr($name,0,5) == 'item_') {
            $subID = substr($name,5,strlen($name)-5);
            foreach ($product as $item) {
                $prodID = $item->getAttribute('prodID');                
                $sub = 0;
                if ($subID == $prodID) {
                    $itemsTotal++;
                    $pName[] = $item->getElementsByTagName('prodName')->item(0)->firstChild->nodeValue;
                    $pQty[] = $_SESSION[$name];
                    $sub = $item->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue * $_SESSION['item_'.$subID];
                    $item->getElementsByTagName('prodQty')->item(0)->firstChild->nodeValue -= $_SESSION['item_'.$subID];
                    $xml->Save('products.xml');
                    unset($_SESSION[$name]);
                }
                $total += $sub;
            }
        }
    }
}

//session_unset($_SESSION['loggedUser']);
//session_unset($_SESSION['item_9']);

$custID = $_GET['custID'];
$custName = $_GET['custName'];
$saveTrans = new DOMDocument();
$saveTrans->formatOutput = true;
$saveTrans->preserveWhiteSpace = false;
$saveTrans->Load('admin/transactions.xml');
$transactions = $saveTrans->getElementsByTagName('transactions')->item(0);
$transaction = $transactions->getElementsByTagName('transaction');

$newTrans = $saveTrans->createElement('transaction');
if ($transaction->length == 0) {
    $transID = 1;
} else {
    $transID = $transaction->length + 1;
}
$newTrans->setAttribute('transID', $transID);
$newCustID = $saveTrans->createElement('custID');
$newCustID->appendChild($saveTrans->createTextNode($custID));
$newCustName = $saveTrans->createElement('custName');
$newCustName->appendChild($saveTrans->createTextNode($custName));
$newTotal = $saveTrans->createElement('custTotal');
$newTotal->appendChild($saveTrans->createTextNode($total));
$newDate = $saveTrans->createElement('custDate');
date_default_timezone_set('Asia/Manila');
$newDate->appendChild($saveTrans->createTextNode(date("Y/m/d h:i:s A")));

$newTrans->appendChild($newCustID);
$newTrans->appendChild($newCustName);
$newTrans->appendChild($newTotal);
$newTrans->appendChild($newDate);

for ($i = 0; $i < $itemsTotal; $i++) {
    $newProd = $saveTrans->createElement('product');
    $newItemName = $saveTrans->createElement('prodName');
    $newItemName->appendChild($saveTrans->createTextNode($pName[$i]));
    $newItemQty = $saveTrans->createElement('prodQty');
    $newItemQty->appendChild($saveTrans->createTextNode($pQty[$i]));
    $newProd->appendChild($newItemName);
    $newProd->appendChild($newItemQty);
    $newTrans->appendChild($newProd);
}

$transactions->appendChild($newTrans);

echo "Success";

$saveTrans->Save('admin/transactions.xml');
?>