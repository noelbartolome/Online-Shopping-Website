<?php
session_start();
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->Load('admin.xml');
$admins = $xml->getElementsByTagName('admins')->item(0);
$admin = $admins->getElementsByTagName('admin');
if (isset($_POST['cmdCode'])) {
    $cmdCode = $_POST['cmdCode'];   
} else {
    $cmdCode = $_GET['cmdCode'];
}

if ($cmdCode == 1) {
    $flag = 0;
    $sideBar = '<div id="logoContainer" style="height: 88px; margin-left: 25px;">
                           <div id="animatingLogo" onclick="Cart(1, 2)">
                                <div class="phone">
                                    <div class="gadgetio"><span>G</span></div>
                                    <div class="homebtn"></div>
                                </div>
                                <div id="adgetio">ADGET<span>.iO</span></div>
                           </div>
                        </div>
                        <div class="controls" onclick="loadContent(3);">
                        <img src="../images/folder.png" style="vertical-align: sub; margin-left:45px;">
                        <span style="line-height: 50px;vertical-align: super;height: 40px;margin-left: 15px;display: inline-block;">Add Category<span>
                        </div>
                        <div class="controls" onclick="loadContent(4);">
                        <img src="../images/barcode.png" style="vertical-align: sub; margin-left:45px;">
                        <span style="line-height: 50px;vertical-align: super;height: 40px;margin-left: 15px;display: inline-block;">Add Product</span>
                        </div>
                        <div class="controls" onclick="loadContent(5);">                        
                        <img src="../images/list.png" style="vertical-align: sub; margin-left:45px;">
                        <span style="line-height: 50px;vertical-align: super;height: 40px;margin-left: 15px;display: inline-block;">Update Product</span>
                        </div>
                        <div class="controls" onclick="loadContent(6);">
                        <img src="../images/transaction.png" style="vertical-align: sub; margin-left:45px;">
                        <span style="line-height: 50px;vertical-align: super;height: 40px;margin-left: 15px;display: inline-block;">Transaction</span>                        
                        </div>
                        <div class="controls" onclick="loadContent(6);">
                        <img src="../images/logout (2).png" style="vertical-align: sub; margin-left:45px;">
                        <span style="line-height: 50px;vertical-align: super;height: 40px;margin-left: 15px;display: inline-block;">Logout</span>                        
                        </div>';
    
    if (!isset($_SESSION['loggedAdmin'])) {
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        
        foreach ($admin as $adm) {
            if (strtolower($adm->getElementsByTagName('email')->item(0)->firstChild->nodeValue) == strtolower($email)) {
                if ($adm->getElementsByTagName('password')->item(0)->firstChild->nodeValue == $pass) {
                    $flag = 1;
                    $_SESSION['loggedAdmin'] = $email;                    

                    echo 'Access Granted*'.$sideBar;
                }
            } 
        }
        
        if ($flag == 0) {
            echo 'Access Denied';
        }
    } elseif (isset($_SESSION['loggedAdmin'])) {
        echo 'Access Granted*'.$sideBar;
    }
}

if ($cmdCode == 3) {
    echo '<div id="mainContent-ac">
            <div id="catTitle-container">
                <div>Add Category</div>
            </div>
            <div id="main-ac-content">
               <div id="catList-div"></div>
               <div id="catForm-container">
                   <input type="text" id="txtcatsub" style="width:58.5%;" placeholder="Category Name" required>
                   <input type="button" id="btnAddNewCat" onclick="addNewCat()" style="border: 1px solid #0181c4;" value="Add New Category">
               </div>
            </div>
        </div>';
}

if ($cmdCode == 4) {
    echo '<div id="mainContent">
            <form method="post" enctype="multipart/form-data" action="processAddProduct.php">
            <div id="catTitle-container">
                <div>Add New Product</div>
            </div>
            <div id="ap_input">
              <p id="ap_prodID">Product ID : ';
                $xml = new DomDocument;
                $xml->formatOutput = true;
                $xml->preserveWhiteSpace = false;
                $xml->Load('../products.xml');
                $parentProd = $xml->getElementsByTagName('products')->item(0);
                $products = $xml->getElementsByTagName('product');
                if ($products->length > 0) {
                    echo $id = $products->length + 1;
                } else {
                    echo $id = 1;
                }
                echo '</p>
                <select name="prodcat" style="width: 405px; height: 45px; padding: 2px; border-radius: 5px; border: 2px solid #dddddd;">';
                    foreach ($parentProd->childNodes as $category) {
                        if ($category->nodeType == 1) {
                            echo '<option>'.$category->nodeName.'</option>';
                        }
                    }
                echo '</select>
                <input type="text" name="prodname" placeholder="Name">
                <input type="text" name="prodprice" placeholder="Price">
                <input type="text" name="prodqty" placeholder="Quantity">
                <span id="ap_prodimg_txt">Product Image</span>
                <input type="file" accept="image/*" name="prodimg" placeholder="Image Files">
                <input type="submit" name="addProduct" value="ADD PRODUCT" style="width:102%;height: 45px;background-color: #0181c4;color: white;border-radius: 50px;border: 1px solid #0181c4;">
            </div>            
        </form>
        </div>';
}

if ($cmdCode == 5) {
    $xml = new DomDocument;
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->Load('../products.xml');
    $parentProd = $xml->getElementsByTagName('products')->item(0);
    $products = $xml->getElementsByTagName('product');
    
    if (isset($_GET['pagenum'])) {
        $pagenum = $_GET['pagenum'];
    } else {
        $pagenum = 1;
    }
    
    $i = $pagenum > 1 ? ($pagenum - 1) * 25 : $pagenum - 1;
    
    echo '<div id="admin-title--large">
            Product List
            <a href="../pdfreports/pdfProducts.php" target="_blank"><button id="printPDF">Print PDF</button><a/>
        </div>';
    
    echo '<div id="product-table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>';
    
                $prodLen = 0;
                foreach ($products as $item) {
                    $prodLen++;
                }
    
    
                for ($ctr = 1; $i < $prodLen; $i++, $ctr++) {
                    $prodID = $products->item($i)->getAttribute("prodID");
                    echo '<tr class="product-table-tr">
                            <td style="width:10%;">'.$prodID.'</td>
                            <td style="width:35%;">'.
                                $products->item($i)->getElementsByTagName("prodName")->item(0)->firstChild->nodeValue.
                            '</td>
                            <td id="qty_'.$prodID.'">'.
                                $products->item($i)->getElementsByTagName("prodQty")->item(0)->firstChild->nodeValue.
                            '</td>
                            <td id="price_'.$prodID.'">PHP '.
                                number_format($products->item($i)->getElementsByTagName("prodPrice")->item(0)->firstChild->nodeValue,2).
                            '</td>
                            <td id="action_'.$prodID.'" style="width:35%; text-align:center;">
                                <button id="updatePrice_'.$prodID.'" onclick="updatePriceQty('.$prodID.')">UPDATE PRODUCT</button>
                            </td>
                        </tr>';
                    if ($ctr == 25) break;
                }
            echo '</table>
        </div>';
    
    echo '<div id="page_btn-container" style="margin-top:20px;">';
        $pages = ceil($prodLen / 25);
        if ($pagenum > 1) {
            echo '<button onclick="paginateProdList('.($pagenum - 1).')">PREV</button>';
            echo '<button onclick="paginateProdList('.($pagenum - 1).')">'.($pagenum - 1).'</button>';
        }
        echo '<button class="currentPagenum" onclick="paginateProdList('.$pagenum.')">'.$pagenum.'</button>';
        if ($pagenum < $pages) {            
            echo '<button onclick="paginateProdList('.($pagenum + 1).')">'.($pagenum + 1).'</button>';
            echo '<button onclick="paginateProdList('.($pagenum + 1).')">NEXT</button>';
        }
    echo '</div>';
}

if ($cmdCode == 6) {
    $xml = new DOMDocument();
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->Load('transactions.xml');
    $transactions = $xml->getElementsByTagName('transactions')->item(0);
    $transaction = $transactions->getElementsByTagName('transaction');
    $total = 0;    
    
    if (isset($_GET['pagenum'])) {
        $pagenum = $_GET['pagenum'];
    } else {
        $pagenum = 1;
    }
    
    $i = $pagenum > 1 ? ($pagenum - 1) * 25 : $pagenum - 1;
    $prodLen = 0;
    foreach ($transaction as $trans) {
        $prodLen++;
    }
    
    echo '<div id="admin-title--large">
            Transactions
            <a href="../pdfreports/index.php" target="_blank"><button id="printPDF">Print PDF</button><a/>
        </div>';
    
    echo '<div id="product-table-container">
            <table>
                <tr>
                    <th>TID</th>
                    <th>CID</th>
                    <th>Customer Name</th>
                    <th>Item</th>
                    <th>Date</th>
                    <th>Total</th>
                </tr>';
    
                for ($ctr = 1; $i < $prodLen; $i++, $ctr++) {
                    $prods = $transaction->item($i)->getElementsByTagName('product');
                    $itemsOnTrans = 0;
                    foreach ($prods as $trans) {
                        $itemsOnTrans++;
                    }
                    
                    echo '<tr class="product-table-tr">
                            <td style="width:7.5%;" rowspan="'.$itemsOnTrans.'">'.
                                $transaction->item($i)->getAttribute("transID").
                            '</td>
                            <td style="width:7.5%;" rowspan="'.$itemsOnTrans.'">'.
                                $transaction->item($i)->getElementsByTagName("custID")->item(0)->firstChild->nodeValue.
                            '</td>
                            <td style="width:25%;" rowspan="'.$itemsOnTrans.'">'.
                                $transaction->item($i)->getElementsByTagName("custName")->item(0)->firstChild->nodeValue.
                            '</td>
                            <td style="width:25%;">'.
                                $prods->item(0)->firstChild->nodeValue.
                            '</td>
                            <td style="width:20%;" rowspan="'.$itemsOnTrans.'">'.
                                $transaction->item($i)->getElementsByTagName("custDate")->item(0)->firstChild->nodeValue.
                            '</td>
                            <td style="width:15%;" rowspan="'.$itemsOnTrans.'">PHP '.
                                number_format($transaction->item($i)->getElementsByTagName("custTotal")->item(0)->firstChild->nodeValue,2).
                            '</td>
                        </tr>';
                    
                    for ($x = 1; $x < $itemsOnTrans; $x++) {
                        echo '<tr class="product-table-tr">
                                <td>'.$prods->item($x)->firstChild->nodeValue.'</td>
                            </tr>';
                    }
                        $total += $transaction->item($i)->getElementsByTagName("custTotal")->item(0)->firstChild->nodeValue;
                    if ($ctr == 25) break;
                }
                
                echo '<tr class="product-table-tr">
                    <td colspan="5">TOTAL</td>
                    <td>PHP '.
                        number_format($total,2).
                    '</td>
                </tr>
            </table>
        </div>';
    
    $_SESSION['totalT'] = $total;
    echo '<div id="page_btn-container" style="margin-top:20px;">';
        $pages = ceil($prodLen / 25);
        if ($pagenum > 1) {
            echo '<button onclick="paginateTransaction('.($pagenum - 1).')">PREV</button>';
            echo '<button onclick="paginateTransaction('.($pagenum - 1).')">'.($pagenum - 1).'</button>';
        }
        echo '<button class="currentPagenum" onclick="paginateTransaction('.$pagenum.')">'.$pagenum.'</button>';
        if ($pagenum < $pages) {            
            echo '<button onclick="paginateTransaction('.($pagenum + 1).')">'.($pagenum + 1).'</button>';
            echo '<button onclick="paginateTransaction('.($pagenum + 1).')">NEXT</button>';
        }
    echo '</div>';
}
?>