<?php 
	
	use Prismic\Api;
	use Prismic\LinkResolver;
	use Prismic\Predicates;

	require_once '/config.php';

	$document = $WPGLOBAL['document'];
	$n = isset($_GET['n']) ? $_GET['n'] : -1;
	
	if($n > 0) {

		$i = 0;
		$n = $_GET['n'] * 9;
		foreach ($document->results as $el) { 
			if($i > $n && $i < ($n+9) ) { ?>
				<div class="el">
					<div class="cover" 
					     style="background-image: url(<?= $el->banner_image->url ?>);"></div>
					<div class="text">
						<h4>
							<?= RichText::asText($el->banner_title); ?>
						</h4>
						<p>
							<?= RichText::asText($el->banner_text); ?>
						</p>
						<a href="/newz/<?php echo($uid); ?>">
							<?= RichText::asText($document->content_cta_text); ?>
						</a>
						<div class="container-share">
							<a href="<?= $el->banner_link_facebook->url; ?>">
								<img src="img/newz/Facebook-color.svg" alt="">
							</a>
							<a href="<?= $el->banner_link_instagram->url; ?>">
								<img src="img/newz/Instagram-color.svg" alt="">
							</a>
							<a href="<?= $el->banner_link_linkedin->url; ?>">
								<img src="img/newz/LinkedIn-color.svg" alt="">
							</a>
							<a href="<?= $el->banner_link_twitter->url; ?>">
								<img src="img/newz/Twitter-color.svg" alt="">
							</a>
						</div>
					</div>
				</div>
		<?php
			} 
			$i++;
		}
	}
?>