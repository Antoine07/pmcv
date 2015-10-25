#!/usr/bin/php
<?php
/**
 * @fixtures
 */

require_once __DIR__ . '/../bootstrap/build.php';;

if (is_null($db)) {
    die('no database connexion');
}

$date = new DateTime(null, new DateTimeZone('Europe/Paris'));
$now = $date->format('Y-m-d h:i:s');

$count = $db->exec("
  INSERT INTO users (email,password, status)
  VALUES
 ('tony@tony.fr', '" . password_hash('tony', PASSWORD_BCRYPT, ['cost' => 12, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)]) . "', 'administrator')
  ") or die('fixtures problem');

$count = $db->exec("
  INSERT INTO posts (title, content, excerpt, status, published_at, user_id)
  VALUES
  ('php7',  'blablabla', 'blablabla','published','$now',1 ),
  ('mysql', 'blablabla', 'blablabla', 'published','$now',1),
  ('MongoDB', 'blablabla','blablabla', 'published','$now',1),
  ('Docker', 'blablabla','blablabla', 'published','$now',1)
  ") or die('fixtures problem');

$count = $db->exec("
  INSERT INTO categories (title)
  VALUES
  ('Techno back' ),
  ('Techno Front' ),
  ('Server' )
  ") or die('fixtures problem');

$count = $db->exec("
  INSERT INTO category_post (post_id, category_id)
  VALUES
  (1,1 ),
  (1,2 ),
  (1,3 ),
  (2,1),
  (3,1)
  ") or die('fixtures problem');

echo "number lines fixtures: $count \n";