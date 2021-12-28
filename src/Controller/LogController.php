<?php

declare(strict_types=1);

namespace App\Controller;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Model\UserModel;
use App\Request;

class LogController extends AbstractController
{

    protected UserModel $userModel;
    private const DEFAULT_ACTION = 'login';

    public function __construct(Request $request)
    {
      parent::__construct($request);
      $this->userModel = new UserModel(parent::$configuration['db']);
    }

    public function run(): void
  {
    try {
      $action = $this->action() . 'Action';
      if (!method_exists($this, $action)) {
        $action = self::DEFAULT_ACTION . 'Action';
      }
      $this->$action();
    } catch (StorageException $e) {
      $this->view->render('error', ['message' => $e->getMessage()]);
    } catch (NotFoundException $e) {
      $this->redirect('/', ['error' => 'noteNotFound']);
    }
  }

  protected function action(): string
  {
    return $this->request->getParam('action', self::DEFAULT_ACTION);
  }

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

            $this->passwordValidate($logParams);
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

    private function passwordValidate($logParams): void{
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
        if ($this->userModel->checkIfStrongPassword($logParams) === false){
            header('Location:/?action=register&error=passwordNotEnoughStrong');
            exit();
        }
        $this->userModel->userRegister($logParams);
        header('Location:/?before=register');     
    }
}





