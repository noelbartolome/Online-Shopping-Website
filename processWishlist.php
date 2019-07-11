<?php
session_start();
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->Load('users.xml');
$users = $xml->getElementsByTagName('users')->item(0);
$user = $users->getElementsByTagName('user');
$cmdCode = $_GET['cmdCode'];

//Add/remove item from wishlist
if ($cmdCode == 1) {
    $prodID = $_GET['prodID'];
    $openC = new DOMDocument();
    $openC->formatOutput = true;
    $openC->reserveWhiteSpace = false;
    $openC->Load('products.xml');
    $products = $openC->getElementsByTagName('products')->item(0);
    $product = $products->getElementsByTagName('product');
    
    if (isset($_SESSION['loggedUser'])) {
        foreach ($product as $item) {
            if ($prodID == $item->getAttribute('prodID')) {
                foreach ($user as $cust) {
                    if ($cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue == $_SESSION['loggedUser']) {
                        $wishlist_len = $cust->getElementsByTagName('wishitem');
                        $flag = 0;

                        foreach ($wishlist_len as $wishlist) {
                            if ($wishlist->getElementsByTagName("prodID")->item(0)->firstChild->nodeValue == $prodID) {
                                $flag = 1;
                                $cust->removeChild($wishlist);
                                echo 'Existing';
                            }
                        }

                        if ($flag == 0) {
                            $newWish = $xml->createElement('wishitem');
                            $newID = $xml->createElement('prodID');
                            $newID->appendChild($xml->createTextNode($prodID));
                            $newName = $xml->createElement('prodName');
                            $newName->appendChild($xml->createTextNode($item->getElementsByTagName('prodName')->item(0)->firstChild->nodeValue));
                            $newPrice = $xml->createElement('prodPrice');
                            $newPrice->appendChild($xml->createTextNode($item->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue));
                            $newImage = $xml->createElement('prodImg');
                            $newImage->appendChild($xml->createTextNode($item->getElementsByTagName('prodImg')->item(0)->firstChild->nodeValue));

                            $newWish->appendChild($newID);
                            $newWish->appendChild($newName);
                            $newWish->appendChild($newPrice);
                            $newWish->appendChild($newImage);
                            $cust->appendChild($newWish);
                            echo 'Added';                            
                        }
                        $xml->Save('users.xml');
                    }
                }
            }
        }
    } else {
        echo 'logFirst';
    }
}

//Show wishlist page
elseif ($cmdCode == 2) {
    if (!isset($_SESSION['loggedUser'])) {
        echo 'logFirst';
    } else {
        $flag = 0;
        foreach ($user as $cust) {
            $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
            if ($_SESSION['loggedUser'] == $custEmail) {
                $wishlist_len = $cust->getElementsByTagName('wishitem');
                foreach ($wishlist_len as $wishlist) {
                    $flag = 1;
                }
            }
        }
        
        if ($flag == 0) {
            $wishpage = '<div id="CWODiv" onload="clearInterval(autoLipat);">
                        <div id="noitem-container">
                            <div id="noitem-imgContainer">
                                <img src="images/broken-heart.png" style="width: 128px;height: 128px;margin-left: 133px;">
                                <p>Your wishlist is empty.</p>
                                <p>Go shopping</p>
                                <input type="button" style="cursor:pointer;" onclick="Cart(1,2)" value="VIEW ITEMS">
                            </div>
                        </div>
                    </div>';
        } else {
            $wishpage = '<div id="wishlistDiv">
                        <div id="wishTitle">
                            <div id="cart-backToHome" onclick="Cart(1, 2)">❮ Continue shopping</div>
                            <div id="cart-title">Wishlist</div>
                        </div>
                        <div id="wishContent">
                            <div id="cart-rowHeader">
                                <div id="cart-headItem" style="width:36%;">ITEM</div>
                                <div id="cart-headPrice" style="width:27%;">PRICE</div>
                                <div id="cart-headAction" style="width:36%;">ACTION</div>
                            </div>
                            <div id="tableContainer">
                                <table id="item-table">';
                                                         
                                    foreach ($user as $cust) {
                                        $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
                                        if ($_SESSION['loggedUser'] == $custEmail) {
                                            $wishlist_len = $cust->getElementsByTagName('wishitem');
                                            
                                            foreach ($wishlist_len as $wishlist) {
                                                $wishID = $wishlist->getElementsByTagName('prodID')->item(0)->firstChild->nodeValue;
                                                $wishpage .= '<tr class="cart-item-row" id="row_'.$wishID.'">
                                                                <td class="cart-item-image" style="width:10%;">
                                                                    <img src="'.$wishlist->getElementsByTagName("prodImg")->item(0)->firstChild->nodeValue.'">
                                                                </td>
                                                                <td class="cart-item-name" style="width:18%;">'.
                                                                    $wishlist->getElementsByTagName('prodName')->item(0)->firstChild->nodeValue
                                                                .'</td>
                                                                <td class="cart-item-price" style="width:10%;">PHP '.
                                                                    number_format($wishlist->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue,2)
                                                                .'</td>
                                                                <td class="cart-item-action1" style="width:23%;">
                                                                    <button style="background-color:#0181c4; border-color:#0181c4; width:40%;" onclick="wishRem_toCart('.$wishID.')">ADD TO CART</button>
                                                                    <button onclick="wishlist('.$wishID.', 1, 1)" style="width:40%;">REMOVE</button>
                                                                </td>
                                                            </tr>';                                                
                                            }
                                        }
                                    }

                                $wishpage .= '</table>
                            </div>
                        </div>';
        }
                        
        echo $wishpage;
    }        
} else if ($cmdCode == 3) {
    unset($_SESSION['loggedUser']);
    foreach ($_SESSION as $name=>$value) {
        if ($value > 0) {
            if (substr($name,0,5) == "item_") {
                unset($_SESSION[$name]);
            }
        }
    }
    echo 'logout';
} else if ($cmdCode == 4) {
    $prodID = $_GET['prodID'];
    foreach ($user as $cust) {
        if ($cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue == $_SESSION['loggedUser']) {
            $wishlist_len = $cust->getElementsByTagName('wishitem');
            foreach ($wishlist_len as $wishlist) {
                $wishID = $wishlist->getElementsByTagName('prodID')->item(0)->firstChild->nodeValue;
                if ($wishID == $prodID) {
                    $cust->removeChild($wishlist);
                    break;
                }
            }
        }
    }
    $xml->Save('users.xml');
}
?>