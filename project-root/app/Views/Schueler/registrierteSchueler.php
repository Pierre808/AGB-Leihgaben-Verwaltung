<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Registrierte Schueler:</h1>
        
        <div class="container-list">
            <?php
                for($i = 0; $i < count(esc($schueler)); $i++)
                { 
                    $infos = esc($schueler[$i])
                    ?>

                    <a class="block width100" href="<?= base_url("show-schueler/" . $infos['schueler_id']) ?>">
                    <div class="container">
                        <div class="pList">
                            <p class="big">Name: <span class="standart left-margin"><?= $infos['name'] ?></span></p>
                            <p class="big">E-mail: <span class="standart left-margin"><?php 
                                if($infos['mail'] == "")
                                {
                                    echo("/");
                                }
                                else
                                {
                                    echo($infos['mail']);
                                }
                            ?></span></p>
                            <p class="big">Schüler Id: <span class="standart left-margin"><?= $infos['schueler_id'] ?></span></p>
                        </div>
                    </div>
                    </a>
                <?php }
            ?>
        </div>
<?= $this->endSection() ?>