<?php
	namespace kompot;

    class Application {
        /**
        * Отладка кода в теле передаваемого callback
        * @param $callback
        * @param $output если true, то выводим, иначе - возвращаем резульат работы callback
        * @return string|void
        * 
        * 	\kompot\Application::debug(function() use ($USER) {
		*		var_dump($USER);
		*	});
        **/
        public static function debug(callable $callback, $output = true) {
            if( is_callable($callback) ) {
                global $USER;
                
                if( $USER->isAdmin() ) {
                    ob_start();
                        call_user_func($callback);
                    $content = ob_get_clean();

                    if($output) {
                        echo "<pre>";
                            echo $content;
                        echo "</pre>";
                    } else {
                        return $content;
                    }
                }    
            }
        }
    }

    trait BitrixHighload {
        public function getConnection() {
            // todo
        }
    }

    abstract class UserProvider {

        public $UF_NAME;
        public $UF_SURNAME;

        function __construct() {
        }

        abstract protected function fetch();
    }
	
    class Buyer extends UserProvider {
        use BitrixHighload;

        protected function fetch() {

        }
    }

    class Participant extends UserProvider {
        use BitrixHighload;

        protected function fetch() {
            
        }
    }