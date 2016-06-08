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
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <div class="pure-g">
            <div class="pure-u-1">
                <span id="suggestion-title"> Here's a suggestion </span>
            </div>
        </div>
        <div class="pure-g">
            <div class="pure-u-1">
                <span id="codename" onclick="selectText('codename')"> <?= $nameManager->getCodename(); ?> </span>
            </div>
        </div>
        <hr>
        <div class="pure-g">
            <div class="pure-u-1">
                <button type="button" 
                        class="pure-button button-xlarge pure-button-primary" onclick="getCodename()"> 
                    Next, please 
                </button>
            </div>
        </div>
        <div class="pure-g">
            <div class="pure-u-1">
                <button type="button" id="btn-show-form"
                        class="pure-button button-xlarge button-warning" onclick="showForm()"> 
                    Hey, I have an idea!
                </button>
            </div>
        </div>

        <form method="post" action="CallManager.php" class="pure-form" onsubmit="sendName(); return false;"  hidden>
            <div class="msg" id="result-message" hidden></div>
            <fieldset>
                <legend>Cool! let's add it to the app</legend>
                <div class="pure-g">
                    <div class="pure-u-1">
                        <input type="text" name="adjective" id="adjective" placeholder="adjective">
                    </div>
                </div>
                <div class="pure-g">
                    <div class="pure-u-1">
                        <input type="text" name="name" id="name" placeholder="name">
                    </div>
                </div>
                <button type="submit" id="btn-save" 
                        class="pure-button button-xlarge button-success"> 
                    Send
                </button>
            </fieldset>
        </form>

        <div id="footer">
            <label> Created By <a href="https://shockry.blogspot.com.eg/" target="_blank"> Shokry</a> .
                <a href="https://github.com/shockry/random-name-generator" target="_blank">
                    You can make this guy better.
                </a>
                <img src="img/github.png" width="16" height="16">
             </label>
        </div>

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
                var nameValue = document.querySelector("#name").value.trim();
                var adjectiveValue = document.querySelector("#adjective").value.trim();

                if (nameValue === "" && adjectiveValue === "") {
                    alert("Can't go with these fields are both empty");
                    return false;
                }
                //Sending the form data to the server through an AJAX request
                document.querySelector('#btn-save').setAttribute('disabled', true);
                document.querySelector('#btn-save').innerHTML = '<div class="cssload-container">'+
                    '<div class="cssload-speeding-wheel"></div></div>';

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
                        
                        if (result.status == 1) {
                            document.querySelector('#btn-save').textContent = result.msg;
                            document.querySelector("#result-message").setAttribute('hidden', true);
                            window.setTimeout(function() {
                                    document.querySelector('#btn-save').textContent = "Send";
                                }, 3000);
                        }
                        else {
                            document.querySelector('#btn-save').textContent = "Send";
                            document.querySelector("#result-message").textContent = result.msg;
                            document.querySelector("#result-message").removeAttribute('hidden');
                        }

                        names = result.names;
                        adjectives = result.adjectives;

                        document.querySelector("#name").value = "";
                        document.querySelector("#adjective").value = "";
                        document.querySelector('#btn-save').removeAttribute('disabled');
                    } else {
                        alert("I can't convince the server to save your entries at the moment, sorry :(");
                    }
                }
            }

            //Shows or hides the form when the "I have an idea" button is clicked
            function showForm() {
                if (document.querySelector('form').hasAttribute('hidden')) {
                    document.querySelector('form').removeAttribute('hidden');
                    document.querySelector('#btn-show-form').textContent = "Okay, hide these things";
                } else {
                    document.querySelector('form').setAttribute('hidden', '');
                    document.querySelector('#btn-show-form').textContent = "Hey, I have an idea!";
                    document.querySelector("#name").value = "";
                    document.querySelector("#adjective").value = "";
                    document.querySelector('#result-message').setAttribute('hidden', '');
                }
            }

            //Select the suggested name when clicked
            function selectText(containerid) {
                if (document.selection) {
                    var range = document.body.createTextRange();
                    range.moveToElementText(document.getElementById(containerid));
                    range.select();
                } else if (window.getSelection) {
                    var range = document.createRange();
                    range.selectNode(document.getElementById(containerid));
                    window.getSelection().addRange(range);
                }
            }
        </script>
    </body>
</html>