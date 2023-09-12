<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/containers.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1>Alle Leihgaben:</h1>

        <div class="filter-container">
            <?= form_open('all-leihgabe/filter') ?>
                <div class="filter-search">
                        <div class="inputs">
                            <div class="input">
                                <img src="<?= base_url('imgs/suche.png') ?>"/>
                                <?= form_input('schueler', set_value('schueler'), ['placeholder'=>'Schülername'], 'text') ?>
                            </div>
                            <div class="input">
                                <img src="<?= base_url('imgs/suche.png') ?>"/>
                                <?= form_input('lehrer', set_value('lehrer'), ['placeholder'=>'Lehrername'], 'text') ?>
                            </div>  
                            <div class="input" id="last">
                                <img src="<?= base_url('imgs/suche.png') ?>"/>
                                <?= form_input('gegenstand', set_value('gegenstand'), ['placeholder'=>'Gegenstandsbezeichnung'], 'text') ?>
                            </div>
                        </div>

                        <input type="submit" value="Suchen"/>
                </div>
                <div class="filter-checkboxes">
                    <div class="checkbox">
                        <label class="checkbox-container">
                            Offene Leihgaben
                            <input type="checkbox" id="active" name="active" value="yes" <?php
                                if(esc($post) == true)
                                {
                                    if(esc($active_checked) == true)
                                    {
                                        echo('checked');
                                    }
                                }
                                else{
                                    echo('checked');
                                }
                            ?>>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label class="checkbox-container">
                            Überfällige Leihgaben
                            <input type="checkbox" id="ueberfaellig" value="yes" name="ueberfaellig" <?php
                                if(esc($post) == true)
                                {
                                    if(esc($ueberfaellig_checked) == true)
                                    {
                                        echo('checked');
                                    }
                                }
                            ?>>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            <?= form_close() ?>
        </div>
        
        <div class="container-list">
            <?php
                for($i = 0; $i < count(esc($active)); $i++)
                { 
                    $infos = esc($active[$i])
                    ?>

                    <a class="block width100" href="<?= base_url("show-leihgabe/" . $infos['id']) ?>">
                    <div class="container">
                        <div class="pList">
                            <p class="big">Name Schüler: <span class="standart left-margin"><?= $infos['schueler_name'] ?></span></p>
                            <p class="big">Gegenstand: <span class="standart left-margin"><?= $infos['gegenstand_bezeichnung'] ?></span></p>
                            <p class="big">Ausgeliehen am: <span class="standart left-margin"><?= $infos['formated_datum_start'] ?></span></p>
                            <?php 
                                if($infos['aktiv'] == 0)
                                { ?>
                                    <p class="big">Zurückgegeben: <span class="standart left-margin"><?= $infos['formated_datum_ende'] ?></span></p>
                                <?php }
                            ?>
                            <p class="big">Ausgeliehen bei: <span class="standart left-margin"><?= $infos['lehrer'] ?></span></p>
                        </div>
                    </div>
                    </a>
                <?php }
            ?>
        </div>
<?= $this->endSection() ?>