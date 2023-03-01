<?php

use controllers\SignUpController;
use controllers\DatabaseController;
use controllers\SignInController;

require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

session_start();
ob_start();
$url = $_SERVER['REQUEST_URI'];

if (preg_match('#/main#', $url, $params)) { //Роутер
    $_SESSION['errors'] = null;
    if ($_SESSION['auth'] == true) {
        $title = "Main";
        $content = include "views" . DIRECTORY_SEPARATOR . "main.html";
    } else {
        $title = "Auth";
        $content = include "views" . DIRECTORY_SEPARATOR . "chooseAction.html";
    }
}else if (preg_match('#/signin#', $url, $params)) {
    $title = "Sign In";
    $content = include "views" . DIRECTORY_SEPARATOR . "signIn.html";
}else if (preg_match('#/signup#', $url, $params)) {
    $title = "Sign Up";
    $content = include "views" . DIRECTORY_SEPARATOR . "signUp.html";
}

$layout = file_get_contents("views" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "layout.php");
$layout = str_replace('{{ title }}', $title, $layout);
$layout = str_replace('{{ content }}', $content, $layout);
echo $layout;

$database = new DatabaseController();

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { //Проверка на ajax запрос
    //Регистрация
    if (
        isset($_POST['login']) &&
        isset($_POST['name']) &&
        isset($_POST['password']) &&
        isset($_POST['confirmPassword']) &&
        isset($_POST['email'])
    ) {
        $user = [
            'login' => $_POST['login'],
            'name' => $_POST['name'],
            'password' => $_POST['password'],
            'email' => $_POST['email'],
            'confirmPassword' => $_POST['confirmPassword']
        ];

        $controller = new SignUpController($user, $database);
        $_SESSION['errors'] = $controller->getErrors();
        ob_end_clean();
        echo json_encode(['accept' => $controller->getAccept()]);
    }

    //Авторизация
    if (
        isset($_POST['login']) &&
        isset($_POST['password']) &&
        isset($_POST['signInCheck'])
    ) {
        $user = [
            'login' => $_POST['login'],
            'password' => $_POST['password']
        ];

        $controller = new SignInController($user, $database);
        $_SESSION['errors'] = $controller->getErrors();
        ob_end_clean();
        echo json_encode(['accept' => $controller->getAccept()]);
    }
}

if (isset($_POST['logOut'])) {
    $_SESSION['auth'] = false;
    unset($_SESSION['login']);
    header("Refresh: 0");
}
