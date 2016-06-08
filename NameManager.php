<?php
class NameManager
{
    function __construct()
    {
        //If the data is not in the session yet, add it to the session
        session_start();
        if (!isset($_SESSION['adjectives'])) {
            $_SESSION['adjectives'] = $this->getData('adjectives');
            $_SESSION['names'] = $this->getData('names');
        }
    }

    /**
    * Gets the data from files or session if available
    * @param $type string 'names' or 'adjectives'
    * @return array holds all names or all adjectives available depending on the parameter 
    */
    public function getData($type) 
    {
        if (isset($_SESSION[$type])) {
            return $_SESSION[$type];
        } else {
            $data = file_get_contents('data/'.$type);
            return $data? explode(',', $data): array();
        }
    }

    /**
    * Generates a random codename
    * @param $part string 
    */
    public function getCodename($part = 'both')
    {
        //Generate some random integer
        //to be used as an index to the array holding the names/adjectives
        $randomName = $_SESSION['names'][rand(0, count($_SESSION['names']) - 1)];
        $randomAdjective = $_SESSION['adjectives'][rand(0, count($_SESSION['adjectives']) - 1)];

        if ($part == 'name') {
            return $randomName;
        } elseif ($part == 'adjective') {
            return $randomAdjective;
        } else {
            return htmlspecialchars($randomAdjective) . ' ' . htmlspecialchars($randomName);
        }
    }

    /**
    * Saves user-defined codename
    * 
    */
    public function saveCodename()
    {
        $nameSaved = false;
        $adjectiveSaved = false;

        $nameAlreadyThere = false;
        $adjectiveAlreadyThere = false;

        if (strlen($_POST['adjective']) > 0) {
            if (!in_array(ucfirst(strtolower($_POST['adjective'])), $_SESSION['adjectives'])) {
                $_SESSION['adjectives'][] = ucfirst(strtolower($_POST['adjective']));
                file_put_contents('data/adjectives', ','.$_POST['adjective'], FILE_APPEND);
                $adjectiveSaved = true;
            } else {
                $adjectiveAlreadyThere = true;
            }
        }

        if (strlen($_POST['name']) > 0) {
            if (!in_array(ucfirst(strtolower($_POST['name'])), $_SESSION['names'])) {
                $_SESSION['names'][] = ucfirst(strtolower($_POST['name']));
                file_put_contents('data/names', ','.$_POST['name'], FILE_APPEND);
                $nameSaved = true;
            } else {
                $nameAlreadyThere = true;
            }
        }

        $response = array();

        if ($nameSaved && $adjectiveSaved) {
            $response['msg'] =  'All good, thanks!';
        } elseif ($nameSaved) {
            if ($adjectiveAlreadyThere){
                $response['msg'] =  'aved the name, thanks! but the adjective is already there';
            }
            else {
                $response['msg'] =  'All good, thanks!';
            }
        } elseif ($adjectiveSaved) {
            if ($nameAlreadyThere){
                $response['msg'] =  'Saved the adjective, thanks! but the name is already there';
            }
            else {
                $response['msg'] =  'All good, thanks!';
            }
        } else {
            $response['msg'] =  'Sorry, someone has read your mind, those guys are already on the system!';
        }
        
        //Sending the arrays after the modification to the browser to keep it updated
        $response['names'] = $_SESSION['names'];
        $response['adjectives'] = $_SESSION['adjectives'];

        return json_encode($response);
    }
}
