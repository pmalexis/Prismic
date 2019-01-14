<?php 
use Prismic\Dom\RichText;
use Prismic\Document;

$document = $WPGLOBAL['document']->results[0]->data;

$title = "Multilang";
include_once 'header.php'; 
//var_dump($document->results); 
?>

<main class="multilang">

	<h1><?= RichText::asText($document->title) ?></h1>

	<p><?= RichText::asText($document->text) ?></p>

</main>

<?php 
	include_once 'footer.php'; 
	$_SESSION['lang'] = 'de-de';
?>