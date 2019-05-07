<?php
    class tictactoeEngine{
        //board constant
        const PLAYER_X = 1;
        const PLAYER_O = 2;
        const MAX_DEPTH = 9;
        const MIN_DEPTH = 1;
        const DEFAULT_DEPTH = 3;
        const GAME_DRAWN = 511;
        const ACCEPTABLE_MOVES = [1, 2, 4, 8, 16, 32, 64, 128, 256];

        //variable of the current game state and it player
        protected $game_state;
        protected $player_turn;
        protected $bestmove = 0;
        protected $depth;
        protected $player_x;
        protected $player_o;
        
        public function __construct($game_state = 0, $player_x = 0, $player_o = 0, $turn = PLAYER_X, $depth = self::DEFAULT_DEPTH){
            //check if game state is valid;
            if($game_state <= 511 && $game_state >= 0)
                $this->game_state = $game_state;
            else
                throw new Exception("Invalid game table");

            //how deep should we search the tree (uses to set difficulty)
            if($depth <= self::MAX_DEPTH && $depth >= self::MIN_DEPTH)
                $this->depth = (int)$depth;
            else
                throw new Exception('Invalid search depth');

            //ensure player value are relevant
            if($player_x < 511 && $player_x >= 0)
                $this->player_x = $player_x;
            else 
                throw new Exception('player x score is invalid');
            
            //ensure player value are relevant
            if($player_o < 511 && $player_o >= 0)
                $this->player_o = $player_o;
            else 
                throw new Exception('player o score is invalid');

            //add player scores to ensure it isn't more than board scores
            if(($this->player_x + $this->player_o) > $this->game_state)
                throw new Exception('invalid player and board input');
            
            if($turn < 1 || $turn > 2)
                throw new Exception('invalid player turn');
            else
                $this->player_turn = $turn;
        }

        //check if a move is valid on current board
        protected function is_valid($value){
            if(!in_array(self::ACCEPTABLE_MOVES, $acceptable))
                return false;
            if(($value & $this->game_state) == $value)
                return false;

            return true;
        }

        //transform bit boards to actual moves
        protected static function zeroBit2moves($board){
            $moves = array();
            $board = strrev(decbin($board));
            //pad string
            $board = str_pad($board, 9, '0', STR_PAD_RIGHT);
            for($j = 0; $j < 9; $j++){
                if($board[$j] == '0'){
                    $moves[self::ACCEPTABLE_MOVES[$j]] = self::ACCEPTABLE_MOVES[$j];
                }
            }

            return $moves;
        }

        //alternate board players
        protected static function alt_players($value = PLAYER_X){
            if($value == self::PLAYER_X)
                return self::PLAYER_O;
            
            return self::PLAYER_X;
        }
        
        //min_max algorithm used to traverse game tree based on recursion
        protected function min_max($player_x, $player_o, $turn = 1, $max = false, $depth = 1){
            //get current board based on player scores
            $board = $player_x + $player_o;
            
            //convert bit into actaul moves
            $moves = self::zeroBit2moves($board);

            if($max){
                //intial value of a max player
                $value = -1000;
                $bestmove = array(-1000);
                foreach($moves as $move){
                    //prepare variables to test for the max branch of current move
                    $new_player_x = $player_x;
                    $new_player_o = $player_o;
                    $new_turn = self::alt_players($turn);
                    $new_depth = $depth + 1;
                    //play the move of the max player 
                    if($turn == 1)
                        $new_player_x += $move;
                    else
                        $new_player_o += $move;

                    $new_board = $new_player_o + $new_player_x;

                    //four possible condition of the board at max and how to handle it
                    if(self::game_won($new_player_x, $new_player_o)){
                        $best_value = 1/$depth;
                    }elseif(self::game_drawn($new_board)){
                        $best_value = 0;
                    }elseif($new_depth > $this->depth){
                        $best_value = 0; //if maximum depth has been reached then just score
                    }else
                        $best_value = $this->min_max($new_player_x, $new_player_o, $new_turn, false, $new_depth);
                    
                    //this is part that collate all input for max player (TOP of the tree only)
                    if($depth == 1){ 
                        $bestmove[$move] = $best_value;
                        //var_dump($move. " ". $best_value);
                    }
                    //store max input for each min max state
                    $value = max($value, $best_value);

                }

                $random_best = self::randomize($bestmove);

                //find the best out of all stored top-tree move
                $this->bestmove = array_search(max($random_best), $random_best);

                return $value;

            } else{
                //calculate the min of each board state after max player plays
                $value = +1000;
                foreach($moves as $move){
                    //prepare variables for testing each move
                    $new_player_x = $player_x;
                    $new_player_o = $player_o;
                    $new_turn = self::alt_players($turn);
                    $new_depth = $depth + 1;
                    //make a move
                    if($turn == 1)
                        $new_player_x += $move;
                    else
                        $new_player_o += $move;
                    //get new state of board
                    $new_board = $new_player_o + $new_player_x;

                    //score four possible board state for a mean player
                    if(self::game_won($new_player_x, $new_player_o)){
                        $best_value = -1/$depth;
                    }elseif(self::game_drawn($new_board))
                        $best_value = 0;
                    elseif($new_depth > $this->depth){
                        $best_value = 0;
                    }else{
                        $best_value = $this->min_max($new_player_x, $new_player_o, $new_turn, true, $new_depth);
                    }
                    
                    //find the least value               
                    $value = min($value, $best_value);
                }
                return $value;
            }
        
        }

        //help to randomise an array key
        protected static function randomize($input){
            //Hold the shuffled array
            $shuffled_array = [];
            //get key
            $keys = array_keys($input);

            //shuffle array keys
            shuffle($keys);

            foreach($keys as $key){
                $shuffled_array[$key] = $input[$key];
            }

            return $shuffled_array;
        }

        //check if a game has been won 
        protected static function game_won($player_x, $player_o){
            //the array for all possible win positions
            $win =  [7, 56, 448, 73, 146, 292, 273, 84];
            foreach($win as $value){
                if(($value & $player_x) == $value)
                    return self::PLAYER_X;
                elseif(($value & $player_o) == $value)
                    return self::PLAYER_O;
            } 
    
            return 0;
        }

        //check if a game has been drawn
        protected static function game_drawn($game_state){
            if($game_state == self::GAME_DRAWN)
                return true;
        
            return false;
        }

        //A wrapper function for who has won the game
        protected function has_won(){
            return self::game_won($this->player_x, $this->player_o);
        }

        //A wrapper function for is draw
        protected function is_draw(){
            return self::game_drawn($this->game_state);
        }


        //The only public function which only return best move
        public function getBestMove(){
             //if game state was drawn or won already, there is no best move
             if($this->is_draw() || $this->has_won()){
                return $this->bestmove = false;
            }

            //return bestmove if bestmove has been cached
            if($this->bestmove)
                return $this->bestmove;

            //if not cached then obtain new best move
            $this->min_max($this->player_x, $this->player_o, $this->player_turn, true);
            
            //then return bestmove
            return $this->bestmove;
        }

    }

    //$engine = new tictactoeEngine(0, 0, 0, 1, 4);
    //$engine->getBestMove();