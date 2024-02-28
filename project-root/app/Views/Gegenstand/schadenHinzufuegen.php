<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Schaden Hinzufügen: </h1>

        <p class="subtitle">Vorhandene Schäden</p>
        <?php
        if(count(esc($schaeden)) != null)
        {
        ?>
        <div class="container-list">
            <?php
            for($i = 0; $i < count(esc($schaeden)); $i++)
            { ?>
                <div class="text-container">
                    <p><?= esc($schaeden[$i]['bezeichnungUpper']) ?></p>
                    <div class="round-button">
                        <button><a href="<?= base_url('schaden-entfernen/' . esc($gegenstandId . '/' . esc($schaeden[$i]['bezeichnung'])))?>">—</a></button>
                    </div>
                </div>
            <?php
            } ?>
        </div>        
        <?php 
        } 
        else
        { ?>
            <div class="warning warning-green" id="warning100">
                <p>Keine Schäden vorhanden</p>
            </div>
        <?php
        }?>

        
        <p class="subtitle">Weitere Schäden</p>
        <?php
        if(count(esc($restlicheSchaeden)) != null)
        {
        ?>
        <div class="container-list">
            <?php
            for($i = 0; $i < count(esc($restlicheSchaeden)); $i++)
            { ?>
                <div class="text-container">
                    <p><?= esc($restlicheSchaeden[$i]['bezeichnungUpper']) ?></p>
                    <div class="round-button">
                        <button><a href="<?= base_url('schaden-hinzufuegen/' . esc($gegenstandId . '/' . esc($restlicheSchaeden[$i]['bezeichnung'])))?>">+</a></button>
                    </div>
                </div>
            <?php
            } ?>
        </div>  
        <?php
        }
        else
        { ?>
            <div class="warning warning-green" id="warning100">
                <p>Keine weiteren Schäden vorhanden</p>
            </div>
        <?php } ?>  
    </div>
<?= $this->endSection() ?>