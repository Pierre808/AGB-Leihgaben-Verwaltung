<?= $this->extend("layouts/default") ?>

<?= $this->section("title")?> | <?= esc($page_title) ?> <?= $this->endSection() ?>


<?= $this->section("headerLinks")?> 
<link rel="stylesheet" href="<?= base_url('css/waiting.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/warning.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/buttons.css') ?>">
<?= $this->endSection() ?>


<?= $this->section("content") ?>
    <div id="main">
        <h1 id="title">Leihgabe erstellen:</h1>

        <div class="buttonlist-vertical">
            <button onclick="location.href='<?= base_url('add-leihgabe/') ?>'" class="button100 buttonhundred">Schüler mit Schülerausweis einscannen</button>
            
            <h3>oder: </h3>

            <button onclick="location.href='<?= base_url('add-temp-schueler/') ?>'" class="button100 buttonhundred">Schüler temporär ohne Schülerausweis registrieren</button>
        </div>
    </div>
<?= $this->endSection() ?>