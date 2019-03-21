<?php 
namespace App\Model;

use Nette;

class CustomerManager
{
    use Nette\SmartObject;

    private $database;
    
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getCustomers()
    {
        return $this->database->table('customer');
    }
}
?>