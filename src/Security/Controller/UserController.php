<?php

use Burrito\Framework\Controller as Controller;
use Security\Entity\User as User;

class UserController extends Controller {

    function loginAction(){
        // выводим её при помощи View
        $this->view->render('login', array('page' => null));
    }

    function registrationAction(){
        if ($this->request->method() == 'POST'){

            $login = $this->request->get('login');
            $password = $this->request->get('password');

            $user = new User( NULL );
            $user->name = $login;
            $user->pass = $password;
            $user->save();

            $this->redirect('about');
        }

        $context = array();
        $this->view->render('registration', $context);
    }

}