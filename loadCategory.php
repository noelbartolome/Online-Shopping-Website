<?php
session_start();
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->Load('products.xml');
$products = $xml->getElementsByTagName('products')->item(0);
$categories = $products->childNodes;
$loadCat = $_GET['catName'];

if (isset($_GET['pagenum'])) {
    $pagenum = $_GET['pagenum'];
} else {
    $pagenum = 1;
}
$i = $pagenum > 1 ? ($pagenum - 1) * 15 : $pagenum - 1;
$prodLen = 0;

foreach ($categories as $category) {
    if ($category->nodeName == $loadCat) {        
        $catChilds = $category->getElementsByTagName('product');        
        $openU = new DOMDocument();
        $openU->Load('users.xml');
        $users = $openU->getElementsByTagName('users')->item(0);
        $user = $users->getElementsByTagName('user');
        $catPage = '<div id="featuredDiv-search">';
//        foreach ($catChilds as $prod) {
        
        foreach ($catChilds as $item) {
            $prodLen++;
        }
        
        for ($ctr = 1; $i < $prodLen; $i++, $ctr++) {
            $catPage .= '<div class="featuredProduct">
                            <div class="featuredImg-wrap">
                                <div class="featuredImg">
                                    <img src="'.$catChilds->item($i)->getElementsByTagName('prodImg')->item(0)->childNodes->item(0)->nodeValue.'">
                                </div>
                                <div class="middle" onclick="Cart('.$catChilds->item($i)->getAttribute("prodID").", 1".')">
                                    <div class="viewDetails">ADD TO CART</div>
                                </div>
                            </div>
                            <div class="featuredPrice">PHP '.number_format($catChilds->item($i)->getElementsByTagName('prodPrice')->item(0)->childNodes->item(0)->nodeValue, 2).'</div>
                            <div class="featuredCart" onclick="Cart('.$catChilds->item($i)->getAttribute("prodID").", 1".')">
                                <img src="images/shopping-cart.png"/>
                            </div>
                            <div class="featuredWish" onclick="wishlist('.$catChilds->item($i)->getAttribute("prodID").", 1".')">';
                                
                            if (!isset($_SESSION['loggedUser'])) {
                                    $catPage .= '<img id="heart_'.$catChilds->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                                } else {
                                    foreach ($user as $cust) {
                                        $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
                                        if ($custEmail == $_SESSION['loggedUser']) {
                                            $flag = 0;
                                            $wishlen = $cust->getElementsByTagName('wishitem');
                                            foreach ($wishlen as $wish) {
                                                $wishID = $wish->getElementsByTagName('prodID')->item(0)->firstChild->nodeValue;
                                                if ($wishID == $catChilds->item($i)->getAttribute("prodID")) {
                                                    $catPage .= '<img id="heart_'.$catChilds->item($i)->getAttribute("prodID").'" src="images/like-1.png"/>';
                                                    $flag = 1;
                                                }
                                            }
                                            
                                            if ($flag == 0) {
                                                $catPage .= '<img id="heart_'.$catChilds->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                                            }
                                        }
                                    }
                                }
                    
                            $catPage .= '</div>
                            <div class="featuredTitle">'.$catChilds->item($i)->getElementsByTagName('prodName')->item(0)->childNodes->item(0)->nodeValue.'</div>
                        </div>';
            if ($ctr == 15) break;
        }
//        }
        $catPage .= '</div>';
    }        
}

$catPage .= '<div id="page_btn-container">';
            $pages = ceil($prodLen / 15);
            if ($pagenum > 1) {
                $catPage .= '<button onclick="paginateCat('.($pagenum - 1).', '."'".$loadCat."'".')">PREV</button>
                                <button onclick="paginateCat('.($pagenum - 1).', '."'".$loadCat."'".')">'.($pagenum - 1).'</button>';
            }
            $catPage .= '<button class="currentPagenum" onclick="paginateCat('.$pagenum.', '."'".$loadCat."'".')">'.$pagenum.'</button>';// EXPERIMENTAL
            if ($pagenum < $pages) {
                $catPage .= '<button onclick="paginateCat('.($pagenum + 1).', '."'".$loadCat."'".')">'.($pagenum + 1).'</button>
                                <button onclick="paginateCat('.($pagenum + 1).', '."'".$loadCat."'".')">NEXT</button>';
            }
            $catPage .= '</div>';

echo $catPage;

?>