<?php
namespace Controllers;

use Services\QuizService;

class QuizController extends Controller
{
    private QuizService $quizService;

    public function __construct()
    {
        $this->quizService = new QuizService();
    }

    public function getAll()
    {
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $quizzesObjects = $this->quizService->getAllQuizzes($offset, $limit);
        $this->respond($this->quizToJSON($quizzesObjects));

    }

    public function getQuizzesByTopic($topicId){
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $quizzesObjects = $this->quizService->getQuizzesByTopic($topicId, $offset, $limit);
        $this->respond($this->quizToJSON($quizzesObjects));
    }

    private function quizToJSON($inputArray){
        $quizzes = Array();

        foreach ($inputArray as $quizObject){
            $quiz = Array(
                'Id' => $quizObject->getId(),
                'name' => $quizObject->getName(),
                'nr_players' => $quizObject->getNrPlayers(),
                'avg_correct_answers' => $quizObject->getAverage(),
                'topic' => $quizObject->getTopic()->getName(),
                'level' => $quizObject->getLevel()->getName(),
                'mod_date' => $quizObject->getModDate()->format('d/m/y H:i'),
            );

            $quizzes[] = $quiz;
        }

        return $quizzes;
    }

    public function getOne($id)
    {
        $quiz = $this->quizService->getQuizById($id);

        if (!$quiz) {
            $this->respondWithError(404, "Quiz not found");
            return;
        }

        $this->respond($quiz);
    }

    public function getAllTopics(){
        $topics = $this->quizService->getAllTopics();

        $this->respond($topics);
    }

    public function getTopic($topicId){
        $topic = $this->quizService->getTopicById($topicId);

        if(!$topic){
            $this->respondWithError(404, "Topic not found");
            return;
        }

        $this->respond($topic);
    }
}