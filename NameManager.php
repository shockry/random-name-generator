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
            return $randomAdjective . ' ' . $randomName;
        }
    }

    /**
    * Saves user-defined codename
    * 
    */
    public function saveCodename()
    {
        if (isset($_POST['name'])) {
            if (in_array(ucfirst(strtolower($_POST['name'])), $_SESSION['names'])) {
                return "This name is already there";
            }
        }

        if (isset($_POST['adjective'])) {
            if (in_array(ucfirst(strtolower($_POST['adjective'])), $_SESSION['adjectives'])) {
                return "This adjective is already there";
            }
        }

        return var_dump($_SESSION['adjectives']);
    }
}
