<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/waiting.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1 id="title">Leihgabe erstellen:</h1>

        <?php
            if(esc($lastGegenstand) != null)
            { ?>
                <div class="warning warning-green" id="warning100">
                    <p class="big">Letzter gescannter Gegenstand: <span class="standart left-margin"><?= $lastGegenstand['bezeichnung'] ?></span></p>
                </div>    
            <?php }
        ?>

        <br>
        <br>

        <!--<div class="warning ">
            <p>
                Um einen Gegenstand einscannen zu können wird ebebfalls ein Barcode-reader benötigt.
            </p>
        </div>-->

        <?= form_open() ?>
            <label for="id"><b>Schüler ID</b></label>
            <?php 
                $data = [
                    'name'      => 'id',
                    'value'     => esc($schuelerId),
                    'disabled'     => 'true',
                ];
                echo form_input($data);
            ?>

            <label for="name"><b>Name</b></label>
            <?php 
                $data = [
                    'name'      => 'name',
                    'value'     => esc($schuelerName),
                    'disabled'     => 'true',
                ];
                echo form_input($data);
            ?>
            
            <label for="mail"><b>Mail</b></label>
            <?php 
                $data = [
                    'name'      => 'mail',
                    'value'     => esc($schuelerMail),
                    'disabled'     => 'true',
                ];
                echo form_input($data);
            ?>

            <div class="input50Div">
                <div>    
                    <label for="weitere"><b>Weitere Schüler</b> (optional)</label>
                    <?= form_input('weitere', set_value('weitere'), ['placeholder'=>'Weitere Schüler, die an der Leihgabe beteiligt sind', 'id' => 'weitere-input'], 'text') ?>
                </div>
                <div>
                    <label for="weitere"><b>Lehrer</b> (optional)</label>
                    <?= form_input('lehrer', set_value('lehrer'), ['placeholder'=>'Lehrer, der den Gegenstand verleiht', 'id' => 'lehrer-input'], 'text') ?>
                </div>
            </div>

            <label for="datum-ende"><b>Ende der Leihgabe</b></label>
            <br>
            <?= form_input('datum-ende-aktiv', set_value('datum-ende-aktiv'), ['placeholder'=>'', 'id' => 'datum-checkbox'], 'checkbox') ?>
            <input type="date" id="datum-input" name="datum-ende"
            placeholder="dd-mm-yyyy"
            value="<?= date('Y-m-d', strtotime(date('Y-m-d') . " + 1 day")) ?>"
            min="<?= date('Y-m-d') ?>"
            disabled="true">
        <?= form_close() ?>
        
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
        const someCheckbox = document.getElementById('datum-checkbox');

        someCheckbox.addEventListener('change', e => {
        if(e.target.checked === true) {
            document.getElementById("datum-input").disabled = false;
        }
        if(e.target.checked === false) {
            document.getElementById("datum-input").disabled = true;
        }
        });

        const dateInput = document.getElementById('datum-input');

        dateInput.addEventListener('change', e => {
            console.log('blur');
            e.target.blur();
        });

        
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
                
                let datumEnde = null;
                if(document.getElementById('datum-checkbox').checked == true)
                {
                    datumEnde = document.getElementById("datum-input").value;
                }
                
                $.ajax({
                    url: 'gegenstandZuLeihgabeHinzufuegen',
                    type: 'POST',
                    dataType: "json",
                    data: { 
                        schuelerId: "<?= esc($schuelerId) ?>",
                        gegenstandId: code,
                        weitere: document.getElementById("weitere-input").value,
                        lehrer: document.getElementById("lehrer-input").value,
                        datumEnde: datumEnde
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