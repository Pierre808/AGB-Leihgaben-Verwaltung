<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/waiting.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1 id="title">Schülerdaten anzeigen:</h1>

        <div class="warning ">
            <p>
                Um einen Schülerausweis zu scannen wird ein Barcode-reader benötigt, welcher sich im A-Turm Keller befindet.
            </p>
        </div>
        
        <div id="errorDiv">
            
        </div>
        
        <div class="waiting-success-cointainer">
            <div id="loading-box">
                <h2>Jetzt Schülerausweis mit Barcode-reader einscannen</h2>
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
                
                sendBarcode(code, 'schueler', 'true', 'show');
            }
        }
    </script>
<?= $this->endSection() ?>