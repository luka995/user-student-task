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
    
    public function getGradeAvg()
    {
        $id = $this->student->id;
        $sql = "select avg(grade_value) as avg_grade from grade where student_id=?";
        $stm = $this->getDb()->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->execute();
        return $stm->fetchColumn();        
    }            
    
    public function getGradeCount()
    {
        $id = $this->student->id;
        $sql = "select count(*) as grade_count from grade where student_id=?";
        $stm = $this->getDb()->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->execute();
        return $stm->fetchColumn();                
    }
    
    public function getGrades()
    {
        $id = $this->student->id;
        $sql = "select grade_value from grade where student_id=? order by id";
        $stm = $this->getDb()->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->execute();
        return $stm->fetchAll();        
    }
    
    private function getStudentData()
    {
        return [
            'id' => $this->student->id,
            'name' => $this->student->student_name,            
            'board' => $this->getBoard()->board_name,
            'grades' => $this->getGrades(),
        ];
    }
    
    private function CSM()
    {
        //CSM considers pass if the average is bigger or equal to 7 and fail otherwise. Returns JSON format
        $gradeCount = $this->getGradeCount();
        $data = [
            'student' => $this->getStudentData(),           
        ];
        if ($gradeCount) {
            $gradeAvg = $this->getGradeAvg();
            $data['grade_avg'] = $gradeAvg;
            $data['pass'] = $gradeAvg >= 7;
        }
        if ($gradeCount > 4 || $gradeCount < 1) {
            $data['invalidGradeCount'] = $gradeCount;
        }    
        return json_encode($data, JSON_PRETTY_PRINT);        
    }
    
    private function CSMB()
    {
        //CSMB discards the lowest grade, if you have more than 2 grades, and considers pass if his biggest grade is bigger than 8
        
    }
    
}