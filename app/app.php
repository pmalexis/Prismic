<?php

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
  $document = $api->query(Predicates::at('document.type', 'articles'));

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
