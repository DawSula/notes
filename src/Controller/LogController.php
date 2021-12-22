<?php

declare(strict_types=1);

namespace App\Controller;



use App\Request;

class LogController extends AbstractLogController
{



    public function loginAction()
    {
        if ($this->request->hasPost())
        {
            $logParams = [
                'name'=>$this->request->postParam('name'),
                'password' => $this->request->postParam('password'),
            ];
            if ($this->userModel->checkIfAllSet($logParams) === false){
                $this->redirect('/', ['error' => 'dataRequire']);
            }
            if ($this->userModel->checkifCorrect($logParams) === false){
                $this->redirect('/', ['error' => 'IncorrectData']);
            }else{
                $this->userModel->startSession($logParams);
                $this->view->render('logged');
            }
        }

        $logParams = [
            'registered' => $this->request->getParam('before'),
            'logout'=>$this->request->getParam('logout'),
            'error'=>$this->request->getParam('error')
        ];

        if ($logParams['logout']==='yes'){
            session_unset();
        }

        $this->view->render('login',$logParams);
    }

    public function registerAction()
    {
        if ($this->request->hasPost()){
            $logParams = [
                'name'=> $this->request->postParam('name'),
                'password'=> $this->request->postParam('password'),
                'repeatedPassword'=>$this->request->postParam('repeatedPassword'),
            ];

            if ($this->userModel->checkPasswords($logParams)=== false) {
                header('Location: /?action=register&error=passwordError');
                exit();
            }
            if ($this->userModel->checkIfAllSet($logParams)===false){
                header(('Location: /?action=register&error=someEmpty'));
                exit();
            }
            if ($this->userModel->checkIfExist($logParams) === true){
                header('Location:/?action=register&error=userExist');
                exit();
            }
            $this->userModel->userRegister($logParams);
            header('Location:/?before=register');
        }

        $logParams = [
            'error'=>$this->request->getParam('error'),
        ];
        $this->view->render('register', $logParams);
    }
    public function logoutAction(){
        $logParams = [
            'logout' => $this->request->getParam('action'),
        ];

        $this->view->render('logout', $logParams);
    }

}





