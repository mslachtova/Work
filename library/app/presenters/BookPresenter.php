<?php
namespace App\Presenters;

use Nette;
use App\Model\BookManager;
use Nette\Application\UI\Form;


class BookPresenter extends Nette\Application\UI\Presenter
{
    private $bookManager;
    
    public function injectBookManager(BookManager $bookManager)
    {
        $this->bookManager = $bookManager;

    }
    
    public function renderDefault()
    {
        $this->template->books = $this->bookManager->getAll();
    }

}