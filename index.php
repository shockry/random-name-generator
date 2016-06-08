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
            <input type="text" name="adjective" id="adjective" placeholder="adjective">
            <input type="text" name="name" id="name" placeholder="name">
            <button type="button" onclick="sendName()"> Send </button>
        </form>

        <script type="text/javascript">
            names = <?= json_encode($nameManager->getData('names')); ?>;
            adjectives = <?= json_encode($nameManager->getData('adjectives')); ?>;

            function getCodename() {
                //Generate some random integer
                //to be used as an index to the array holding the names/adjectives
                var randomName = names[Math.floor((Math.random() * names.length))];
                var randomAdjective = adjectives[Math.floor((Math.random() * adjectives.length))];

                document.querySelector("#codename").textContent = randomAdjective + ' ' + randomName;
            }

            //Handles saving new data to the system
            function sendName() {
                var nameValue = document.querySelector("#name").value;
                var adjectiveValue = document.querySelector("#adjective").value;

                if (nameValue === "" && adjectiveValue === "") {
                    alert("Can't go with these fields are both empty");
                    return false;
                }
                //Sending the form data to the server through an AJAX request
                httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = tellUser;
                httpRequest.open("POST", "CallManager.php");
                httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                httpRequest.send("name=" + encodeURIComponent(nameValue) +
                "&adjective=" + encodeURIComponent(adjectiveValue));
            }

            function tellUser() {
                //When the request finishes, re-populate the names and adjectives arrays with the new data
                if (httpRequest.readyState === XMLHttpRequest.DONE) {
                    if (httpRequest.status === 200) {
                        var result = JSON.parse(httpRequest.responseText);
                        alert(result.msg);
                        names = result.names;
                        adjectives = result.adjectives;

                        document.querySelector("#name").value = "";
                        document.querySelector("#adjective").value = "";
                    } else {
                        alert("I can't convince the server to save your entries at the moment, sorry :(");
                    }
                }
            }
        </script>
    </body>
</html>