<?php

namespace luka\model;

use Spatie\ArrayToXml\ArrayToXml;

class Student extends Model
{    
    
    private $student = null;
    private $board = null;
    
    protected $attrs = ['student_name', 'board_id'];
    
    public function __construct($id = null)
    {
        if ($id) {
            $this->student = $this->getById('student', $id);
            if (!$this->student) {
                throw new \Exception('Unknown student');
            }
        }
    }     
    
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $cols = implode(', ', $this->attrs);     
        $placeholders = implode(', ', array_fill(0, count($this->attrs), '?'));
        $sql = "INSERT INTO student($cols) VALUES($placeholders)";
        $stm = $this->getDb()->prepare($sql);
        $arr = array_values((array)$this->student);
        if ($stm->execute($arr)) {
            $this->student->id = $this->getDb()->lastInsertId();
            return true;
        }
        return false;        
    }
    
    public function load($data) 
    {
        if (!$data) {
            return false;
        }
        $student = new \stdClass();
        foreach ($this->attrs as $attr) {
            $student->$attr = $data[$attr] ?? null;
        }
        $this->student = $student;
        return $this->validate();
    }
    
    public function getData()
    {
        return $this->student;
    }
    
    protected function validate() 
    {
        if (empty($this->student->student_name)) {
            $this->errors['student_name'] = 'Missing student name';
        }
        if (empty($this->student->board_id)) {
            $this->errors['board_id'] = 'Missing board';
        }
        return empty($this->errors);
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
    
    public function getGradeAvg($array)
    {
       return number_format(array_sum($array) / count($array), 2) ;       
    }            
    
    public function getGradeCount($data)
    {           
        return count($data['grades']);
    }
    
    public function getGrades()
    {
        $id = $this->student->id;
        $sql = "select grade_value from grade where student_id=? order by id";
        $stm = $this->getDb()->prepare($sql);
        $stm->bindValue(1, $id);
        $stm->execute();
        return array_values(array_column($stm->fetchAll(\PDO::FETCH_ASSOC), 'grade_value'));        
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
    
    private function checkGradeCount($gradeCount, &$data)
    {
        if ($gradeCount > 4 || $gradeCount < 1) {
            $data['student']['invalidGradeCount'] = $gradeCount;
        }         
    }
    
    const CSM_THRESHOLD = 7;
    private function CSM()
    {
        //CSM considers pass if the average is bigger or equal to 7 and fail otherwise. Returns JSON format
        $studentData = $this->getStudentData();
        $gradeCount = $this->getGradeCount($studentData);
        $data = [
            'student' => $studentData,           
        ];
        if ($gradeCount) {
            $gradeAvg = $this->getGradeAvg($studentData['grades']);
            $data['student']['grade_avg'] = $gradeAvg;
            $data['student']['final_result'] = $gradeAvg >= self::CSM_THRESHOLD ? 'Pass' : 'Fail';
        }
        $this->checkGradeCount($gradeCount, $data);
        return json_encode($data, JSON_PRETTY_PRINT);        
    }
    
    const CSMB_TRESHOLD = 8;
    private function CSMB()
    {        
        //CSMB discards the lowest grade, if you have more than 2 grades, and considers pass if his biggest grade is bigger than 8
        $studentData = $this->getStudentData();        
        $data = [
            'student' => $studentData,           
        ];        
        $grades = $studentData['grades'];
        $gradeCount = $this->getGradeCount($studentData);        
        if ($gradeCount) {
            sort($grades);
            if ($gradeCount > 2) {
                array_shift($grades);                
            }
            $gradeAvg = $this->getGradeAvg($grades);
            $bigestGrade = $grades[count($grades) - 1];
            $data['student']['grades'] = ['grade' => $grades];
            $data['student']['final_result'] = $bigestGrade > self::CSMB_TRESHOLD ? 'Pass' : 'Fail';
            $data['student']['grade_avg'] = $gradeAvg;            
        }
        $this->checkGradeCount($gradeCount, $data);        
        $arrayToXml = new ArrayToXml($data);
        return $arrayToXml->dropXmlDeclaration()->toXml();                
    }
    
}