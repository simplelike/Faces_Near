<?php

require_once('abstractErrorClass.php');
require_once('userClass.php');
require_once('randomSeq.php');

class SessionClass extends AbstractErrorClass {
    const saltLength = 10;
    public function SessionClass(){
	    if(!isset($_SESSION)){
	        session_start();
            }
    }
    public function generateSalt(){
        $this->checkIt();
        $_SESSION['salt'] = randomSeq(SessionClass::saltLength);
    }
    public function getSalt(){
        $this->checkIt();
        if (empty($_SESSION['salt'])){
            $this->generateSalt();
        }
        return $_SESSION['salt'];
    }
    public function getLoggedUser(){
        $this->checkIt();
        if (empty($_SESSION['userId'])){
            return;
        }
        $user = new UserClass();
        $user->selectById($_SESSION['userId']);
        if ($this->forwardError($user)){
            return;
        }
        return $user;
    }
    public function loginAsUser($userId){
        $this->checkIt();
        $user = new UserClass();
        $user->selectById($userId);
        if ($this->forwardError($user)){
            return;
        }
        $_SESSION['userId'] = $user->get('id');
        return $user;
    }
    public function login(){
        $this->checkIt();
        if (!$this->isLoginRequest()){
            die('It isn`t login request');
        }
        $user = new UserClass();
        $user->selectByLogin($_POST['login']);
        if ($this->forwardError($user)){
            return;
        }
        if (sha1($this->getSalt().$user->get('password')) != $_POST['password']){
            $this->error('Wrong login or password');
    	    return;
        }
    	$_SESSION['userId'] = $user->get('id');
        return $user;
    }
    static public function isLoginRequest(){
        if (empty($_POST['login'])){
            return false;
        }
        if (empty($_POST['password'])){
            return false;
        }
        return true;
    }
    function logout(){
        $this->checkIt();
        $_SESSION['userId'] = '';
    }
}
