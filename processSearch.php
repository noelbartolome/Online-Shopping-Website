<?php
session_start();
$xml = new DOMDocument();
$xml->Load('products.xml');
$products = $xml->getElementsByTagName('products')->item(0);
$product = $products->getElementsByTagName('product');
$txtToSearch = $_GET['txtsearch'];
$featuredDiv = '<div id="featuredDiv-search">
                    <div id="resultfor">Results for "'.$txtToSearch.'"</div>';
$flags = 0;
$anak = 0;

foreach ($product as $item) {
    $prod_item = $item->getElementsByTagName('prodName')->item(0)->firstChild->nodeValue;
    if (stripos($prod_item, $txtToSearch) !== false) {
        $anak++;
        if ($anak == 5) {
            $featuredDiv .= '<div class="featuredProduct" style="margin-right:0;">';
            $anak = 0;
        } else {
            $featuredDiv .= '<div class="featuredProduct" style="margin-right:10px;">';
        }
        $featuredDiv .= '<div class="featuredImg-wrap">
                            <div class="featuredImg">
                                <img src="'.$item->getElementsByTagName('prodImg')->item(0)->childNodes->item(0)->nodeValue.'">
                            </div>
                            <div class="middle" onclick="Cart('.$item->getAttribute("prodID").", 1".')">
                                <div class="viewDetails">ADD TO CART</div>
                            </div>
                        </div>
                        <div class="featuredPrice">PHP '.number_format($item->getElementsByTagName('prodPrice')->item(0)->childNodes->item(0)->nodeValue, 2).'</div>
                        <div class="featuredCart" onclick="Cart('.$item->getAttribute("prodID").", 1".')">
                            <img src="images/shopping-cart.png"/>
                        </div>
                        <div class="featuredWish" onclick="wishlist('.$item->getAttribute("prodID").", 1".')">';

                        if (!isset($_SESSION['loggedUser'])) {
                                $featuredDiv .= '<img id="heart_'.$item->getAttribute("prodID").'" src="images/like.png"/>';
                            } else {
                                $openU = new DOMDocument();
                                $openU->formatOutput = true;
                                $openU->preserveWhiteSpace = false;
                                $openU->Load('users.xml');
                                $users = $openU->getElementsByTagName('users')->item(0);
                                $user = $users->getElementsByTagName('user');
                                foreach ($user as $cust) {
                                    $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
                                    if ($custEmail == $_SESSION['loggedUser']) {
                                        $flag = 0;
                                        $wishlen = $cust->getElementsByTagName('wishitem');
                                        foreach ($wishlen as $wish) {
                                            $wishID = $wish->getElementsByTagName('prodID')->item(0)->firstChild->nodeValue;
                                            if ($wishID == $item->getAttribute("prodID")) {
                                                $featuredDiv .= '<img id="heart_'.$item->getAttribute("prodID").'" src="images/like-1.png"/>';
                                                $flag = 1;
                                            }
                                        }

                                        if ($flag == 0) {
                                            $featuredDiv .= '<img id="heart_'.$item->getAttribute("prodID").'" src="images/like.png"/>';
                                        }
                                    }
                                }
                            }

                        $featuredDiv .= '</div>
                        <div class="featuredTitle">'.$item->getElementsByTagName('prodName')->item(0)->childNodes->item(0)->nodeValue.'</div>
                    </div>';
        $flags++;
    }
}

if ($flags == 0) {
    $featuredDiv = '<div id="featuredDiv-search">
                        <div id="noresult-img">
                            <img src="images/growth.png" style="width:128px; height:128px; margin-top:100px;">
                        </div>
                        <div id="noresult-msg">
                            <div>We\'re sorry. We cannot find any matches for your search term.</div>
                            <button id="noresult-btn" onclick="Cart(1, 2)">Continue Shopping</button>
                        </div>';
}
echo $featuredDiv .= '</div>';
?>