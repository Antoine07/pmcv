#!/usr/bin/php
<?php
/**
 * @fixtures
 */
require_once __DIR__ . '/../bootstrap/build.php';

if (is_null($db)) {
    die('no database connexion');
}

/**
 * @create table posts
 */
$count = $db->exec("
  CREATE TABLE posts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(100),
    excerpt TEXT,
    content TEXT,
    status ENUM('published', 'unpublished') NOT NULL DEFAULT 'unpublished',
    published_at DATETIME NOT NULL,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

/**
 * @create table medias
 */
$count = $db->exec("
  CREATE TABLE medias (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    post_id int(10) unsigned DEFAULT NULL,
    m_filename VARCHAR(100),
    m_size INT NOT NULL,
    PRIMARY KEY (id),
    KEY posts_post_id_foreign (post_id),
    CONSTRAINT posts_post_id_foreign FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

/**
 * @create table users
 */

$count = $db->exec("
  CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(100),
    password VARCHAR(100),
    status ENUM('administrator', 'visitor')
    NOT NULL DEFAULT 'visitor',
    PRIMARY KEY (id),
    CONSTRAINT un_email UNIQUE (email)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

echo "number lines fixtures: $count \n";
