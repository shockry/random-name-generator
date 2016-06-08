<?php
include 'NameManager.php';

$nameManager = new NameManager();

?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
        <!--[if lte IE 8]>
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-old-ie-min.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
            <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
        <!--<![endif]-->
        <style>
            body {
                text-align:center;
                text-shadow: 0 1px 0 #fff;
                background:url('img/cork-wallet.png');
            }

            #codename {
                font-size: 60pt;
                font-weight: 800;
            }

            #suggestion-title {
                font-size: 20pt;
                padding: 0 10% 0 10%;
            }

            hr {
                border: 0;
                height: 1px;
                background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
            }
            .button-xlarge {
                font-size: 125%;
            }

            .pure-button {
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
        <div class="pure-g">
            <div class="pure-u-1">
                <span id="suggestion-title"> Here's a suggestion </span>
            </div>
        </div>
        <div class="pure-g">
            <div class="pure-u-1">
                <span id="codename"> <?= $nameManager->getCodename(); ?> </span>
            </div>
        </div>
        <hr>
        <div class="pure-g">
            <div class="pure-u-1">
                <button type="button" class="pure-button button-xlarge pure-button-primary" onclick="getCodename()"> Next, please </button>
            </div>
        </div>
        <form method="post" action="CallManager.php" class="pure-form" hidden>
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