<?php
session_start();
$cmdCode = $_GET['cmdCode'];
$xml = new DOMDocument();
$xml->Load('products.xml');
$products = $xml->getElementsByTagName('products')->item(0);
$product = $products->getElementsByTagName('product');
$sub = 0;
$total = 0;

if ($cmdCode == 1) {      
    $prodID = $_GET['prodID'];
    $totalItems = 0;
    $totalPrice = 0;
    $sub = 0;
    $flag = 0;
    
    foreach ($product as $item) {
        if ($item->getAttribute('prodID') == $prodID) {
            if (!isset($_SESSION['item_'.$prodID])) {
                $_SESSION['item_'.$prodID] = 0;
            }
            if ($item->getElementsByTagName('prodQty')->item(0)->childNodes->item(0)->nodeValue != $_SESSION['item_'.$prodID]) {
                $_SESSION['item_'.$_GET['prodID']] += '1';
                $prodName = $item->getElementsByTagName('prodName')->item(0)->firstChild->nodeValue;
                $prodPrice = $item->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue;
                $prodImage = $item->getElementsByTagName('prodImg')->item(0)->firstChild->nodeValue;
            } else {
                $flag = 1;
            }
        }
    }
    
    foreach ($_SESSION as $name=>$value) {
        if ($value > 0) {
            if (substr($name,0,5) == "item_") {
                foreach ($product as $citem) {
                    $prodID = substr($name,5,strlen($name)-5);
                    $sub = 0;
                    if ($citem->getAttribute('prodID') == $prodID) {
                        $sub = $citem->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue * $_SESSION[$name];
                    }
                    $totalPrice += $sub;
                }
                $totalItems += $_SESSION[$name];
            }
        }
    }
    
    if ($flag == 1) {
        echo "0";
    } else {
        echo '1*'.$prodName.'*'.number_format($prodPrice, 2).'*'.$prodImage.'*'.number_format($totalPrice, 2).'*'.$totalItems;
    }    
}

else if ($cmdCode == 2) {
    $homepage = '<div id="productSlider"  onload="showSlides(1)">
                <div class="mySlides fade">
                    <img src="images/galaxy-star-home-combo-type2-pc.jpg" style="width:100%; height:100%;">
                </div>
                <div class="mySlides fade">
                    <img src="new/BANNER(1200X400)_s9+_ENG_PHONEGALLERY.jpg" style="width:100%; height:100%;">
                </div>                
                <div class="mySlides fade">
                    <img src="new/BANNER(1200X400)_s9_ENG_PHONEGALLERY.jpg" style="width:100%; height:100%;">
                </div>   
                <div class="mySlides fade">
                    <img src="new/Banner-vc-1200x400.jpg" style="width:100%; height:100%;">
                </div>   
                <div class="mySlides fade">
                    <img src="new/Banner_4.jpg" style="width:100%; height:100%;">
                </div>   
                <div class="mySlides fade">
                    <img src="new/Banner_6.jpg" style="width:100%; height:100%;">
                </div>     
                <div class="mySlides fade">
                    <img src="new/HTB1zIvgepGWBuNjy0Fbq6z4sXXa4.jpg" style="width:100%; height:100%;">
                </div>   
                <div class="mySlides fade">
                    <img src="new/Samsung-wave-2-1200x400.jpg" style="width:100%; height:100%;">
                </div>   
                <a class="prev" onclick="plusSlides(-1); clearInterval(autoLipat);">&#10094;</a>
                <a class="next" onclick="plusSlides(1); clearInterval(autoLipat);">&#10095;</a>
                <div style="text-align:center;" id="dotContainer">
                    <span class="dot" onclick="currentSlide(1)"></span> 
                    <span class="dot" onclick="currentSlide(2)"></span> 
                    <span class="dot" onclick="currentSlide(3)"></span> 
                    <span class="dot" onclick="currentSlide(4)"></span> 
                    <span class="dot" onclick="currentSlide(5)"></span> 
                    <span class="dot" onclick="currentSlide(6)"></span> 
                    <span class="dot" onclick="currentSlide(7)"></span> 
                    <span class="dot" onclick="currentSlide(8)"></span> 
                    <span class="dot" onclick="currentSlide(9)"></span> 
                    <span class="dot" onclick="currentSlide(10)"></span> 
                </div>
            </div>
            <div id="catPics">
                <div style="background-image: url(images/categories-p1.jpg);"></div>
                <div style="background-image: url(images/categories-p2.jpg);"></div>
                <div style="background-image: url(images/categories-p3.jpg);"></div>
                <div style="background-image: url(images/categories-p4.jpg);"></div>
            </div>
            <div id="voucherDiv">
                <div class="voucher" id="voucher1"></div>
                <div class="voucher" id="voucher2"></div>
            </div>
            
            <div id="newArrivalTitle">
                <img src="images/star%20(1).png" style="width:32px; height:32px; float:left;" />
                <span>New Arrivals</span>
            </div>
            
            <div id="featuredDiv">';
                
//                foreach ($product as $prod) {
                    $openU = new DOMDocument();
                    $openU->formatOutput = true;
                    $openU->preserveWhiteSpace = false;
                    $openU->Load('users.xml');
                    $users = $openU->getElementsByTagName('users')->item(0);
                    $user = $users->getElementsByTagName('user');
    
                    $pagenum = 1;
                    $i = $pagenum > 1 ? ($pagenum - 1) * 15 : $pagenum - 1;
                    $prodLen = 0;
    
                    foreach ($product as $categories) {
                        $prodLen += 1;
                    }
                    
                    for ($ctr = 1; $i < $prodLen; $i++, $ctr++) {
                    $homepage.='<div class="featuredProduct">
                            <div class="featuredImg-wrap">
                                <div class="featuredImg">
                                    <img src="'.$product->item($i)->getElementsByTagName('prodImg')->item(0)->childNodes->item(0)->nodeValue.'">
                                </div>
                                <div class="middle" onclick="Cart('.$product->item($i)->getAttribute("prodID").", 1".')">
                                    <div class="viewDetails">ADD TO CART</div>
                                </div>
                            </div>
                            <div class="featuredPrice">PHP '.number_format($product->item($i)->getElementsByTagName('prodPrice')->item(0)->childNodes->item(0)->nodeValue, 2).'</div>
                            <div class="featuredCart" onclick="Cart('.$product->item($i)->getAttribute("prodID").", 1".')">
                                <img src="images/shopping-cart.png"/>
                            </div>
                            <div class="featuredWish" onclick="wishlist('.$product->item($i)->getAttribute("prodID").", 1".')">';
                    
                                if (!isset($_SESSION['loggedUser'])) {
                                    $homepage .= '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                                } else {
                                    foreach ($user as $cust) {
                                        $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
                                        if ($custEmail == $_SESSION['loggedUser']) {
                                            $flag = 0;
                                            $wishlen = $cust->getElementsByTagName('wishitem');
                                            foreach ($wishlen as $wish) {
                                                $wishID = $wish->getElementsByTagName('prodID')->item(0)->firstChild->nodeValue;
                                                if ($wishID == $product->item($i)->getAttribute("prodID")) {
                                                    $homepage .= '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like-1.png"/>';
                                                    $flag = 1;
                                                }
                                            }
                                            
                                            if ($flag == 0) {
                                                $homepage .= '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                                            }
                                        }
                                    }
                                }
                    
                            $homepage .= '</div>
                            <div class="featuredTitle">'.$product->item($i)->getElementsByTagName('prodName')->item(0)->childNodes->item(0)->nodeValue.'</div>
                        </div>';
                        
                        if ($ctr == 15) break;
                    }
//                }
            $homepage .= '</div>';
            $homepage .= '<div id="page_btn-container">';
            $pages = ceil($prodLen / 15);
            if ($pagenum > 1) {
                $homepage .= '<button onclick="paginateTo('.($pagenum - 1).', 1)">PREV</button>
                                <button onclick="paginateTo('.($pagenum - 1).', 1)">'.($pagenum - 1).'</button>';
            }
            $homepage .= '<button class="currentPagenum" onclick="paginateTo('.$pagenum.', 1)">'.$pagenum.'</button>';// EXPERIMENTAL
            if ($pagenum < $pages) {
                $homepage .= '<button onclick="paginateTo('.($pagenum + 1).', 1)">'.($pagenum + 1).'</button>
                                <button onclick="paginateTo('.($pagenum + 1).', 1)">NEXT</button>';
            }
            $homepage .= '</div>';
    echo $homepage;
}

else if ($cmdCode == 143) {
    $flag = 0;
    foreach ($_SESSION as $name=>$value) {
        if ($value > 0) {
            if (substr($name,0,5) == "item_") {
                $flag = 1;
            }
        }
    }
    
    if ($flag == 0) {        
        $homepage = '<div id="CWODiv" onload="clearInterval(autoLipat);">
                        <div id="noitem-container">
                            <div id="noitem-imgContainer">
                                <img src="images/cart_empty.png" style="width: 150px;height: 115px;margin-left: 115px;">
                                <p>Your shopping cart is empty.</p>
                                <p>Go shopping</p>
                                <input type="button" style="cursor:pointer;" onclick="Cart(1,2)" value="VIEW ITEMS">
                            </div>
                        </div>
                    </div>';
    } else {
        $homepage = '<div id="CWODiv" onload="clearInterval(autoLipat);">
                <div id="cart-itemsContainer">
                    <div id="cart-titleDiv">
                        <div id="cart-backToHome" onclick="Cart(1, 2)">❮ Continue shopping</div>
                        <div id="cart-title">Cart</div>
                    </div>
                    <div id="cart-rowHeader">
                        <div id="cart-headItem">ITEM</div>
                        <div id="cart-headPrice">PRICE</div>
                        <div id="cart-headQty">QUANTITY</div>
                        <div id="cart-headAction">ACTION</div>
                    </div>
                    
                    <div id="tableContainer">
                        <table id="item-table">';
        
                        foreach ($_SESSION as $name=>$value) {
                            if ($value > 0) {
                                if (substr($name,0,5) == "item_") {
                                    $prodID = substr($name,5,strlen($name)-5);
                                    foreach ($product as $item) {
                                        if ($item->getAttribute('prodID') == $prodID) {
                                            $sub = 0;
                                            $sub = $item->getElementsByTagName("prodPrice")->item(0)->childNodes->item(0)->nodeValue * $_SESSION['item_'.$prodID];
                                            $homepage .= '<tr id="row_'.$prodID.'" class="cart-item-row">
                                                    <td class="cart-item-image">
                                                        <img src="'.$item->getElementsByTagName("prodImg")->item(0)->childNodes->item(0)->nodeValue.'"/>
                                                    </td>
                                                    <td class="cart-item-name">'.$item->getElementsByTagName("prodName")->item(0)->childNodes->item(0)->nodeValue.'
                                                        <p style="margin: 5px 0px;">PHP '.number_format($item->getElementsByTagName("prodPrice")->item(0)->childNodes->item(0)->nodeValue,2).'</p>
                                                    </td>
                                                    <td id="CPItem_'.$prodID.'" class="cart-item-price">PHP '.number_format($item->getElementsByTagName("prodPrice")->item(0)->firstChild->nodeValue * $_SESSION['item_'.$prodID], 2).'</td>
                                                    <td class="cart-item-qty">
                                                        <input type="button" id="min_'.$prodID.'" class="minusqty" onclick="qtyCtrl(0, '.$prodID.')" value="-">
                                                        <input type="text" id="'.$prodID.'" onclick="this.select()" onkeyup="qtyCtrl(2, '.$prodID.')" class="qtynumber" value="'.$_SESSION["item_".$prodID].'">
                                                        <input type="button" id="plus_'.$prodID.'" class="plusqty" onclick="qtyCtrl(1, '.$prodID.')" value="+"></td>
                                                    <td class="cart-item-action">
                                                        <button class="cart-item-action_btn" onclick="removeItemCart('.$prodID.')">REMOVE</button>
                                                    </td>
                                                </tr>';
                                            $total += $sub;
                                        }                                        
                                    }                                
                                }
                            }
                        }                            
                        
                        $homepage .= '</table>
                    </div>                    
                    
                </div>
                <div id="cart-totalDiv">
                    <div id="cart-title-os">Order Summary</div>
                    <div id="cart-subDiv">
                        <div id="spanSubTotal"><span>SUBTOTAL</span><span id="price1">PHP '.number_format($total, 2);
                        $homepage .= '</span></div>
                        <div id="spanShipping"><span>SHIPPING FEE</span><span>FREE</span></div>
                        <div id="modeOfPayment">
                        <p style="display: inline-block; line-height: 35px; font-weight: bold; float: left; text-indent: 18px;">MODE OF PAYMENT</p>
                            <img src="images/paypal.png" style="width:64px height:64px;">
                        </div>
                    </div>
                    <div id="totalDiv"><span>TOTAL</span><span id="price2">PHP '.number_format($total, 2);
                    $homepage .= '</span></div>
                    <div id="btnProceedCheckout"><button ';
        
                        if (!isset($_SESSION['loggedUser'])) {
                            $homepage .= 'onclick="Cart(1, 8);"';
                        } else {
                            $getUser = new DOMDocument();
                            $getUser->formatOutput = true;
                            $getUser->preserveWhiteSpace = false;
                            $getUser->Load('users.xml');
                            $users = $getUser->getElementsByTagName('users')->item(0);
                            $user = $users->getElementsByTagName('user');

                            foreach ($user as $cust) {
                                $custID = $cust->getAttribute('custID');
                                $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
                                $custName[0] = "'".$cust->getElementsByTagName('fullName')->item(0)->firstChild->nodeValue."'";
                                if ($_SESSION['loggedUser'] == $custEmail) {
                                    $homepage .= 'onclick="processCheckout('.$custID.', '.$custName[0].');"';
                                }
                            }                            
                        }
                        
                        $homepage .= '>PROCEED TO CHECKOUT</button>
                    </div>
                </div>
            </div>';
    }                            
    echo $homepage;
}

else if ($cmdCode == 4) {
    $prodID = $_GET['prodID'];
    $qtyValue = $_GET['qtyValue'];
    $_SESSION['item_'.$prodID] = $qtyValue;
    $CPItem = 0;
    $noOfCart = 0;        
    
    foreach ($_SESSION as $name=>$value) {
        if ($value > 0) {
            if (substr($name,0,5) == 'item_') {
                $noOfCart += $_SESSION[$name];
                foreach ($product as $item) {
                    if ($item->getAttribute("prodID") == $prodID) {
                        $CPItem = $item->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue * $_SESSION['item_'.$prodID];
                    }
                }
            }
        }
    }
    echo $noOfCart.'*'.number_format($CPItem, 2);
}

else if ($cmdCode == 5) {
    $itemRemove = $_GET['itemRemove'];
    $_SESSION[$itemRemove] = 0;
    $noOfCart = 0;
    
    foreach ($_SESSION as $name=>$value) {
        if ($value > 0) {
            if (substr($name,0,5) == 'item_') {
                $noOfCart += $_SESSION[$name];                
            }
        }
    }
    echo $noOfCart;
}

else if ($cmdCode == 6) {
    $prodID = $_GET['prodID'];
    $total = 0;
    foreach ($_SESSION as $name=>$value) {
        if ($value > 0) {
            if (substr($name,0,5) == 'item_') {
                $prodID = substr($name,5,strlen($name)-5);
                foreach ($product as $item) {
                    $sub = 0;
                    if ($item->getAttribute('prodID') == $prodID) {
                        $sub = $item->getElementsByTagName('prodPrice')->item(0)->firstChild->nodeValue * $_SESSION[$name];
                    }
                    $total += $sub;
                }
            }
        }
    }
    echo 'PHP '.number_format($total, 2);
}

else if ($cmdCode == 7) {
    $noOfCart = 0;
    foreach ($_SESSION as $name=>$value) {
        if ($value > 0) {
            if (substr($name,0,5) == 'item_') {
                $noOfCart += $_SESSION[$name];                
            }
        }
    }
    echo $noOfCart;
}

else if ($cmdCode == 8) {
    echo $homepage = '<div id="form-container">
        <div id="animatingLogo" style="width:269px; height:140px; margin-left:65.5px; margin-top:15px;">
            <div class="phone" style="height:102px; width:60px;">
                <div class="gadgetio" style="width: 50px; height: 84px; top: 5px; left: 5px; line-height:84px;"><span style="font-size:65px; animation-name: growingG-bigger;">G</span></div>
                <div class="homebtn" style="width: 7px; height: 7px; left: 28px; bottom:3px;"></div>
            </div>
            <div id="adgetio" style="height: 102px; line-height: 100px; font-size: 65px;">ADGET<span>.iO</span></div>
        </div>
        
        <div id="form-login">
            <input type="text" name="login-email" placeholder="Email" required>
            <input type="password" name="login-password" placeholder="Password" required>
            <input type="button" name="login-button" onclick="processLogin(1)" value="LOGIN">
            <p>Don\'t have an account? <span onclick="activateSignUp(1);">Sign Up</span></p>
        </div>

        <div id="form-signup">
            <input type="text" name="signup-name" placeholder="Full Name" required>
            <input type="text" name="signup-email" placeholder="Email" required>
            <input type="password" name="signup-password" placeholder="Password" required>
            <input type="password" name="signup-repassword" placeholder="Retype Password" required>
            <input type="button" name="signup-button" onclick="processSignup(2)" value="SIGN UP">
            <p>Already have an account? <span onclick="activateSignUp(0);">Login</span></p>
        </div>
    </div>';
}

else if ($cmdCode == 9) {
    $subCmd = $_GET['subcmd'];
    if ($subCmd == 1) {
        $openU = new DOMDocument();
        $openU->formatOutput = true;
        $openU->preserveWhiteSpace = false;
        $openU->Load('users.xml');
        $users = $openU->getElementsByTagName('users')->item(0);
        $user = $users->getElementsByTagName('user');

        $pagenum = $_GET['pagenum'];
        $i = $pagenum > 1 ? ($pagenum - 1) * 15 : $pagenum - 1;
        $prodLen = 0;

        foreach ($product as $categories) {
            $prodLen += 1;
        }

        $homepage = "";
        for ($ctr = 1; $i < $prodLen; $i++, $ctr++) {
            $homepage .= '<div class="featuredProduct">
                        <div class="featuredImg-wrap">
                            <div class="featuredImg">
                                <img src="'.$product->item($i)->getElementsByTagName('prodImg')->item(0)->childNodes->item(0)->nodeValue.'">
                            </div>
                            <div class="middle" onclick="Cart('.$product->item($i)->getAttribute("prodID").", 1".')">
                                <div class="viewDetails">ADD TO CART</div>
                            </div>
                        </div>
                        <div class="featuredPrice">PHP '.number_format($product->item($i)->getElementsByTagName('prodPrice')->item(0)->childNodes->item(0)->nodeValue, 2).'</div>
                        <div class="featuredCart" onclick="Cart('.$product->item($i)->getAttribute("prodID").", 1".')">
                            <img src="images/shopping-cart.png"/>
                        </div>
                        <div class="featuredWish" onclick="wishlist('.$product->item($i)->getAttribute("prodID").", 1".')">';

                if (!isset($_SESSION['loggedUser'])) {
                        $homepage .= '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                } else {
                    foreach ($user as $cust) {
                        $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
                        if ($custEmail == $_SESSION['loggedUser']) {
                            $flag = 0;
                            $wishlen = $cust->getElementsByTagName('wishitem');
                            foreach ($wishlen as $wish) {
                                $wishID = $wish->getElementsByTagName('prodID')->item(0)->firstChild->nodeValue;
                                if ($wishID == $product->item($i)->getAttribute("prodID")) {
                                    $homepage .= '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like-1.png"/>';
                                    $flag = 1;
                                }
                            }

                            if ($flag == 0) {
                                $homepage .= '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                            }
                        }
                    }
                }

                $homepage .= '</div>
                <div class="featuredTitle">'.$product->item($i)->getElementsByTagName('prodName')->item(0)->childNodes->item(0)->nodeValue.'</div>
            </div>';

            if ($ctr == 15) {
                break;
            }
        }
    
        $pageBtn = "";
        $pages = ceil($prodLen / 15);
        if ($pagenum > 1) {
            $pageBtn .= '<button onclick="paginateTo('.($pagenum - 1).', 1)">PREV</button>';
            $pageBtn .= '<button onclick="paginateTo('.($pagenum - 1).', 1)">'.($pagenum - 1).'</button>';
        }                    
        $pageBtn .= '<button class="currentPagenum" onclick="paginateTo('.$pagenum.', 1)">'.$pagenum.'</button>';
        if ($pagenum < $pages) {
            $pageBtn .= '<button onclick="paginateTo('.($pagenum + 1).', 1)">'.($pagenum + 1).'</button>';
            $pageBtn .= '<button onclick="paginateTo('.($pagenum + 1).', 1)">NEXT</button>';
        }  

        echo $homepage.'*'.$pageBtn;
    }
}
?>