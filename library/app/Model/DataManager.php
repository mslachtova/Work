<?php
namespace App\Model;

use Nette;

abstract class DataManager
{
    use Nette\SmartObject;
    
    private $database;
    private $tableName;
    
    public function __construct(Nette\Database\Context $database, $tableName)
    {
        $this->database = $database;
        $this->tableName = $tableName;
    }
    
    public function create(array $data){
        $this->database->table($this->tableName)->insert($data);
    }
    
    public function find($id){
        return $this->database->table($this->tableName)->get($id);
    }
    
    public function update($id, array $data){
        return $this->database->table($this->tableName)
        ->where('id', $id)
        ->update($data);
    }
    
    public function delete($id){
        return $this->database->table($this->tableName)->where('id', $id)->delete();
    }
    
    public function getAll(){
        return $this->database->table($this->tableName);
    }
}
?>
