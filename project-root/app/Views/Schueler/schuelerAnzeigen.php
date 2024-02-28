<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/spancolors.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Schüler: <?= esc($schueler['name']) ?></h1>

        <a href="<?= base_url('add-gegenstand-to-leihgabe/' . esc($schueler['schueler_id'])) ?>"><button class="bottom-margin" id="smallInputBtn">Diesem Schüler etwas verleihen</button></a>

        <div class="container-list">
            <div class="container">
                <p><span  class="big">Name: </span><?= esc($schueler['name']) ?></p>
                <p><span  class="big">Mail: </span><?php 
                    if($schueler['mail'] == "")
                    {
                        echo("/");
                    }
                    else
                    {
                        echo($schueler['mail']);
                    }
                ?></p>
                <p><span  class="big">Schueler-Id: </span><?= esc($schueler['schueler_id']) ?></p>
                <?php 
                    if(esc($isTemp))
                    {
                        echo('<p><span class="red">Temporär registriert. Bitte Schülerausweis hinterlegen</span> (siehe unten: Neuen Schülerausweis zuweisen)</p>');
                    }
                ?>
            </div>
            
            <div class=container>
                <h2>Bearbeiten</h2>
                <div class="pList">
                    <p class="big">Name:</p>
                    <?= form_open('show-schueler/'.esc($schueler['schueler_id'])) ?>
                        <?= form_input('name', esc($schueler['name']), ['placeholder'=>esc($schueler['name']), 'id'=>'inputNoMargin'], 'text') ?>
                        <input id="smallInputBtn" type="submit" value="Speichern"/>
                    <?= form_close() ?>

                    <br>
                    
                    <p class="big">Schueler-Id:</p>
                    <button onclick="location.href='<?= base_url('edit-schueler/' . esc($schueler['schueler_id'])) ?>'">Neuen Schülerausweis zuweisen</button>
                </div>
            </div>

            <div class="container">
                <h2>Offene Leihgaben</h2>

                <?php
                if(esc($aktiv) != null)
                {
                ?>
                    <div>
                        <?php
                        for($i = 0; $i < count(esc($aktiv)); $i++)
                        {
                            $color = "row-light";

                            if($i % 2 == 0)
                            {
                                $color = "row-dark";
                            }
                        ?>
                        <a class="width100" href="<?= base_url("show-leihgabe/" . esc($aktiv[$i]['id'])) ?>">
                        <div class="container-row <?= $color ?>">
                            <p class="big">Bezeichnung: <span class="standart left-margin"><?= esc($aktiv[$i]['gegenstand_bezeichnung']) ?></span></p>
                            <p class="big">Gegenstand-Id: <span class="standart left-margin"><?= esc($aktiv[$i]['gegenstand_id']) ?></span></p>
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
                        <p>Keine offenen Verleihungen</p>
                    </div>
                    <br>
                <?php
                }
                ?>
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
                            <p class="big">Bezeichnung: <span class="standart left-margin"><?= esc($verlauf[$i]['gegenstand_bezeichnung']) ?></span></p>
                            <p class="big">Gegenstand-Id: <span class="standart left-margin"><?= esc($verlauf[$i]['gegenstand_id']) ?></span></p>
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