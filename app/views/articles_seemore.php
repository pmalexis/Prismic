<?php 
	
	use Prismic\Api;
	use Prismic\LinkResolver;
	use Prismic\Predicates;

	require_once '../config.php';

	$n = isset($_GET['n']) ? $_GET['n'] : -1;
	
	if($n > 0) {
	 
		$api = Api::get(PRISMIC_URL);
		$document = $api->query(Predicates::at('document.type', 'articles'));

		$i = 0;
		$n = $_GET['n'] * 10;
		foreach ($document->results as $arr) { 
			if($i > $n && $i < ($n+10) ) { ?>
				<div class="card">
					<h3><?php echo $arr->data->body[0]->primary->titre_de_l_article[0]->text; ?></h3>
					<p><?php echo $arr->data->body[0]->primary->texte_de_l_article[0]->text; ?></p>
					<a href="articles/<?php echo $arr->uid; ?>">Lire l'article</a>
				</div>
		<?php
			} 
			$i++;
		}
	}
	else echo '<h2>Erreur, essayer plus tard !</h2>';
?>