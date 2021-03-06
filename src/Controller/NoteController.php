<?php

declare(strict_types=1);

namespace App\Controller;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Model\NoteModel;
use App\Request;

class NoteController extends AbstractController
{
  private const DEFAULT_ACTION = 'list';
  private const PAGE_SIZE = 10;
  protected NoteModel $noteModel;
  
  

  public function __construct(Request $request)
  {
    parent::__construct($request);
    $this->noteModel = new NoteModel(parent::$configuration['db']);
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

  

  public function createAction(): void
  {
    if ($this->request->hasPost()) {
      $noteData = [
        'title' => $this->request->postParam('title'),
        'description' => $this->request->postParam('description')
      ];
      $this->noteModel->create($noteData);
      $this->redirect('/', ['before' => 'created']);
    }

    $this->view->render('create');
  }

  public function showAction(): void
  {
    $this->view->render(
      'show',
      ['note' => $this->getNote()]
    );
  }

  public function listAction(): void
  {
    $phrase = $this->request->getParam('phrase');
    $pageNumber = (int) $this->request->getParam('page', 1);
    $pageSize = (int) $this->request->getParam('pagesize', self::PAGE_SIZE);
    $sortBy = $this->request->getParam('sortby', 'title');
    $sortOrder = $this->request->getParam('sortorder', 'desc');

    if (!in_array($pageSize, [1, 5, 10, 25])) {
      $pageSize = self::PAGE_SIZE;
    }

    if ($phrase) {
      $noteList = $this->noteModel->search($phrase, $pageNumber, $pageSize, $sortBy, $sortOrder);
      $notes = $this->noteModel->searchCount($phrase);
    } else {
      $noteList = $this->noteModel->list($pageNumber, $pageSize, $sortBy, $sortOrder);
      $notes = $this->noteModel->count();
    }




    $this->view->render(
      'list',
      [
        'page' => [
          'number' => $pageNumber,
          'size' => $pageSize,
          'pages' => (int) ceil($notes / $pageSize)
        ],
        'phrase' => $phrase,
        'sort' => ['by' => $sortBy, 'order' => $sortOrder],
        'notes' => $noteList,
        'before' => $this->request->getParam('before'),
        'error' => $this->request->getParam('error')
      ]
    );
  }

  public function editAction(): void
  {

    if ($this->request->isPost()) {
      $noteId = (int) $this->request->postParam('id');
      $noteData = [
        'title' => $this->request->postParam('title'),
        'description' => $this->request->postParam('description')
      ];
      $this->noteModel->edit($noteId, $noteData);
      $this->redirect('/', ['before' => 'edited']);
    }

    $this->view->render(
      'edit',
      ['note' => $this->getNote()]
    );
  }

  public function deleteAction(): void
  {
    if ($this->request->isPost()) {
      $id = (int) $this->request->postParam('id');
      $this->noteModel->delete($id);
      $this->redirect('/', ['before' => 'deleted']);
    }

    $this->view->render(
      'delete',
      ['note' => $this->getNote()]
    );
  }

    public function logoutAction(){
        $logParams = [
            'logout'=>$this->request->getParam('action')
        ];
        if ($logParams['logout']==='logout'){
            session_unset();
        }

        $this->view->render('logout',$logParams);
    }



  private function getNote(): array
  {
    $noteId = (int) $this->request->getParam('id');
    if (!$noteId) {
      $this->redirect('/', ['error' => 'missingNoteId']);
    }

    return $this->noteModel->get($noteId);
  }
}
