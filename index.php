<?php
include 'NameManager.php';

$nameManager = new NameManager();

?>

<html>
    <head></head>
    <body>
        <h3 id="codename"> <?= $nameManager->getCodename(); ?> </h3>
        <button type="button" onclick="getCodename()"> Next, please </button>
        <form method="post" action="CallManager.php">
            <input type="text" name="adjective" placeholder="adjective">
            <input type="text" name="name" placeholder="name">
            <button type="button" value="Send">
        </form>

        <script type="text/javascript">
            names = <?= json_encode($nameManager->getData('names')); ?>;
            adjectives = <?= json_encode($nameManager->getData('adjectives')); ?>;

            function getCodename() {
            
              console.log(names);
            }

            function getCodename() {
                //Generate some random integer
                //to be used as an index to the array holding the names/adjectives
                var randomName = names[Math.floor((Math.random() * names.length))];
                var randomAdjective = adjectives[Math.floor((Math.random() * adjectives.length))];

                document.querySelector("#codename").textContent = randomAdjective + ' ' + randomName;
                
            }
        </script>
    </body>
</html>