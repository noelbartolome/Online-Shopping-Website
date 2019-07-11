<?php
session_start();
$xml = new DOMDocument();
$xml->Load('products.xml');
$products = $xml->getElementsByTagName('products')->item(0);
$product = $products->getElementsByTagName('product');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>GadgetIO</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <link rel="shortcut icon" type="image/x-icon" href="images/tab-GIO.png">
        <script src="script.js"></script>
    </head>
    <body onload="showSlides(1); initAll();">
       <div id="modalContainer">
           <div id="modalContent">
                <div class="closeModal" id="cmodal1" onclick="closeModal();">&times;</div>
                <div id="modalImg-container">
                    <img id="modal-img" style="width: 170px; height: 200px; margin-top: 15px;">
                    <p id="modal-pname">Item added to cart</p>
                </div>
                <div id="modalSummary">
                    <table id="modal-table">
                        <tr>
                            <td style="font-weight: bold;">PRICE:</td>
                            <td id="modal-price" style="text-align: right;">PHP 0</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">ITEMS ON CART:</td>
                            <td id="modal-items" style="text-align: right;"> ITEMS</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">SUBTOTAL:</td>
                            <td id="modal-total" style="text-align: right;">PHP 69,999</td>
                        </tr>
                        <tr>
                            <td><input type="button" id="cmodal2" value="CONTINUE SHOPPING" onclick="closeModal()"></td>
                            <td><input type="button" value="GO TO CART" style="float: right;" onclick="Cart(1, 143)"></td>
                        </tr>
                    </table>
                </div>
           </div>
       </div>
        <header>
            <div id="navContainer">
                <div id="logoContainer" style="margin-left:55px;">
               <div id="animatingLogo" onclick="Cart(1, 2)">
                    <div class="phone">
                        <div class="gadgetio"><span>G</span></div>
                        <div class="homebtn"></div>
                    </div>
                    <div id="adgetio">ADGET<span>.iO</span></div>
               </div>
                </div>
                <div id="searchContainer">
                    <form>
                        <input type="text" id="txtsearch" spellcheck="false" autocomplete="off" onclick="this.select();" placeholder="Search Products"/>
                        <img onclick="searchItem()" src="images/search%20(2).png">
                    </form>
                </div>
                <div id="wrap-options">
                    <ul>
                        <li id="badge" onclick="Cart(1, 143); clearInterval(autoLipat);" data-badge="<?php
                        $noOfCart = 0;
                        foreach ($_SESSION as $name=>$value) {
                            if ($value > 0) {
                                if (substr($name,0,5) == 'item_') {
                                    $noOfCart += $_SESSION[$name];
                                }
                            }
                        }
                        echo $noOfCart;
                        ?>"><img src="images/shopping-cart%20(1).png" ><span>CART</span></li>
                        <li
                        <?php 
//                        if (!isset($_SESSION['loggedUser'])) {
//                            echo 'onclick="Cart(1, 8);"';
//                        } else {
                            echo 'onclick="wishlist(1, 2);"';
//                        }
                        ?>
                        ><img src="images/like.png"><span>WISHLIST</span></li>
                        <li><img src="images/user.png">
                            <span>
                                MY ACCOUNT &#x25BC;
                                <div id="myAccount-dropdown">
                                    <?php
                                    if (!isset($_SESSION['loggedUser'])) {
                                        echo '<a onclick="Cart(1,8);">Login / Sign Up</a>';
                                    } else {
                                        echo '<a onclick="logoutUser();">Logout</a>';
                                    }
                                    ?>                                    
                                </div>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="catnavContainer">
                <div style="width:80%; height:100%; margin:auto;">
                    <ul>
                        <?php
                        $categories = $products->childNodes;
                        foreach ($categories as $category) {
                            if ($category->nodeType == 1) {
                                echo '<li onclick="loadCategory('."'".$category->nodeName."'".')">'.$category->nodeName.'</li>';
                            }                            
                        }
                        ?>
                    </ul>
                </div>                
            </div>
        </header>
        <div id="mainContainer">
            <div id="productSlider" onload="showSlides(1)">
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
            
            <div id="featuredDiv">
                <?php
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
                        echo '<div class="featuredProduct" incre="'.$prodLen.'">
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
                                    echo '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                                } else {
                                    foreach ($user as $cust) {
                                        $custEmail = $cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue;
                                        if ($custEmail == $_SESSION['loggedUser']) {
                                            $flag = 0;
                                            $wishlen = $cust->getElementsByTagName('wishitem');
                                            foreach ($wishlen as $wish) {
                                                $wishID = $wish->getElementsByTagName('prodID')->item(0)->firstChild->nodeValue;
                                                if ($wishID == $product->item($i)->getAttribute("prodID")) {
                                                    echo '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like-1.png"/>';
                                                    $flag = 1;
                                                }
                                            }
                                            
                                            if ($flag == 0) {
                                                echo '<img id="heart_'.$product->item($i)->getAttribute("prodID").'" src="images/like.png"/>';
                                            }
                                        }
                                    }
                                }
                    
                            echo '</div>
                            <div class="featuredTitle">'.$product->item($i)->getElementsByTagName('prodName')->item(0)->childNodes->item(0)->nodeValue.'</div>
                        </div>';
                        
                        if ($ctr == 15) break;
                    }                
//                }
                ?>
            </div>
            <div id="page_btn-container">
                <?php
                $pages = ceil($prodLen / 15);
                if ($pagenum > 1) {
                    echo '<button onclick="paginateTo('.($pagenum - 1).', 1)">PREV</button>';
                    echo '<button onclick="paginateTo('.($pagenum - 1).', 1)">'.($pagenum - 1).'</button>';
                }                    
                echo '<button class="currentPagenum" onclick="paginateTo('.$pagenum.', 1)">'.$pagenum.'</button>';
                if ($pagenum < $pages) {
                    echo '<button onclick="paginateTo('.($pagenum + 1).', 1)">'.($pagenum + 1).'</button>';
                    echo '<button onclick="paginateTo('.($pagenum + 1).', 1)">NEXT</button>';
                }
                ?>
            </div>
        </div>
        <footer>
            <div id="footContainer">
                <div id="catOnFooter">
                    <p>CATEGORIES</p>
                    <ul>
                        <?php
                        $categories = $products->childNodes;
                        foreach ($categories as $category) {
                            if ($category->nodeType == 1) {
                                echo '<li onclick="loadCategory('."'".$category->nodeName."'".')">'.$category->nodeName.'</li>';
                            }                            
                        }
                        ?>
                    </ul>
                </div>
                <div id="socialMedia">
                    <p>LET'S BE FRIENDS</p>
                    <ul>
                        <li><img src="images/facebook%20(1).png"><span>www.facebook.com/GadgetIO</span></li>
                        <li><img src="images/instagram%20(1).png"><span>instagram.com/GadgetIO</span></li>
                        <li><img src="images/google-plus%20(1).png"><span>plus.google.com/+GadgetIO</span></li>
                    </ul>
                </div>
                <div id="copyright">
                    <p>Copyright &copy; 2018 GadgetIO. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>