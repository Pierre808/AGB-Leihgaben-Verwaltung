<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/buttons.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/waiting.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Schnellzugriff:</h1>
        <div class="buttonlist">
            <button onclick="location.href='<?= base_url('select-method') ?>'">Gegenstand verleihen</button>
            <button onclick="location.href='<?= base_url('gegenstand-zurueckgeben') ?>'">Gegenstand zurückgeben</button>
            <button onclick="location.href='<?= base_url('schuelerdaten-anzeigen') ?>'">Schülerdaten anzeigen</button>
        </div>

        <h1>Barcode scannen</h1>
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

        <h1>Überfällige Leihgaben:</h1>
        <div class="container-list">
            <?php 
            if(count(esc($leihgaben)) != 0)
            {
                for($i = 0; $i < count(esc($leihgaben)); $i++)
                { ?>
                    <a class="block width100" href="<?= base_url('show-leihgabe/' . esc($leihgaben[$i]['id'])) ?>">
                        <div class="container">
                            <p><span  class="big">Name Schüler: </span><?= esc($leihgaben[$i]['schueler_name']) ?></p>
                            <p><span  class="big">Gegenstand: </span><?= esc($leihgaben[$i]['gegenstand_bezeichnung']) ?></p>
                            <p><span  class="big">Überfällig seit: </span><?= esc($leihgaben[$i]['formated_datum_ende']) ?></p>
                        </div>
                    </a>
                <?php }
            }
            else
            { ?>
                <div class="warning warning-green">
                    <p>
                        Keine überfälligen Leihgaben.
                    </p>
                </div>
            <?php }
            ?>
        </div>
    </div>

    <script>
        let code = '';
        document.addEventListener("keydown", e => {
            if(e.key != "Shift" && e.key != "Enter" && e.key != "Control") {
                code = code + e.key;
                console.log("code: " + code);

                codeScanned();
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
                
                sendBarcode(code, '', false, "home");

                code = "";
            }
        }

    </script>
<?= $this->endSection() ?>