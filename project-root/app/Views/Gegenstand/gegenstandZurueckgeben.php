<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/waiting.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1 id="title">Gegenstand zurückgeben:</h1>

        <?php
            if(esc($lastInfo) != null)
            { ?>
                <div class="warning warning-green" id="warning100">
                    <p class="big">Letzter gescannter Gegenstand: <span class="standart left-margin"><?= $lastInfo['gegenstand'] ?></span></p>
                    <p class="big">Ausgeliehen von: <span class="standart left-margin"><?= $lastInfo['schueler'] ?></span></p>
                </div>    
            <?php }
        ?>

        <br>
        <br>

        <div class="warning ">
            <p>
                Um einen Barcode scannen zu können, wird ein Barcode-reader benötigt, welcher sich im A-Turm Keller befindet.
            </p>
        </div>
        
        <div id="errorDiv">
            
        </div>
        
        <div class="waiting-success-cointainer">
            <div id="loading-box">
                <h2>Jetzt Gegenstand mit Barcode-reader einscannen</h2>
                <div class="loader">
                    <span class="loader-element"></span>
                    <span class="loader-element"></span>
                    <span class="loader-element"></span>
                </div>
            </div>

            <div id="animationDiv">
                
            </div>
        </div>
        
    </div>

    <script>
        let reading = true;
        let code = '';
        document.addEventListener("keydown", e => {
            if(reading)
            {
                if(e.key != "Shift" && e.key != "Enter" && e.key != "Control") {
                    
                    if(document.getElementById("weitere-input") === document.activeElement || document.getElementById("lehrer-input") === document.activeElement || document.getElementById("datum-input") === document.activeElement)
                    {
                        console.log('not reading code');
                        return;
                    }
                    code = code + e.key;
                    console.log("code: " + code);
    
                    codeScanned();
                }
            }
        });

        /**
         * prueft, ob Barcode zuende eingegeben ist bzw. keine Eingabe mehr folgt
         * (500 ms keine Eingabe)
         */
        async function codeScanned() {
            var currentCode = code;

            await new Promise(resolve => setTimeout(resolve, 500));
            
            if(currentCode == code) {
                console.log("finished code: " + code);
                reading = false;
                
                sendBarcode(code, 'gegenstand', 'true', 'return', 'gegenstandZurueckgeben', [code]);
            }
        }
    </script>
<?= $this->endSection() ?>