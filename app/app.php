<?php

if(!session_id()) {
  session_start();
}

if(empty($_SESSION['lang'])) {
  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  $_SESSION['lang'] = $lang.'-'.$lang;
}

/*
 * This is the main file of the application, including routing and controllers.
 *
 * $app is a Slim application instance, see the framework documentation for more details:
 * http://docs.slimframework.com/
 *
 * The order of the routes matter, as it will define the priority of routes. For that reason we
 * need to keep the more "generic" routes, such as the pages route, at the end of the file.
 *
 * If you decide to change the URLs, make sure to change PrismicLinkResolver in LinkResolver.php
 * as well to make sures links in your site are correctly generated.
 */

use Prismic\Api;
use Prismic\Predicates;

require_once 'includes/http.php';

$apiEndpoint = $WPGLOBAL['app']->getContainer()->get('settings')['prismic.url'];
$repoEndpoint = str_replace("/api", "", $apiEndpoint);
$url = $repoEndpoint . '/app/settings/onboarding/run';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, array("language=php&framework=slim"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result=curl_exec ($ch);
curl_close ($ch);

// Previews
$app->get('/preview', function ($request, $response) use ($app, $prismic) {
  $token = $request->getParam('token');
  $url = $prismic->get_api()->previewSession($token, $prismic->linkResolver, '/');
  setcookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
  return $response->withStatus(302)->withHeader('Location', $url);
});

// Index page
$app->get('/', function ($request, $response) use ($app, $prismic) {
  return $response->withStatus(302)->withHeader('Location', '/tutorial');
});

// Tutorial Page
$app->get('/tutorial', function ($request, $response) use ($app, $prismic) {
  render($app, 'tutorial');
});

// Get page by UID
$app->get('/page/{uid}', function ($request, $response, $args) use ($app, $prismic) {
  // Query the API
  $api = $prismic->get_api();
  $document = $api->getByUID('page', $args['uid']);

  // Render the page
  render($app, 'page', array('document' => $document));
});

$app->get('/articles', function ($request, $response, $args) use ($app, $prismic) {
  // Query the API
  $api = $prismic->get_api();
  $document = $api->query(Predicates::at('document.type', 'articles'), [ 'lang' => $_SESSION['lang'] ]);

  // Render the page
  render($app, 'articles', array('document' => $document));
});

$app->get('/articles/{uid}', function ($request, $response, $args) use ($app, $prismic) {
  // Query the API
  $api = $prismic->get_api();
  $document = $api->getByUID('articles', $args['uid']);

  // Render the page
  render($app, 'article', array('document' => $document));
});

$app->get('/articles_seemore', function ($request, $response) use ($app, $prismic) {
  render($app, 'articles_seemore');
});

//TEST MULTILANG
$app->get('/multilang', function ($request, $response, $args) use ($app, $prismic) {
  // Query the API
  $api = $prismic->get_api();
  $document = $api->query(Predicates::at('document.type', 'multilang'), [ 'lang' => $_SESSION['lang'] ]);

  // Render the page
  render($app, 'multilang', array('document' => $document));
});

$app->get('/multilang/{uid}', function ($request, $response, $args) use ($app, $prismic) {
  // Query the API
  $api = $prismic->get_api();
  /*$documents = $api->query(Predicates::at('document.type', 'multilangrepeat'), [ 'lang' => $_SESSION['lang'] ]);

  $document = null;

  foreach($documents->results as $arr) {
    if($arr->uid == $args['uid']) {
      $document = $arr;
    }
  }*/

  $document = $api->getByUID('multilangrepeat', $args['uid']);

  var_dump($document);

  // Render the page
  //render($app, 'multilangrepeat', array('document' => $document));
});



/**
 * Webhook github
 * auto pull if push on master
 */
$app->post('/github-webhook', function() use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    $ref = $data["ref"] ?? "none";
    $dir = __DIR__."/prod-test/prismic/";
    if ($ref != "refs/heads/master") {
        shell_exec("cd $dir && echo $ref >> git.log 2>&1");
    }
    shell_exec("cd $dir && git pull >> git.log 2>&1");
});

/**
 * Webhook github
 * manual pull
 */
$app->get('/github-webhook', function() use ($app) {
    $dir = __DIR__."/prod-test/prismic/";
    echo "<pre>";
    echo nl2br(shell_exec("cd $dir && git pull 2>&1"));
    echo "<hr>";
    echo nl2br(shell_exec("cd $dir && git log  --pretty=oneline -10"));
    echo "<pre>";
});


/*
1/ [coté serveur] Génération de la clé SSH
https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/

2/ [coté serveur] Enregistrement de la clé pour github
fichier “.ssh/config” :
Host github.com
HostName github.com
User leahpar
IdentityFile /home/urql6ygan4m8/.ssh/id_rsa
IdentitiesOnly yes

3/ [github] Ajout de la clé SSH au compte github
https://github.com/settings/keys
New SSH key > coller la clé publique (fichier id_rsa.pub qu’on vient de générer)

4/ [github] créer le webhook
https://github.com/leonardchalvet/Skeelz/settings/hooks
Add webhook
payload url = http://skeelz.com/github-webhook
content = application/form-urlencoded
secret = pas besoin
event = just the push event
active = yes

5/ Enjoy !
*/

