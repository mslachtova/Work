<?php
namespace App\Presenters;

use Nette;
use App\Model\BookManager;
use Nette\Application\UI\Form;
use App\Model\CommentManager;


class BookPresenter extends Nette\Application\UI\Presenter
{
    private $bookManager;
    private $commentManager;
    
    public function injectBookManager(BookManager $bookManager)
    {
        $this->bookManager = $bookManager;

    }
    
    public function injectCommentManager(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
        
    }
    
    public function renderDefault()
    {
        $this->template->books = $this->bookManager->getAll();
    }
    
    public function renderShow($bookId)
    {
        $book = $this->bookManager->find($bookId);
        $this->template->book = $book;
        $this->template->comments = $book->related('comment')->order('created_at');
    }
    
    protected function createComponentCommentForm()
    {
        $form = new Form;
        $form->addText('id', 'Identifikační číslo:')
        ->setRequired();
        $form->addTextArea('content', 'Komentář:')
        ->setRequired();
        $form->addSubmit('send', 'Zveřejnit komentář');
        $form->onSuccess[] = [$this, 'commentFormSucceeded'];
        return $form;
    }
    
    public function commentFormSucceeded(Form $form, \stdClass $values)
    {
        $bookId = $this->getParameter('bookId');
        
        $this->commentManager->create([
            'customer_id' => $values->id,
            'book_id' => $bookId,
            'content' => $values->content,
        ]);
        
        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }

}