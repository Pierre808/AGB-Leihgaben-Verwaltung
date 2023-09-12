<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <base href="<?= base_url() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGB Leihgaben-Verwaltung <?= $this->renderSection("title") ?> </title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/nav.css') ?>">

    <?= $this->renderSection("headerLinks") ?>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Roboto&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>
    <div id="navbar">
        <div id="navTitle">
            <a href="<?= base_url() ?>">
                <h1>AGB Leihgaben-Verwaltung</h1>
            </a>
        </div>

        <div id="navMenu">
            <a onclick="showMenu()">
                <img src="<?= base_url('imgs/menu.png') ?>">
            </a>
        </div>
    </div>

    <div id="menu">
        <div id="closeBtn" class="close">
            <img src="<?= base_url("imgs/close.png") ?>" onclick="hideMenu()"/>
        </div>

        <div class="menu-links">
            <a href="<?= base_url() ?>">
                <div id="home" class="menu-link <?php if(esc($menuName) == "home"){echo("active");} ?>">
                    <img src="<?= base_url("imgs/home.png") ?>"/>
                    <p>Start Seite</p>
                </div>
            </a>

            <a href="<?= base_url("all-leihgabe") ?>">
                <div class="menu-link <?php if(esc($menuName) == "leihgaben"){echo("active");} ?>">
                    <img src="<?= base_url("imgs/leihgaben3.png") ?>"/>
                    <p>Alle Leihgaben</p>
                </div>
            </a>

            <a href="<?= base_url("all-schueler") ?>">
                <div class="menu-link <?php if(esc($menuName) == "schueler"){echo("active");} ?>">
                    <img src="<?= base_url("imgs/account.png") ?>"/>
                    <p>Registrierte Schüler</p>
                </div>
            <a>

            <a href="<?= base_url("all-gegenstande") ?>">
                <div class="menu-link <?php if(esc($menuName) == "gegenstande"){echo("active");} ?>">
                    <img src="<?= base_url("imgs/gegenstand.png") ?>"/>
                    <p>Registrierte Gegenstände</p>
                </div>
            <a>

            
            <div id="addBtn" class="menu-link <?php if(esc($menuName) == "add"){echo("active");} ?>" 
            onclick="calcMenu()">
                <img src="<?= base_url("imgs/add.png") ?>"/>
                <p>Hinzufügen</p>
            </div>

            <div id="ausleiheBtn" class="subordered <?php if(esc($menuName) == "add"){
                if(esc($menuTextName) == "leihgabe"){
                    echo("active-text");
                }
            } ?>">
                <a href="<?= base_url("select-method") ?>">    
                    <p>Leihgabe erstellen</p>
                </a>        
            </div>
            
            <div id="gegenstandBtn" class="subordered <?php if(esc($menuName) == "add"){
                if(esc($menuTextName) == "gegenstand"){
                    echo("active-text");
                }
            } ?>">
                <a href="<?= base_url("add-gegenstand") ?>">
                    <p>Gegenstand hinzufügen</p>
                </a>
            </div>
        </div>
        
        <div class="menu-footer">
            <p>Ein Programm von Pierre Equit - Pierre.equit@gmail.com</p>
        </div>
    </div>



    <script>
        //menu

        var closeBtn = document.getElementById("closeBtn");

        var navMenu = document.getElementById("navMenu");
        var menu = document.getElementById("menu");

        
        var addBtn = document.getElementById("addBtn");
        var ausleiheBtn = document.getElementById("ausleiheBtn");
        var gegenstandBtn = document.getElementById("gegenstandBtn");
        var lastActive = "home";

        async function showMenu() {
            menu.style.display = "block";
            navMenu.classList.add("rotate");

            await new Promise(resolve => setTimeout(resolve, 50));

            menu.style.top = "0vh";
            document.body.style.overflow = "hidden"

            <?php if(esc($menuName) == "add"){
            ?>
                showAddLinks();
            <?php
            } ?>
        }

        async function hideMenu() {
            closeBtn.classList.add("rotate");

            await new Promise(resolve => setTimeout(resolve, 100));

            menu.style.top = "-100vh";
            document.body.style.overflow = "auto"

            hideAddLinks();
            
            await new Promise(resolve => setTimeout(resolve, 700));

            menu.style.display = "none";

            closeBtn.classList.remove("rotate");
            navMenu.classList.remove("rotate");
        }

        function calcMenu(){
            if(ausleiheBtn.style.display == "block"){
                hideAddLinks();
            }
            else{
                showAddLinks();
            }
        }

        function showAddLinks() {

            //addBtn.classList.add("active");
            ausleiheBtn.style.display = "block";
            gegenstandBtn.style.display = "block";
        }

        function hideAddLinks() {
            //addBtn.classList.remove("active");
            ausleiheBtn.style.display = "none";
            gegenstandBtn.style.display = "none";
        }
    </script>
    
    <script src="js/processBarcode.js"></script>

    <?= $this->renderSection("content") ?>

    <script>
        //barcode
        function redirect(href) {
            if(href == "current") {
                href = window.location.href;
            }
            window.location.href = href;
        }

        async function startSuccessAnim(redirect) {
            var animationDiv = document.getElementById("animationDiv");
            var loading = document.getElementById("loading-box");

            loading.style.display = "none";

            animationDiv.innerHTML += 
            '<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" /> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" /> </svg>';
            
            
            await new Promise(resolve => setTimeout(resolve, 1800));

            if(redirect == "current") {
                redirect = window.location.href;
            }
            window.location.href = redirect;
        }

        async function startErrorAnim(errorText, links) {
            var animationDiv = document.getElementById("animationDiv");
            var loading = document.getElementById("loading-box");
            var errorDiv = document.getElementById("errorDiv");

            loading.style.display = "none";

            animationDiv.innerHTML += 
            '<div class="fail-container"> <div class="circle-border"></div> <div class="circle"> <div class="error"></div> </div> </div>';
            
            await new Promise(resolve => setTimeout(resolve, 500));

            let html = '<p>' + errorText + '<br>';
            links.forEach(function(link, index) {
                if(index != 0)
                {
                    html += " oder ";
                }

                html += '<a href="' + link.link + '">' + link.link_text + '</a>';
            });
            if(links.length != 0)
            {
                html += " oder ";
            }
            html += '<a href="' + window.location.href + '">erneut scannen</a>';
            html += '</p> <img src="imgs/warning_white.png"/>';
            errorDiv.innerHTML = html;

            errorDiv.classList.add("warning");
            errorDiv.classList.add("warning-with-img");
        }
    </script>
</body>
</html>

