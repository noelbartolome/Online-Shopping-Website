window.onload = initAll;
var namesArray = new Array();

// [!] Image Slider
var slideIndex = 1;

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";    
}

var autoLipat = 0;
var startInterval = function() {
    autoLipat = setInterval(function() {
        plusSlides(1);
    }, 5000);
}
startInterval();

// [!] Ajax Connection
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

// [!] Add to Cart
function Cart(prodID, cmdCode, isFromWish) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            if (cmdCode == 2) {
                document.getElementById('mainContainer').innerHTML = xml.responseText;
                document.getElementById("txtsearch").value = "";
                slideIndex = 1;
                showSlides(1);
                clearInterval(autoLipat);
                startInterval();                
            }
            else if (cmdCode == 143) {
                document.getElementById('modalContainer').style.display = "none";
                document.getElementById('mainContainer').innerHTML = xml.responseText;
            }
            else if (cmdCode == 6) {
                document.getElementById('price1').innerHTML = xml.responseText;
                document.getElementById('price2').innerHTML = xml.responseText;
            }
            else if (cmdCode == 7) {
                document.getElementById('badge').setAttribute('data-badge', xml.responseText);
            }
            else if (cmdCode == 8) {
                console.log('Appending login page...');
                clearInterval(autoLipat);
                document.getElementById('mainContainer').innerHTML = xml.responseText;
                Cart(1, 7);
            }
            else if (cmdCode == 1) {                            
//                alert(xml.responseText);
                var prodData = xml.responseText.split('*');
                if (prodData[0] == 1) {
                    if (isFromWish == 1) {
                        document.getElementById('cmodal1').setAttribute('onclick', 'closeModal(1)');
                        document.getElementById('cmodal2').setAttribute('onclick', 'closeModal(1)');
                    } else {
                        document.getElementById('cmodal1').setAttribute('onclick', 'closeModal()');
                        document.getElementById('cmodal2').setAttribute('onclick', 'closeModal()');
                    }
                    
                    document.getElementById('badge').setAttribute('data-badge', prodData[5]);
                    document.getElementById('modal-img').setAttribute('src', prodData[3]);
                    document.getElementById('modal-price').innerHTML = 'PHP ' + prodData[2];
                    document.getElementById('modal-pname').innerHTML = prodData[1] + ' added to cart';
                    document.getElementById('modal-items').innerHTML = prodData[5] + ' ITEM(s)';
                    document.getElementById('modal-total').innerHTML = 'PHP ' + prodData[4];
                    document.getElementById('modalContainer').style.display = "block";
                } else {
                    alert('Item out of stock');
                }
            }
        }
    }
    xml.open("GET", "loadCart.php?prodID=" + prodID + "&cmdCode=" + cmdCode, true);
    xml.send(null);
}

function initAll() {
    ajaxConnection();
	document.getElementById("txtsearch").onkeyup = searchSuggest;

	if (xml) {
		xml.onreadystatechange = setnamesArray;
		xml.open("GET", "products.xml", true);
		xml.send(null);
	}
	else {
		alert("Sorry, but I couldn't create an XMLHttpRequest");
	}
}

function setnamesArray() {
	if (xml.readyState == 4) {
		if (xml.status == 200) {
			if (xml.responseXML) {
				var allNames = xml.responseXML.getElementsByTagName("product");
				for (var i=0; i<allNames.length; i++) {
					namesArray[i] = allNames[i].getElementsByTagName("prodName")[0].firstChild.nodeValue;
				}
			}
		}
		else {
			alert("There was a problem with the request " + xml.status);
		}
	}
}

function searchSuggest() {
	var str = document.getElementById("txtsearch").value;
	document.getElementById("txtsearch").className = "";
	if (str != "") {
		
        var le = document.getElementById('searchContainer').getElementsByTagName('div').length;
		
		if (le>0){
			document.getElementById("divpop").innerHTML="";
		}
		else{			
			var newDiv = document.createElement("div");
			newDiv.id = "divpop";
            document.getElementById('searchContainer').appendChild(newDiv);
		}

	
		for (var i=0; i<namesArray.length; i++) {
			var thisName = namesArray[i];
	
			if (thisName.toLowerCase().indexOf(str.toLowerCase()) == 0) {
				var tempDiv = document.createElement("div");
				tempDiv.innerHTML = thisName;
				tempDiv.onclick = makeChoice;
				tempDiv.className = "suggestions";
				document.getElementById("divpop").appendChild(tempDiv);
			}
		}
	}
	
	else{
		document.getElementById("divpop").innerHTML="";
	}
}

function makeChoice(evt) {
	var thisDiv = (evt) ? evt.target : window.event.srcElement;
	document.getElementById("txtsearch").value = thisDiv.innerHTML;
	document.getElementById("divpop").innerHTML = "";
	document.getElementById('txtsearch').focus();
}

function searchItem() {
    ajaxConnection();
    document.getElementById("divpop").innerHTML = "";
    var itemToSearch = document.getElementById('txtsearch').value;
    var mainContainer = document.getElementById('mainContainer');
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            if (!itemToSearch == "") {
                mainContainer.innerHTML = xml.responseText;
                clearInterval(autoLipat);
            }
        }
    }
    xml.open("GET", "processSearch.php?txtsearch=" + itemToSearch, true);
    xml.send(null);
}

function qtyCtrl(operation, prodID) {
    ajaxConnection();    
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            if (document.getElementById(prodID).value == "") {
                document.getElementById(prodID).value = 1;
            }
            var qtyValue = parseInt(document.getElementById(prodID).value);
            var products = xml.responseXML.getElementsByTagName('products')[0];
            var product = products.getElementsByTagName('product');
            
            for (item of product) {
                if (item.getAttribute('prodID') == prodID) {
                    var prodQty = item.getElementsByTagName('prodQty')[0].firstChild.nodeValue;                    
                    if (operation == 0) {
                        qtyValue -= 1;
                    } else if (operation == 1) {                        
                        qtyValue += 1;                    
                    }
                    if (qtyValue <= 1) {
                        qtyValue = 1;
                        document.getElementById("min_" + prodID).classList.add("disable-qty-btn");
                    } else if (qtyValue >= prodQty) {
                        qtyValue = prodQty;
                        document.getElementById("plus_" + prodID).classList.add("disable-qty-btn");
                    } else {
                        document.getElementById("plus_" + prodID).classList.remove("disable-qty-btn");
                        document.getElementById("min_" + prodID).classList.remove("disable-qty-btn");
                    }
                    document.getElementById(prodID).value = qtyValue;
                    updateQtySession(prodID, qtyValue);
                }
            }            
        }
    }
    xml.open("GET", "products.xml", true);
    xml.send(null);
}

function updateQtySession(prodID, qtyValue) {
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            var output = xml.responseText.split("*");
            document.getElementById('badge').setAttribute('data-badge', output[0]);
            document.getElementById('CPItem_' + prodID).innerHTML = 'PHP ' + output[1];            
            Cart(prodID, 6);
        }
    }
    xml.open("GET", "loadCart.php?cmdCode=" + 4 + "&prodID=" + prodID + "&qtyValue=" + qtyValue, true);
    xml.send(null);
}

function removeItemCart(itemRemove) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById('row_' + itemRemove).classList.add("removeItem");
            document.getElementById('badge').setAttribute('data-badge', xml.responseText);
            if (xml.responseText == '0') {
                Cart(1, 143);
            } else {
                Cart(1, 6);
            }            
        }
    }
    xml.open("GET", "loadCart.php?itemRemove=item_" + itemRemove + "&cmdCode=" + 5, true);
    xml.send(null);
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
        formContainer.style.marginTop = "125px";
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
        formContainer.style.marginTop = "175px";
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
        xml.open('POST', 'processLogin.php', true);
        xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xml.onreadystatechange = function() {
            if (xml.readyState == 4 && xml.status == 200) {
                if (xml.responseText == 'Access Granted') {
                    document.getElementById('myAccount-dropdown').getElementsByTagName('a')[0].setAttribute('onclick','logoutUser()');
                    document.getElementById('myAccount-dropdown').getElementsByTagName('a')[0].innerHTML = "Logout";
                    Cart(1, 2);
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

function processSignup(cmdCode) {
    ajaxConnection();
    var signup_data = document.getElementById('form-signup').getElementsByTagName('input');
    var err_container = document.createElement('div');
    var formLogin = document.getElementById('form-login');
    var formContainer = document.getElementById('form-container');
    var formSignup = document.getElementById('form-signup');
    
    if (formSignup.getElementsByTagName('div').length > 0) {
        formSignup.removeChild(formSignup.getElementsByTagName('div')[0]);
    }
    
    err_container.id = "err_container";
    var fullName = signup_data[0].value;
    var email = signup_data[1].value;
    var pass = signup_data[2].value;
    var repass = signup_data[3].value;
    
    if (fullName == "" || email == "" || pass == "" || repass == "") {
        var error_msg = document.createTextNode('Please fill up all fields');
        err_container.appendChild(error_msg);
        formContainer.style.height = "481px";
        formContainer.style.marginTop = "125px";
        formSignup.insertBefore(err_container, signup_data[0]);
    } else if ((email.includes("@") && email.includes(".com")) && pass != "" && (pass == repass)) {
        xml.open('POST', 'processLogin.php', true);
        xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xml.onreadystatechange = function() {
            if (xml.readyState == 4 && xml.status == 200) {
                if (xml.responseText == "Account created") {
                    var error_msg = document.createTextNode('Account created');
                    for (var i = 0; i < 4; i++) {
                        formSignup.getElementsByTagName('input')[i].value = "";
                    }
                    err_container.appendChild(error_msg);
                    err_container.setAttribute('style', 'background-color: #00c991');
                    formContainer.style.height = "481px";
                    formContainer.style.marginTop = "125px";
                    formSignup.insertBefore(err_container, signup_data[0]);
                } else {
                    var error_msg = document.createTextNode('We\'re sorry, that username is taken');
                    err_container.appendChild(error_msg);
                    formContainer.style.height = "481px";
                    formContainer.style.marginTop = "125px";
                    formSignup.insertBefore(err_container, signup_data[0]);
                }
            }
        }
        xml.send('fullName=' + fullName + '&email=' + email + '&pass=' + pass + '&cmdCode=' + cmdCode);
    } else if (!(email.includes("@") && email.includes(".com"))) {
        var error_msg = document.createTextNode('Invalid email address');
        err_container.appendChild(error_msg);
        document.getElementById('form-container').style.height = "481px";
        document.getElementById('form-container').style.marginTop = "125px";
        document.getElementById('form-signup').insertBefore(err_container, signup_data[0]);
    } else {
        var error_msg = document.createTextNode('Passwords did not match');
        err_container.appendChild(error_msg);
        document.getElementById('form-container').style.height = "481px";
        document.getElementById('form-container').style.marginTop = "125px";
        document.getElementById('form-signup').insertBefore(err_container, signup_data[0]);
    }
}

function closeModal(isOnWish) {
    document.getElementById('modalContainer').style.display = "none";
    if (isOnWish == 1) {
        wishlist(1,2);
    }
//    document.getElementById('modalContainer').classList.add("closeModalAnim");
}

function processCheckout(custID, custName) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            alert(xml.responseText);
            if (xml.responseText == 'Success') {
                alert('Order received!');
                document.getElementById('badge').setAttribute('data-badge', '0');
                Cart(1, 2);
            }
        }
    }
    xml.open('GET', 'processCheckout.php?custID=' + custID + '&custName=' + custName, true);
    xml.send();
}

function wishlist(prodID, cmdCode, rem) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            if (cmdCode == 1) {
                if (xml.responseText == 'logFirst') {
                    Cart(1, 8);
                } else {
                    if (xml.responseText == 'Added') {
                        document.getElementById('heart_' + prodID).setAttribute('src', 'images/like-1.png');
                    } else if (xml.responseText == 'Existing') {
                        if (rem == 1) {
                            wishlist(1,2);
                        } else {
                            document.getElementById('heart_' + prodID).setAttribute('src', 'images/like.png');
                        }                        
                    }
                }   
            } else if (cmdCode == 2) {
                clearInterval(autoLipat);
                if (xml.responseText == 'logFirst') {
                    Cart(1,8);
                } else {
                    document.getElementById('mainContainer').innerHTML = xml.responseText;
                }                
            }
        }
    }
    xml.open('GET', 'processWishlist.php?prodID=' + prodID + '&cmdCode=' + cmdCode, true);
    xml.send();
}

function logoutUser() {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            if (xml.responseText == 'logout') {
                document.getElementById('myAccount-dropdown').getElementsByTagName('a')[0].setAttribute('onclick','Cart(1,8)');
                document.getElementById('myAccount-dropdown').getElementsByTagName('a')[0].innerHTML = "Login / Sign Up";
                Cart(1,8);
            }
        }
    }
    xml.open('GET', 'processWishlist.php?cmdCode=3', true);
    xml.send();
}

function loadCategory(catname) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById('mainContainer').innerHTML = xml.responseText;
            clearInterval(autoLipat);
        }
    }
    xml.open('GET', 'loadCategory.php?catName=' + catname, true);
    xml.send();
}

function paginateTo(targetPage, subcmd) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            var output = xml.responseText.split('*');
            if (subcmd == 1) {
                document.getElementById('featuredDiv').innerHTML = output[0];
                document.getElementById('page_btn-container').innerHTML = output[1];
            }
        }
    }
    xml.open('GET', 'loadCart.php?cmdCode=9&pagenum=' + targetPage + '&subcmd=' + subcmd, true);
    xml.send();
}

function paginateCat(pagenum, category) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            document.getElementById('mainContainer').innerHTML = xml.responseText;
        }
    }
    xml.open('GET', 'loadCategory.php?catName=' + category + '&pagenum=' + pagenum, true);
    xml.send()
}

function wishRem_toCart(prodID) {
    ajaxConnection();
    xml.onreadystatechange = function() {
        if (xml.readyState == 4 && xml.status == 200) {
            Cart(prodID, 1, 1);
        }
    }
    xml.open('GET', 'processWishlist.php?cmdCode=4&prodID=' + prodID, true);
    xml.send();
}