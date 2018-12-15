<?php $document = $WPGLOBAL['document']; ?>

<?php include_once 'header.php'; ?>
    
<div class="article">
  <h1><?php echo $document->data->body[0]->primary->titre_de_l_article[0]->text; ?></h1>
  <p><?php echo $document->data->body[0]->primary->texte_de_l_article[0]->text; ?></p>

  <?php foreach ($document->data->body[0]->items as $arr) { ?>
  		<div class="el">
  			<img src="<?php echo $arr->illustration_article->url; ?>">
  			<p><?php echo $arr->texte_illustration_article[0]->text; ?></p>
  		</div>
  <?php } ?>

</div>

<?php include_once 'footer.php'; ?>