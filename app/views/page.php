<?php
use Prismic\Dom\RichText;

$document = $WPGLOBAL['document'];
?>

<?php include_once 'header.php'; ?>
    
<div>
  <h1><?= RichText::asText($document->data->title) ?></h1>
  <div>
    <?= RichText::asHtml($document->data->description) ?>
  </div>
  <img src="<?= $document->data->image->url ?>" alt="<?= $document->data->image->alt ?>">
</div>

<?php include_once 'footer.php'; ?>