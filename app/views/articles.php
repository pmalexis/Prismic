<?php 
use Prismic\Dom\RichText;

$document = $WPGLOBAL['document'];
$title = "Articles";
include_once 'header.php'; 
//var_dump($document->results); 
?>

<main class="articles">

	<div class="container-search">
		<div class="icn">
            <img src="images/loupe.svg">
        </div>
        <input id="liveSearch" type="text" placeholder="Rechercher">
	</div>

	<div class="container-card">

	<?php $i = 0;
		foreach ($document->results as $arr) { 
			if($i < 10) { ?>
				<div class="card">
					<h3><?= RichText::asText($arr->data->body[0]->primary->titre_de_l_article) ?></h3>
					<p><?php echo $arr->data->body[0]->primary->texte_de_l_article[0]->text; ?></p>
					<a href="articles/<?php echo $arr->uid; ?>">Lire l'article</a>
				</div>
	<?php
			} 
			$i++;
		}
	?>
	</div>

	<div class="container-seemore">
		<button data-seemore="1">SEE MORE</button>
	</div>

</main>

<script type="text/javascript">
	$(document).ready(function(){
        
        $("#liveSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".container-card .card h3").filter(function() {
                $(this).parent().toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('.container-seemore button').click(function(){
        	request(readData);
        })

        // AJAX
        function getXMLHttpRequest() { 
		    let objXMLHttp = null;
		    if (window.XMLHttpRequest) {
		        objXMLHttp = new XMLHttpRequest();
		    }
		    else if (window.ActiveXObject) {
		        objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    return objXMLHttp;
		}

        function request(callback) {
			let xhr = getXMLHttpRequest();
			
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					callback(xhr.responseText);
				}
			};

			let n = parseInt($('.container-seemore button').attr('data-seemore'));
			$('.container-seemore button').attr('data-seemore', n+1);

			xhr.open("GET", "articles_seemore?n=" + n, true);
			xhr.send(null);
		}

		function readData(sData) {
			//alert(sData);
			$('.container-card').append(sData);
		}
    });
</script>

<?php include_once 'footer.php'; ?>