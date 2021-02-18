<?php
namespace luka\model;

class Student extends Model
{    
    
    private $student = null;
    private $board = null;
    
    public function __construct($id)
    {
        $this->student = $this->getById('student', $id);
    }     
    
    protected function validate($data) 
    {
        
    }

    public function reportData() 
    {
        $board = $this->getBoard();
        if ($board->board_type == 'CSM') {
            return $this->CSM();
        }
        return $this->CSMB();
    }
    
    public function getBoard()
    {
        if (!$this->board) {
            $this->board = $this->getById('board', $this->student->board_id);
        }            
        return $this->board;        
    }
    
    private function CSM()
    {
        return "CSM";
    }
    
    private function CSMB()
    {
        return "CSMB";
    }
    
}