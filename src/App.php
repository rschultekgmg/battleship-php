<?php

use Battleship\GameController;
use Battleship\Position;
use Battleship\Letter;
use Battleship\Color;

class App
{
    private static $myFleet = array();
    private static $enemyFleet = array();
    private static $console;
    private static $dev = false;

    static function runDev() {
        self::$dev = true;
        self::run();
    }

    static function run()
    {
        self::$console = new Console();
        self::$console->setForegroundColor(Color::MAGENTA);

        self::$console->println("                                     |__");
        self::$console->println("                                     |\\/");
        self::$console->println("                                     ---");
        self::$console->println("                                     / | [");
        self::$console->println("                              !      | |||");
        self::$console->println("                            _/|     _/|-++'");
        self::$console->println("                        +  +--|    |--|--|_ |-");
        self::$console->println("                     { /|__|  |/\\__|  |--- |||__/");
        self::$console->println("                    +---------------___[}-_===_.'____                 /\\");
        self::$console->println("                ____`-' ||___-{]_| _[}-  |     |_[___\\==--            \\/   _");
        self::$console->println(" __..._____--==/___]_|__|_____________________________[___\\==--____,------' .7");
        self::$console->println("|                        Welcome to Battleship                         BB-61/");
        self::$console->println(" \\_________________________________________________________________________|");
        self::$console->println();
        self::$console->resetForegroundColor();
        self::InitializeGame();
        self::StartGame();
    }

    public static function InitializeEnemyFleet()
    {
        self::$enemyFleet = GameController::initializeShips();

        $random = random_int(1, 5);
        if (self::$dev) {
            self::$console->println("I drew a list of ships for the computer: " . $random);
        }
        self::positionEnemyShips($random);
    }

    public static function getRandomPosition()
    {
        $rows = 8;
        $lines = 8;

        $letter = Letter::value(random_int(0, $lines - 1));
        $number = random_int(1, $rows);

        return new Position($letter, $number);
    }

    public static function InitializeMyFleet()
    {
        self::$myFleet = GameController::initializeShips();

        self::$console->println("Please position your fleet (Game board has size from A to H and 1 to 8) :");

        $existingPositions = [];
        foreach (self::$myFleet as $ship) {

            self::$console->println();
            printf("Please enter the positions for the %s (size: %s)", $ship->getName(), $ship->getSize());

            for ($i = 1; $i <= $ship->getSize(); $i++) {
                printf("\nEnter position %s of %s (i.e A3):", $i, $ship->getSize());
                $position = null;
                while ($position == null) {
                    try {
                        $input = readline("");
                        if (in_array($input, $existingPositions)) {
                            throw new \Exception("Position taken.");
                        }
                        $ship->addPosition($input);
                        $existingPositions[] = $input;
                        $position = true;
                    } catch (\Exception $e) {
                        self::$console->setForegroundColor(Color::RED);
                        self::$console->println($e->getMessage() . Color::WHITE);
                        $position = null;
                        continue;
                    }
                }
            }
        }
    }

    public static function beep()
    {
        echo "\007";
    }

    public static function InitializeGame()
    {
        self::InitializeMyFleet();
        self::InitializeEnemyFleet();
    }

    public static function StartGame()
    {
        self::$console->println("\033[2J\033[;H");
        self::$console->println("                  __");
        self::$console->println("                 /  \\");
        self::$console->println("           .-.  |    |");
        self::$console->println("   *    _.-'  \\  \\__/");
        self::$console->println("    \\.-'       \\");
        self::$console->println("   /          _/");
        self::$console->println("  |      _  /\" \"");
        self::$console->println("  |     /_\'");
        self::$console->println("   \\    \\_/");
        self::$console->println("    \" \"\" \"\" \"\" \"");

        $gameInProgress = true;
        while ($gameInProgress) {
            self::$console->println(Color::WHITE);
            self::$console->println("======================================");
            self::$console->println(Color::YELLOW);
            self::$console->println("Player, it's your turn");
            self::$console->println(Color::WHITE);
            self::$console->println("Enter coordinates for your shot :");
            $position = readline("");

            $isHit = GameController::checkIsHit(self::$enemyFleet, self::parsePosition($position));
            if ($isHit) {
                self::beep();
                self::$console->println(Color::RED);
                self::$console->println("                \\         .  ./");
                self::$console->println("              \\      .:\" \";'.:..\" \"   /");
                self::$console->println("                  (M^^.^~~:.'\" \").");
                self::$console->println("            -   (/  .    . . \\ \\)  -");
                self::$console->println("               ((| :. ~ ^  :. .|))");
                self::$console->println("            -   (\\- |  \\ /  |  /)  -");
                self::$console->println("                 -\\  \\     /  /-");
                self::$console->println("                   \\  \\   /  /");
                self::$console->println("");
                self::$console->println("Yeah ! Nice hit !");
            } else {
                self::$console->println(Color::CADET_BLUE);
                self::$console->println("≋≋≋≋≋      |\\        ≋≋≋≋≋");
                self::$console->println(" ≋≋≋≋     |⚓\\       ≋≋≋≋");
                self::$console->println("  ≋≋≋     |  \\      ≋≋≋");
                self::$console->println("≋≋≋≋≋     |   \\    ≋≋≋≋≋");
                self::$console->println(" ≋≋≋ .|   _|    \\    ≋≋≋");
                self::$console->println("≋≋≋≋ /|\\  |⎈      \\  ≋≋≋≋");
                self::$console->println(" ≋≋≋/_|_\\_|________|\\≋≋≋");
                self::$console->println("≋≋≋\\______________|  \\≋≋≋");
                self::$console->println("≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋");
                self::$console->println("   ≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋");
                self::$console->println("");
                self::$console->println("Miss");
            }
            $gameInProgress = self::endGame(self::$enemyFleet);
            if(!$gameInProgress) {
                 self::showVictoryMessage();
            }
            self::$console->println(Color::WHITE);

            if ($gameInProgress) {
                self::$console->println(Color::YELLOW);
                self::$console->println("Enemy ships that has been sunk:");
                foreach (self::$enemyFleet as $ship) {
                    if ($ship->isSunk()) {
                        self::$console->println(Color::RED . "You sunk the " . $ship->getName() . Color::WHITE);
                    }
                }
                $position = self::getRandomPosition();
                $isHit = GameController::checkIsHit(self::$myFleet, $position);
                self::$console->println();
                self::$console->println("======================================");
                self::$console->println($isHit ? Color::RED : Color::CADET_BLUE);
                printf("Computer shoot in %s%s and %s", $position->getColumn(), $position->getRow(), $isHit ? "hit your ship !\n" : "miss");
                if ($isHit) {
                    self::beep();
                    self::$console->println(Color::RED);
                    self::$console->println("                \\         .  ./");
                    self::$console->println("              \\      .:\" \";'.:..\" \"   /");
                    self::$console->println("                  (M^^.^~~:.'\" \").");
                    self::$console->println("            -   (/  .    . . \\ \\)  -");
                    self::$console->println("               ((| :. ~ ^  :. .|))");
                    self::$console->println("            -   (\\- |  \\ /  |  /)  -");
                    self::$console->println("                 -\\  \\     /  /-");
                    self::$console->println("                   \\  \\   /  /");
                } else {
                    self::$console->println(Color::CADET_BLUE);
                    self::$console->println("≋≋≋≋≋      |\\        ≋≋≋≋≋");
                    self::$console->println(" ≋≋≋≋     |⚓\\       ≋≋≋≋");
                    self::$console->println("  ≋≋≋     |  \\      ≋≋≋");
                    self::$console->println("≋≋≋≋≋     |   \\    ≋≋≋≋≋");
                    self::$console->println(" ≋≋≋ .|   _|    \\    ≋≋≋");
                    self::$console->println("≋≋≋≋ /|\\  |⎈      \\  ≋≋≋≋");
                    self::$console->println(" ≋≋≋/_|_\\_|________|\\≋≋≋");
                    self::$console->println("≋≋≋\\______________|  \\≋≋≋");
                    self::$console->println("≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋");
                    self::$console->println("   ≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋≋");
                    self::$console->println("");
                    self::$console->println("Miss");
                }
                self::$console->println(Color::WHITE);
                $gameInProgress = self::endGame(self::$enemyFleet);
                if(!$gameInProgress) {
                    self::$console->println(Color::YELLOW . "You LOST!" . Color::WHITE);
                }
            }

            if (!$gameInProgress) {
                self::$console->println(Color::YELLOW);
                self::$console->println("Game over!");
            }
        }
        $closeConsole = true;
        while ($closeConsole) {    
            self::$console->println("Press ENTER if you want to quit the game");    
            readline("");
            $closeConsole = false;
        }
    }

    private static function showVictoryMessage() {
        self::beep();
        self::$console->println(Color::YELLOW);
        self::$console->println("       ⭐️ YOU ARE THE WINNER! ⭐️");
        self::$console->println("    \\☆.。.:*・°☆.。.:*・°☆.。.:*・°☆./");
        self::$console->println("         _______________");
        self::$console->println("        |@@@@|     |####|");
        self::$console->println("        |@@@@|     |####|");
        self::$console->println("        |@@@@|     |####|");
        self::$console->println("        \\@@@@|     |####/");
        self::$console->println("         \\@@@|     |###/");
        self::$console->println("          `@@|_____|##'");
        self::$console->println("               (O)");
        self::$console->println("           .-''''''-.");
        self::$console->println("         .'  * * *  `.");
        self::$console->println("        :  *       *  :");
        self::$console->println("        : ~ WINNER  ~ :");
        self::$console->println("         :  *     *  :");
        self::$console->println("          `.  * *  .'");
        self::$console->println("            `-...-'");
        self::$console->println(Color::CHARTREUSE);
        self::$console->println("       🏆 VICTORY IS YOURS! 🏆");
        self::$console->println(Color::YELLOW);
        self::$console->println("    /☆.。.:*・°☆.。.:*・°☆.。.:*・°☆\\");
        self::$console->println("");
    }

    public static function endGame ($fleet) {
        $endGame = true;

        foreach ($fleet as $ship) {
            if (!$ship->isSunk()) {
                $endGame = false;
            }
        }

        if ($endGame) {
            return false;
        }

        return true;
    }

    public static function parsePosition($input)
    {
        $letter = substr($input, 0, 1);
        $number = substr($input, 1, 1);

        if(!is_numeric($number)) {
            throw new Exception("Not a number: $number");
        }

        return new Position($letter, $number);
    }

    public static function positionEnemyShips($random)
    {
        if ($random == 1) {
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 4));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 5));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 6));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 7));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 8));

            array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 6));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 7));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 8));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 9));

            array_push(self::$enemyFleet[2]->getPositions(), new Position('A', 3));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('B', 3));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('C', 3));

            array_push(self::$enemyFleet[3]->getPositions(), new Position('F', 8));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('G', 8));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('H', 8));

            array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 5));
            array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 6));
        } elseif($random == 2) {
            // Pięciomasztowiec
            array_push(self::$enemyFleet[0]->getPositions(), new Position('C', 2));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('C', 3));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('C', 4));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('C', 5));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('C', 6));

            // Czteromasztowiec
            array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 5));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('F', 5));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('G', 5));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('H', 5));

            // Trzymasztowiec 1
            array_push(self::$enemyFleet[2]->getPositions(), new Position('A', 7));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('B', 7));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('C', 7));

            // Trzymasztowiec 2
            array_push(self::$enemyFleet[3]->getPositions(), new Position('E', 1));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('F', 1));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('G', 1));

            // Dwumasztowiec
            array_push(self::$enemyFleet[4]->getPositions(), new Position('B', 4));
            array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 4));
        } elseif($random == 3) {
            // Pięciomasztowiec
            array_push(self::$enemyFleet[0]->getPositions(), new Position('D', 2));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('E', 2));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('F', 2));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('G', 2));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('H', 2));

            // Czteromasztowiec
            array_push(self::$enemyFleet[1]->getPositions(), new Position('B', 5));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('B', 6));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('B', 7));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('B', 8));

            // Trzymasztowiec 1
            array_push(self::$enemyFleet[2]->getPositions(), new Position('A', 4));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('A', 5));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('A', 6));

            // Trzymasztowiec 2
            array_push(self::$enemyFleet[3]->getPositions(), new Position('F', 4));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('G', 4));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('H', 4));

            // Dwumasztowiec
            array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 3));
            array_push(self::$enemyFleet[4]->getPositions(), new Position('D', 3));
        } elseif ($random == 4) {
            // Pięciomasztowiec
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 1));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 2));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 3));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 4));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('B', 5));

            // Czteromasztowiec
            array_push(self::$enemyFleet[1]->getPositions(), new Position('E', 3));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('F', 3));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('G', 3));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('H', 3));

            // Trzymasztowiec 1
            array_push(self::$enemyFleet[2]->getPositions(), new Position('D', 6));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('D', 7));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('D', 8));

            // Trzymasztowiec 2
            array_push(self::$enemyFleet[3]->getPositions(), new Position('A', 2));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('A', 3));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('A', 4));

            // Dwumasztowiec
            array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 5));
            array_push(self::$enemyFleet[4]->getPositions(), new Position('D', 5));
        } else {
            // Pięciomasztowiec
            array_push(self::$enemyFleet[0]->getPositions(), new Position('E', 1));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('E', 2));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('E', 3));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('E', 4));
            array_push(self::$enemyFleet[0]->getPositions(), new Position('E', 5));

            // Czteromasztowiec
            array_push(self::$enemyFleet[1]->getPositions(), new Position('A', 5));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('A', 6));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('A', 7));
            array_push(self::$enemyFleet[1]->getPositions(), new Position('A', 8));

            // Trzymasztowiec 1
            array_push(self::$enemyFleet[2]->getPositions(), new Position('C', 3));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('D', 3));
            array_push(self::$enemyFleet[2]->getPositions(), new Position('E', 3));

            // Trzymasztowiec 2
            array_push(self::$enemyFleet[3]->getPositions(), new Position('F', 6));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('G', 6));
            array_push(self::$enemyFleet[3]->getPositions(), new Position('H', 6));

            // Dwumasztowiec
            array_push(self::$enemyFleet[4]->getPositions(), new Position('B', 4));
            array_push(self::$enemyFleet[4]->getPositions(), new Position('C', 4));
        }
    }
}
