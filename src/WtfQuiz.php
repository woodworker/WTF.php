<?php

class WtfQuiz {
    protected $questionDir;
    protected $collectedQuestions = array();
    protected $maxQuestions;

    public function __construct($dir, $maxQuestions = 5) {
        $this->questionDir = $dir;
        $this->maxQuestions = $maxQuestions;

        $this->collectQuestions();

        if ($this->getQuestionCount() < $maxQuestions ) {
            $this->maxQuestions = $this->getQuestionCount();
        }
    }

    protected function collectQuestions () {
        $it = new DirectoryIterator($this->questionDir);
        foreach( $it as $oneDir ) {
            if( !$oneDir->isDot() && file_exists($oneDir->getPathname().'/src.php') ) {
                $this->collectedQuestions[] = $oneDir->getPathname();
            }
        }
    }

    protected function popQuestionDir() {
        return array_pop($this->collectedQuestions);
    }

    protected function askQuestion () {
        $question = $this->popQuestionDir();

        echo "Look at this source.\n\n";
        echo file_get_contents($question.'/src.php');

        $input = $this->askUser("\n\nWhat will be echoed?");
        ob_start();
        $output = system('/usr/bin/env php '.$question.'/src.php');
        ob_end_clean();

        if ( $input == $output ){
            $this->score++;
            printf("Correct!\n");
        } else {
            echo "Sorry but the correct answer was:\n";
            echo $output;
            echo "\n\n";
        }

        if ( file_exists($question.'/explanation.txt') ) {
            $read = $this->askUser("Want to read the explanation? [y|n]");
            if (strtoupper($read) == 'Y' ) {
                echo "\n====================================================\n";
                echo trim(file_get_contents($question.'/explanation.txt'));
                echo "\n====================================================\n";
                echo "\n";
            }
        }
    }

    protected function askUser($question) {
        printf( "%s \n" , $question );
        $input = trim(fgets(STDIN));
        return $input;
    }

    public function getQuestionCount() {
        return count( $this->collectedQuestions );
    }

    public function start () {
        printf("Found %u question(s)\n", $this->getQuestionCount());

        for ( $i = 0 ; $i < $this->maxQuestions ; $i++) {
            $this->askQuestion();
        }

        printf("Your Total Score: %u out of %u \n", $this->score, $this->maxQuestions);
    }
}
