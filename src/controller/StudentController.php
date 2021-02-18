<?php

namespace luka\controller;

class StudentController extends Controller
{
    public function actionIndex()
    {
        if (empty($_GET['student'])) {
            die ('Nije naveden student');
        }
        $id = intval($_GET['student']);
        
        $student = new \luka\model\Student($id);
        return $student->reportData();
    }
}
