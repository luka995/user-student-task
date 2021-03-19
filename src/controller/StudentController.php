<?php

namespace luka\controller;

use luka\model\Student;

class StudentController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'layout/api';
        if (empty($_GET['student'])) {
            throw new \Exception('Unknown student');
        }
        $id = intval($_GET['student']);        
        $student = new \luka\model\Student($id);
        if ($student->getBoard()->board_type == 'CSM') {
            header("Content-type: text/json");
        } else {
            header("Content-type: text/xml");
        }        
        return $student->reportData();
    }
    
    public function actionCreate()
    {
        $student = new Student();
        if ($student->load($_POST['Student'] ?? null) && $student->save()) {
            $this->redirect(['site/index']);
        }
        return $this->render('student/create', ['student' => $student]);
    }
    
}
