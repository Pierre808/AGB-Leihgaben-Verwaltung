<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/spancolors.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Infos zu der Leihgabe:</h1>

        <div class="container-list">
            <a class="width100" href="<?= base_url('show-schueler/' . esc($schueler['schueler_id'])) ?>">
            <div class="container">
                <h2>Schüler</h2>
                <div class="pList">
                    <p class="big">Name: <span class="standart left-margin"><?= esc($schueler['name']) ?></span></p>
                    <p class="big">Mail: <span class="standart left-margin"><?= esc($schueler['mail']) ?></span></p>
                    <p class="big">Schüler-Id: <span class="standart left-margin"><?= esc($schueler['schueler_id']) ?></span></p>
                    <p class="big">Weitere: <span class="standart left-margin"><?= esc($leihgabe['weitere']) ?></span></p>
                </div>
            </div>
            </a>

            <a class="width100" href="<?= base_url('show-gegenstand/' . esc($gegenstand['gegenstand_id'])) ?>">
            <div class="container">
                <h2>Gegenstand</h2>
                <div class="pList">
                    <p class="big">Bezeichnung: <span class="standart left-margin"><?= esc($gegenstand['bezeichnung']) ?></span></p>
                    <p class="big">Id: <span class="standart left-margin"><?= esc($gegenstand['gegenstand_id']) ?></span></p>
                </div>
            </div>
            </a>

            <div class="container">
                <h2>Leihgabe</h2>
                <div class="pList">
                    <p class="big">Ausgeliehen am: <span class="standart left-margin"><?= esc($leihgabe['formated_datum_start']) ?></span></p>
                    <p class="big">Abgabefrist: <span class="standart left-margin"><?= esc($leihgabe['formated_datum_ende']) ?></span></p>
                    <p class="big">Zurückgegeben: <span class="standart left-margin <?= esc($leihgabe['zurueck_color']) ?>"><?= esc($leihgabe['zurueck_string']) ?></span></p>
                    <?php 
                        if(esc($leihgabe['aktiv']) == 0)
                        { ?>
                            <p class="big">Zurückgegeben am: <span class="standart left-margin"><?= esc($leihgabe['formated_datum_rueckgabe']) ?></span></p>
                        <?php }
                    ?>
                    <p class="big">Ausgeliehen bei: <span class="standart left-margin"><?= esc($leihgabe['lehrer']) ?></span></p>
                </div>
            </div>
        </div>

<?= $this->endSection() ?>