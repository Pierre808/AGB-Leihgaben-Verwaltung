<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/waiting.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1 id="title">Neuen Barcode zuweisen:</h1>

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
                
                $.ajax({
                    url: 'gegenstandBarcodeBearbeiten',
                    type: 'POST',
                    dataType: "json",
                    data: { 
                        gegenstandId: "<?= esc($gegenstandId) ?>",
                        newId: code
                    },
                    success: function(response) {
                        console.log(response);
                        if(response.status == "ok") {
                            startSuccessAnim(response.redirect);
                        }
                        else {
                            if(response.hasOwnProperty('links'))
                            {
                                //error animation with message and links
                                startErrorAnim(response.error_message, response.links);
                            }
                            else
                            {
                                //error animation with message but no links
                                startErrorAnim(response.error_message, []);
                            }
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }
    </script>
<?= $this->endSection() ?>