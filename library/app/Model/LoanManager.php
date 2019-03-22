<?php 
namespace App\Model;

use Nette;

class LoanManager extends DataManager
{
    use Nette\SmartObject;
    
    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct($database, 'loan');
    }

}
?>