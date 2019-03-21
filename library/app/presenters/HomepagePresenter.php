<?php

namespace App\Presenters;

use Nette;
use App\Model\CustomerManager;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $customerManager;
    
    /*public function __construct(CustomerManager $customerManager)
    {
        $this->customerManager = $customerManager;
    }*/
    
    public function injectCustomerManager(CustomerManager $customerManager)
    {
        $this->customerManager = $customerManager;
    }
    
    public function renderDefault()
    {
        $this->template->customers = $this->customerManager->getCustomers()->limit(5);
    }
}


