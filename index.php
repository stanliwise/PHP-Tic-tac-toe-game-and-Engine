<?php
    include_once('tictactoeEngine.php');
    session_start();

    //constants to define game state and player
    define('GAME_WON', 1);
    define('GAME_DRAWN', 2);
    define('PLAYER_X', 1);
    define('PLAYER_O', 2);

    //message for current game state
    $message = 'Welcome click any box to get game started';

    //initialize game session variable are empty
    if(count($_SESSION) == 0 || !isset($_SESSION)){
        reset_board();
    }

    //Event: when a new game is recorded
    if(isset($_GET['submit'])){
        reset_board();
    }

    //Event: when a move is inserted
    if(isset($_GET['key'])){
        $key = (int)$_GET['key'];
        play_game($key, $message);
    }

    function computer_play(){
        //check turn
        if($_SESSION['computer'] == 1 && ($_SESSION['c_turn'] == $_SESSION['turn'])){
            global $message;
            //call the Engine engine(game_score, player_x_score, Player_o_score, which_turn(1 for x | 2 for y), depth(max is 9))
            $engine = new tictactoeEngine($_SESSION['game'], $_SESSION['player_x'], $_SESSION['player_o'], $_SESSION['c_turn'], $_SESSION['c_strength']);
            $key = $engine->getBestMove();
            if($key)
                play_game($key, $message);
        }

        return false;
    }
    //reset board for current session
    function reset_board(){
        $_SESSION['move'] = array( 
                                1 => '-', 
                                2 => '-',
                                4 => '-',
                                8 => '-',
                                16 => '-',
                                32 => '-',
                                64 => '-',
                                128 => '-',
                                256 => '-'
                            );
        $_SESSION['game'] = 0;
        $_SESSION['state'] = 0;
        $_SESSION['turn'] = PLAYER_X;
        $_SESSION['player_x'] = 0;
        $_SESSION['player_o'] = 0;
        $_SESSION['computer'] = 0;
        $_SESSION['c_strength'] = 5;
        $_SESSION['c_turn'] = 1;
        //set computer 
        if(isset($_GET['opponent']) && $_GET['opponent'] == 1){
            $_SESSION['computer'] = (int)$_GET['opponent'];
        }
        //set computer strength
        if(isset($_GET['strength']) && $_GET['strength'] >= 1){
            $_SESSION['c_strength'] = (int)$_GET['strength'];
        }
        //set computer turn
        if(isset($_GET['turn']) && ($_GET['turn'] > 1 && $_GET['turn'] < 3)){
            $_SESSION['c_turn'] = (int)$_GET['turn'];
        }

        //call computer play if computer was the first opponent
        computer_play();
    }

    //check if board is full and no winner(draw)
    function game_drawn(){
        $draw = 511;
        $game = $_SESSION['game'];
        if($game == $draw)
            return true;
        
        return false;
    }

    //check board if a player has won
    function game_won(){
        $player_x = $_SESSION['player_x'];
        $player_o = $_SESSION['player_o'];

        $win =  [7, 56, 448, 73, 146, 292, 273, 84];
        foreach($win as $value){
            if(($value & $player_x) == $value){
                paint_win($value);
                return "X won the game";
            }
            elseif(($value & $player_o) == $value){
                paint_win($value);
                return "O won the game";
            }
        }

        return 0;
    }

    //paint the winning line of the game
    function paint_win($value){
        //if game has already been won or drawn it means it has been painted
        if($_SESSION['state'] == GAME_WON || ($_SESSION['state'] == GAME_DRAWN))
            return;

        $value = (int)$value;
        $value = strrev(decbin($value));
        $value_length = strlen($value);
        for($i=1, $j=0; $i <= 256 && $j < $value_length; $i *= 2, $j++){
            if($value[$j] == '1'){
                $_SESSION['move'][$i] = "<span style='color:red'> {$_SESSION['move'][$i]} </span>";
            }
        }
    }

    //check if a move is valid or is avaliable
    function is_valid($value){
        //all legal move
        $game = [1, 2, 4, 8, 16, 32, 64, 128, 256];
        if(!in_array($value, $game))
            return false;
        if(($value & $_SESSION['game']) == $value)
            return false;

        return true;
    }

    //the main code of the game that receive input and send appropraite message
    function play_game($value, &$message){
        //if the game has been won already just return the won message
        if($_SESSION['state'] == GAME_WON){
            $message = game_won();
            return;
        }

        //if game has been drawn already
        if($_SESSION['state'] == GAME_DRAWN){
            $message = "Draw";
            return;
        }
        
        //prevent useless input and force to an integer
        $value = (int)$value;
        if(is_valid($value)){ 
            if($_SESSION['turn'] == PLAYER_X){
                $_SESSION['move'][$value] = 'X';
                $_SESSION['player_x'] += $value;
                $_SESSION['turn'] = PLAYER_O;
                $message = "O to play";
            }else{
                $_SESSION['move'][$value] = 'O';
                $_SESSION['player_o'] += $value;
                $_SESSION['turn'] = PLAYER_X;
                $message = "X to play";
            }

            //increase game bit
            $_SESSION['game'] += $value;

            //Evaluate board for win or draw;
            if(game_won()){
                $message = game_won();
                $_SESSION['state'] = GAME_WON;
                return;
            }

            if(game_drawn()){
                $message = "Draw";
                $_SESSION['state'] = GAME_DRAWN;
                return;
            }

            //this code runs if computer was turned on
            if($_SESSION['computer'])
                computer_play();

            return;
        }else{

            //no moves were valid
            $message = 'Select a valid move';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game programming - Tic-tac-toe</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css">
    <style>
        .table td{
            text-align: center;
        }

        .table a{
            display: block;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1 class="text-center h1" style="margin-top:20px;">Tic-Tac-Toe</h1>
     <div class="container" style="width:200px;">
        <div class="container">
            <p>select an opponent</p>
            <form method="GET" action="" id="game_control">
                <select name="opponent">
                    <option value="1" <?php if($_SESSION['computer']) echo 'selected'; ?>>computer</option>
                    <option value="2"<?php if(($_SESSION['computer']) == 0) echo 'selected'; ?>>human</option>
                </select>
                <br/>
                <p> Adjust strength(for computer mode only)</p>
                <select name="strength">
                    <option value="1" <?php if(($_SESSION['c_strength']) == 1) echo 'selected'; ?>>Dummy</option>
                    <option value="2" <?php if(($_SESSION['c_strength']) == 2) echo 'selected'; ?>>Easy</option>
                    <option value="4" <?php if(($_SESSION['c_strength']) == 4) echo 'selected'; ?>>Intermediate</option>
                    <option value="5" <?php if(($_SESSION['c_strength']) == 5) echo 'selected'; ?>>Hard</option>
                    <option value="9" <?php if(($_SESSION['c_strength']) == 9) echo 'selected'; ?>>Ultimate</option>
                </select>
                <br/>
                <p>pick computer turn (for computer mode only)</p>
                <select name="turn">
                    <option value="1" <?php if(($_SESSION['c_turn']) == 1) echo 'selected'; ?>>X</option>
                    <option value="2" <?php if(($_SESSION['c_turn']) == 2) echo 'selected'; ?>>O</option>
                </select>
                <br/>
                <button type="submit" class="btn btn-primary" name="submit">Reset Board</button> (click reset to reflect adjustment)
            </form>
        </div>
        <p class='p' style="margin-bottom: 20px; font-weight:bold"><?php echo $message; ?> </p>
        <table class="table table-dark table-bordered">
            <tr>
                <td><a href="?key=1"><?php echo $_SESSION['move'][1]; ?></a></td>
                <td><a href="?key=2"><?php echo $_SESSION['move'][2]; ?></a></td>
                <td><a href="?key=4"><?php echo $_SESSION['move'][4]; ?></a></td>
            </tr>
            <tr>
                <td><a href="?key=8"><?php echo $_SESSION['move'][8]; ?></a></td>
                <td><a href="?key=16"><?php echo $_SESSION['move'][16]; ?></a></td>
                <td><a href="?key=32"><?php echo $_SESSION['move'][32]; ?></a></td>
            </tr>
            <tr>
                <td><a href="?key=64"><?php echo $_SESSION['move'][64]; ?></a></td>
                <td><a href="?key=128"><?php echo $_SESSION['move'][128]; ?></a></td>
                <td><a href="?key=256"><?php echo $_SESSION['move'][256]; ?></a></td>
            </tr>
        </table>
    </div>
     <script src="assets/js/jquery.js"></script>
     <script src="assets/js/bootstrap.js"></script>
</body>
</html>