<?php
session_start();
$xml = new DOMDocument();
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->Load('users.xml');
$users = $xml->getElementsByTagName('users')->item(0);
$user = $users->getElementsByTagName('user');

$cmdCode = $_POST['cmdCode'];

if ($cmdCode == 2) {
    $id = 1;
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $flag = 0;
    
    if (!$user->length == 0) {
        $id = $user->length + 1;
    }
    
    foreach ($user as $cust) {
        if (strtolower($cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue) == strtolower($email)) {
            echo 'Email taken';
            $flag = 1;
        } 
    }    
    
    if ($flag == 0) {
        $userTag = $xml->createElement('user');
        $userTag->setAttribute('custID', $id);
        $userTag->appendChild($xml->createElement('fullName', $fullName));
        $userTag->appendChild($xml->createElement('email', $email));
        $userTag->appendChild($xml->createElement('password', $pass));
        $users->appendChild($userTag);
        echo 'Account created';
        $xml->Save('users.xml');
    }    
} else if ($cmdCode == 1) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $flag = 0;
    
    foreach ($user as $cust) {
        if (strtolower($cust->getElementsByTagName('email')->item(0)->firstChild->nodeValue) == strtolower($email)) {
            if ($cust->getElementsByTagName('password')->item(0)->firstChild->nodeValue == $pass) {
                $flag = 1;
                $_SESSION['loggedUser'] = $email;
                echo 'Access Granted';
            }
        } 
    }
    
    if ($flag == 0) {
        echo 'Access Denied';
    }
}
?>