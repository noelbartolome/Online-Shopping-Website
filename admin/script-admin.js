function ajaxConnection() {
    try {
        xml = new XMLHttpRequest();
    } catch (e) {
        try {
            // IE Browser Compability
            xml = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            alert("Browser not supported.");
            return false;
        }
    }
}

function activateSignUp(mode) {
    var formLogin = document.getElementById('form-login');
    var formContainer = document.getElementById('form-container');
    var formSignup = document.getElementById('form-signup');
    if (mode == 1) {
        if (formSignup.getElementsByTagName('div').length > 0) {
            formSignup.removeChild(formSignup.getElementsByTagName('div')[0]);
        }
        
        for (var i = 0; i < 4; i++) {
            formSignup.getElementsByTagName('input')[i].value = "";
        }
        formLogin.style.left = "-100%";
        formSignup.style.left = "0%";
        formContainer.style.marginTop = "70px";
        formContainer.style.height = "454px";
    } else {
        if (formLogin.getElementsByTagName('div').length > 0) {
            formLogin.removeChild(formLogin.getElementsByTagName('div')[0]);
        }
        for (var i = 0; i < 2; i++) {
            formLogin.getElementsByTagName('input')[i].value = "";
        }
        formSignup.style.left = "100%";
        formLogin.style.left = "0%";
        formContainer.style.marginTop = "120px";
        formContainer.style.height = "350px";
    }
}

function processLogin(cmdCode) {
    ajaxConnection();
    var login_data = document.getElementById('form-login').getElementsByTagName('input');
    var err_container = document.createElement('div');
    var formLogin = document.getElementById('form-login');
    var formContainer = document.getElementById('form-container');
    var formSignup = document.getElementById('form-signup');    
    
    if (formLogin.getElementsByTagName('div').length > 0) {
        formLogin.removeChild(formLogin.getElementsByTagName('div')[0]);
    }
    
    err_container.id = "err_container";
    var email = login_data[0].value;
    var pass = login_data[1].value;
    if (email == "" || pass == "") {
        var error_msg = document.createTextNode('Please fill up all fields');
        err_container.appendChild(error_msg);
        formContainer.style.height = "377px";
        formContainer.style.marginTop = "161px";
        formLogin.insertBefore(err_container, login_data[0]);
    } else if ((email.includes("@") && email.includes(".com")) && pass != "") {
        xml.open('POST', 'processLogin-admin.php', true);
        xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xml.onreadystatechange = function() {
            if (xml.readyState == 4 && xml.status == 200) {    
                var output = xml.responseText.split('*');
                if (output[0] == 'Access Granted') {
                    formContainer.setAttribute("style", "width: 260px; padding: 20px 0; border-radius:0; position:fixed; height: 596px; margin: 0; animation: sidebar-left 0.8s ease;");
                    for (var i = 0; i < formContainer.childNodes.length; i++) {
                        if (formContainer.childNodes[i].nodeType == 1) {
                            formContainer.removeChild(formContainer.childNodes[i]);
                        }
                    }
                    formContainer.innerHTML = output[1];
                    loadContent(3);
                } else {
                    var error_msg = document.createTextNode('Incorrect username or password');
                    err_container.appendChild(error_msg);
                    formContainer.style.height = "377px";
                    formContainer.style.marginTop = "161px";
                    formLogin.insertBefore(err_container, login_data[0]);
                }
            }
        }
        xml.send('email=' + email + '&pass=' + pass + '&cmdCode=' + cmdCode);
    } else {
        var error_msg = document.createTextNode('Invalid email address');
        err_container.appendChild(error_msg);
        document.getElementById('form-container').style.height = "377px";
        document.getElementById('form-container').style.marginTop = "161px";
        document.getElementById('form-login').insertBefore(err_container, login_data[0]);
    }
}

function loadContent(cmdCode) {    
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById('admin-contents').innerHTML = xml.responseText;
        }
    }
    xml.open('GET', 'processLogin-admin.php?cmdCode=' + cmdCode, true);
    xml.send();
}

function addNewCat() {
    ajaxConnection();
    var newCat = document.getElementById('txtcatsub').value;
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById('txtcatsub').value = "";
            alert(xml.responseText);
        }
    }
    xml.open('GET', 'processAddCategory.php?newCat=' + newCat, true);
    xml.send();
}

function updatePriceQty(prodID) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            var output = xml.responseText.split('*');
            document.getElementById('modal-id').innerHTML = output[0];
            document.getElementById('modal-name').innerHTML = output[1];
            document.getElementById('modal-qty').value = output[2];
            document.getElementById('modal-price').value = output[3];
            document.getElementById('updateModal').style.display = "block";
        }
    }
    xml.open('GET', 'processAddProduct.php?cmdCode=2&prodID=' + prodID, true);
    xml.send();    
}

function closeModal() {
    document.getElementById('updateModal').style.display = "none";
}

function updateProduct() {
    ajaxConnection();
    var prodID = document.getElementById('modal-id').innerHTML;
    var prodQty = document.getElementById('modal-qty').value;
    var prodPrice = document.getElementById('modal-price').value;
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            var output = xml.responseText.split('*');
            document.getElementById('qty_' + prodID).innerHTML = prodQty;
            document.getElementById('price_' + prodID).innerHTML = 'PHP ' + output[1];
            closeModal();
            alert(output[0]);
        }
    }
    xml.open('GET', 'processAddProduct.php?cmdCode=3&prodID=' + prodID + '&newQty=' + prodQty + '&newPrice=' + prodPrice, true);
    xml.send();
}

function paginateProdList(targetPage) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById('admin-contents').innerHTML = xml.responseText;
        }
    }
    xml.open('GET', 'processLogin-admin.php?cmdCode=5&pagenum=' + targetPage, true);
    xml.send();
}

function paginateTransaction(targetPage) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById('admin-contents').innerHTML = xml.responseText;
        }
    }
    xml.open('GET', 'processLogin-admin.php?cmdCode=6&pagenum=' + targetPage, true);
    xml.send();
}