<?php 
use Prismic\Dom\RichText;
use Prismic\Document;

$document = $WPGLOBAL['document']->data;

$title = "Multilang 2";
include_once 'header.php'; 
//var_dump($document->results); 
?>

<main class="multilang">

	<h1><?= RichText::asText($document->titre) ?></h1>

	<p><?= RichText::asText($document->texte) ?></p>

</main>

<?php include_once 'footer.php'; ?>