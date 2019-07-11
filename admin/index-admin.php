<?php
session_start();
if (isset($_GET['logout'])) {
    unset($_SESSION['loggedAdmin']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>GadgetIO | Home</title>
        <link rel="stylesheet" type="text/css" href="../style.css"/>
        <link rel="shortcut icon" type="image/x-icon" href="../images/tab-GIO.png">
        <script src="script-admin.js"></script>
    </head>
    <body>
        <div id="updateModal">
            <div id="update-modalContent">
                <span class="closeModal" onclick="closeModal();">&times;</span>
                <div id="updateContent">
                    <table id="update-table">
                        <tr>
                            <td as="updateTitle">ID:</td>
                            <td id="modal-id"></td>
                        </tr>
                        <tr>
                            <td as="updateTitle">Product:</td>
                            <td id="modal-name"></td>
                        </tr>
                        <tr>
                            <td as="updateTitle">Quantity:</td>
                            <td>
                                <input id="modal-qty" type="text">
                            </td>
                        </tr>
                        <tr>
                            <td as="updateTitle">Price:</td>
                            <td>
                                <input id="modal-price" type="text">
                            </td>
                        </tr>
                    </table>
                    <div id="action-contain">
                        <button onclick="closeModal();">CANCEL</button>
                        <button onclick="updateProduct();">UPDATE</button>
                    </div>                    
                </div>
            </div>
        </div>
        <div id="mainContainer">
            <?php
            if (!isset($_SESSION['loggedAdmin'])) {
                echo '<div id="form-container" style="margin-top: 120px;">
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
                </div>

                <div id="form-signup">
                    <input type="text" name="signup-name" placeholder="Full Name" required>
                    <input type="text" name="signup-email" placeholder="Email" required>
                    <input type="password" name="signup-password" placeholder="Password" required>
                    <input type="password" name="signup-repassword" placeholder="Retype Password" required>
                    <input type="button" name="signup-button" onclick="processSignup(2)" value="SIGN UP">
                    <p>Already have an account? <span onclick="activateSignUp(0);">Login</span></p>
                </div>
            </div>
            <div id="admin-contents"></div>';
            } else {
                echo '<div id="sideBar">
                        <div id="logoContainer" style="height: 88px; margin-left: 25px;">
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
                        <a href="index-admin.php?logout=1"><div class="controls">
                        <img src="../images/logout (2).png" style="vertical-align: sub; margin-left:45px;">
                        <span style="line-height: 50px;vertical-align: super;height: 40px;margin-left: 15px;display: inline-block;">Logout</span>                        
                        </div></a>
                    </div>
                    <div id="admin-contents"></div>';
            }
            ?>
        </div>
        <?php
        if (isset($_GET['fromAddProd'])) {
            echo '<script>
                    loadContent(5);
                </script>';
        }
        ?>
    </body>
</html>