<?php 
use Prismic\Dom\RichText;
$document = $WPGLOBAL['document']; ?>

<?php include_once 'header.php'; ?>
    
<div class="article">
  <h1><?= RichText::asHtml($document->data->body[0]->primary->titre_de_l_article) ?></h1>
  <p><?= RichText::asHtml($document->data->body[0]->primary->texte_de_l_article) ?></p>

  <?php foreach ($document->data->body[0]->items as $arr) { ?>
  		<div class="el">
  			<img src="<?php echo $arr->illustration_article->url; ?>">
  			<p><?php echo $arr->texte_illustration_article[0]->text; ?></p>
  		</div>
  <?php } ?>

</div>

<?php include_once 'footer.php'; ?>