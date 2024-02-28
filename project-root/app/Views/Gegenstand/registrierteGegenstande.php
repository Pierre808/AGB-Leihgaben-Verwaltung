<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Registrierte Gegenstände:</h1>

        <div class="container-list">
            <?php
                for($i = 0; $i < count(esc($gegenstaende)); $i++)
                { 
                    $infos = esc($gegenstaende[$i])
                    ?>

                    <a class="block width100" href="<?= base_url("show-gegenstand/" . $infos['gegenstand_id']) ?>">
                    <div class="container">
                        <div class="pList">
                            <p class="big">Gegenstand Bezeichnung: <span class="standart left-margin"><?= $infos['bezeichnung'] ?></span></p>
                            <p class="big">Gegenstand Id: <span class="standart left-margin"><?= $infos['gegenstand_id'] ?></span></p>
                        </div>
                    </div>
                    </a>
                <?php }
            ?>
        </div>
<?= $this->endSection() ?>