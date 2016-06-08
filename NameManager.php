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
            return $data? explode('~', $data): array();
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
            //Check to see if the adjective is already in the app
            $newAdjective = ucfirst(strtolower($_POST['adjective']));
            if (!in_array($newAdjective, $_SESSION['adjectives'])) {
                //If not, add it to the session and append it to the approperiate file
                $_SESSION['adjectives'][] = $newAdjective;
                file_put_contents('data/adjectives', '~'.$newAdjective, FILE_APPEND);
                $adjectiveSaved = true;
            } else {
                $adjectiveAlreadyThere = true;
            }
        }

        if (strlen($_POST['name']) > 0) {
            //Check to see if the name is already in the app
            $newName = ucfirst(strtolower($_POST['name']));
            if (!in_array($newName, $_SESSION['names'])) {
                //If not, add it to the session and append it to the approperiate file
                $_SESSION['names'][] = $newName;
                file_put_contents('data/names', '~'.$newName, FILE_APPEND);
                $nameSaved = true;
            } else {
                $nameAlreadyThere = true;
            }
        }

        $response = array();

        //Filter out to see what has happened
        //It might be that both a name and an adjective were inserted into the form.
        //Or it might be one of them.
        //It might also be that one of them is already in the file
        if ($nameSaved && $adjectiveSaved) {
            $response['msg'] =  'Thanks!';
            $response['status'] =  1;
        } elseif ($nameSaved) {
            if ($adjectiveAlreadyThere){
                $response['msg'] =  'Saved the name, thanks! but the adjective is already there';
                $response['status'] =  0;
            }
            else {
                $response['msg'] =  'Thanks!';
                $response['status'] =  1;
            }
        } elseif ($adjectiveSaved) {
            if ($nameAlreadyThere){
                $response['msg'] =  'Saved the adjective, thanks! but the name is already there';
                $response['status'] =  0;
            }
            else {
                $response['msg'] =  'Thanks!';
                $response['status'] =  1;
            }
        } else {
            $response['msg'] =  'Sorry, someone has read your mind, your entry is already on the system!';
            $response['status'] =  0;
        }
        
        //Sending the arrays after the modification to the browser to keep it updated
        $response['names'] = $_SESSION['names'];
        $response['adjectives'] = $_SESSION['adjectives'];

        return json_encode($response);
    }
}
