<?php
return [
    // Routes utilisateur
    'register' => ['controller' => 'UserController', 'method' => 'register'],
    'login' => ['controller' => 'UserController', 'method' => 'login'],
    'logout' => ['controller' => 'UserController', 'method' => 'logout'],
    
    // Routes quiz
    'quiz' => ['controller' => 'QuizController', 'method' => 'index'],
    'select_quiz' => ['controller' => 'QuizController', 'method' => 'select'],
    
    // Routes questions
    'add_question_to_quiz' => ['controller' => 'QuestionController', 'method' => 'addQuestionToQuiz'],
    'remove_question_from_quiz' => ['controller' => 'QuestionController', 'method' => 'removeQuestionFromQuiz'],
    
    // ...autres routes...
];
