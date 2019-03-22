<?php

namespace App\Presenters;

use Nette;
use App\Model\CustomerManager;
use App\Model\BookManager;
use App\Model\CommentManager;
use App\Model\LoanManager;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $customerManager;
    private $bookManager;
    private $commentManager;
    private $loanManager;
    
    public function inject(CustomerManager $customerManager, BookManager $bookManager,
                            CommentManager $commentManager, LoanManager $loanManager)
    {
        $this->customerManager = $customerManager;
        $this->bookManager = $bookManager;
        $this->commentManager = $commentManager;
        $this->loanManager = $loanManager;
    }
    
    public function renderDefault()
    {
        $this->template->customers = $this->customerManager->getAll()->limit(5);
    }
}


