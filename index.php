<?php

// подключаем необходимые файлы
define('ROOT', dirname(__FILE__));
require_once('SplClassLoader.php');

$loader = new SplClassLoader(null, ROOT.'\lib\\', ROOT.'\src\\');
$loader->register();

$app = new Burrito\Framework\Application();
$app->run();










/**

$dbh = new \PDO('mysql:host=localhost;dbname=framework', 'admin', 'admin');
ActiveRecord::setDb( $dbh );

$userClass = 'Security\Entity\User';
$loginPage = '/login/';
$registrationPage = '/registration/';
$logoutPage = '/logout/';

session_start();
if(!empty($_SESSION['loggedIn']) && !empty($_SESSION['username']) && !empty($_SESSION['id'])) {
    $user = new \Security\Entity\User($_SESSION['id']);
}
elseif(!empty($_POST['username']) && !empty($_POST['password'])) {
    // let the user login
    $username = mysql_real_escape_string($_POST['username']);
    $password = md5(mysql_real_escape_string($_POST['password'])); //todo: md5 заменить


    $checklogin = mysql_query("SELECT * FROM users WHERE Username = '".$username."' AND Password = '".$password."'");

    if(mysql_num_rows($checklogin) == 1){
        $row = mysql_fetch_array($checklogin);
        $email = $row['EmailAddress'];

        $_SESSION['Username'] = $username;
        $_SESSION['EmailAddress'] = $email;
        $_SESSION['LoggedIn'] = 1;

        echo "<h1>Success</h1>";
        echo "<p>We are now redirecting you to the member area.</p>";
        echo "<meta http-equiv='refresh' content='=2;index.php' />";
    }
    else{
        #show error
    }

} else {
    // display the login form
}

**/