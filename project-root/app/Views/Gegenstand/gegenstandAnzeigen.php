<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Gegenstand: <?= esc($gegenstand['bezeichnung']) ?></h1>

        <div class="container-list">
            <div class="container">
                <p><span  class="big">Bezeichnung: </span><?= esc($gegenstand['bezeichnung']) ?></p>
                <p><span  class="big">Gegenstand-Id: </span><?= esc($gegenstand['gegenstand_id']) ?></p>
            </div>

            <?php 
                if(esc($active) != null)
                {
                    echo("<a class='width100' href='" . base_url("show-leihgabe/" . esc($active['id'])) . "'>");
                }
            ?>
            <div class="container">
                <p class="big">Aktuell Ausgeliehen:</p>
                <p><?php 
                    if(esc($active) == null)
                    {
                        echo("Nein");
                    }
                    else
                    {
                        echo("Ja");
                    }
                ?></p>
            </div>
            <?php 
                if(esc($active) != null)
                {
                    echo("</a>");
                }
            ?>

            <div class=container>
                <h2>Bearbeiten</h2>
                <div class="pList">
                    <p class="big">Bezeichnung:</p>
                    <?= form_open('show-gegenstand/'.esc($gegenstand['gegenstand_id'])) ?>
                        <?= form_input('bezeichnung', esc($gegenstand['bezeichnung']), ['placeholder'=>esc($gegenstand['bezeichnung']), 'id'=>'inputNoMargin'], 'text') ?>
                        <input id="smallInputBtn" type="submit" value="Speichern"/>
                    <?= form_close() ?>

                    <br>
                    
                    <p class="big">Barcode / Gegenstand-Id:</p>
                    <button onclick="location.href='<?= base_url('edit-gegenstand/' . esc($gegenstand['gegenstand_id'])) ?>'">Neuen Barcode zuweisen</button>
                </div>
            </div>

            <div class="container">
                <h2>Schäden</h2>
                
                <?php
                if(count(esc($schaeden)) != 0)
                {
                ?>
                    <div class="pList">
                        <?php
                        for($i = 0; $i < count(esc($schaeden)); $i++)
                        {?>
                            <p>- <?= esc($schaeden[$i]['bezeichnung']) ?></p>
                        <?php 
                        } ?>
                    </div>
                <?php
                }
                else
                {
                ?>
                    <div class="warning warning-green">
                        <p>Keine Schäden vorhanden</p>
                    </div>
                <?php
                }
                ?>

                <br>

                <button onclick="location.href='<?= base_url('schaden-hinzufuegen/' . esc($gegenstand['gegenstand_id'])) ?>'">Schaden hinzufügen</button>
            </div>

            <div class="container">
                <h2>Verlauf</h2>

                <?php
                if(esc($verlauf) != null)
                {
                ?>
                    <div>
                        <?php
                        for($i = 0; $i < count(esc($verlauf)); $i++)
                        {
                            $color = "row-light";

                            if($i % 2 == 0)
                            {
                                $color = "row-dark";
                            }
                        ?>
                        <a class="width100" href="<?= base_url("show-leihgabe/" . esc($verlauf[$i]['id'])) ?>">
                        <div class="container-row <?= $color ?>">
                            <p class="big">Name: <span class="standart left-margin"><?= esc($verlauf[$i]['schueler_name']) ?></span></p>
                            <p class="big">Schüler-Id: <span class="standart left-margin"><?= esc($verlauf[$i]['schueler_id']) ?></span></p>
                            <p class="big">Von: <span class="standart left-margin"><?= esc($verlauf[$i]['formated_datum_start']) ?></span></p>
                            <p class="big">Bis: <span class="standart left-margin"><?= esc($verlauf[$i]['formated_datum_ende']) ?></span></p>
                        </div>
                        </a>
                        <?php 
                        }
                        ?>
                    </div>
                <?php
                }
                else
                {
                ?>
                    <div class="warning warning-green" id="warning100">
                        <p>Bisher keine Verleihungen</p>
                    </div>
                    <br>
                <?php
                }
                ?>
            </div>
        </div>
<?= $this->endSection() ?>