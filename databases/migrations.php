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

/**
 * @create table posts
 */
$count = $db->exec("
  CREATE TABLE posts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(100),
    user_id INT UNSIGNED,
    excerpt TEXT,
    content TEXT,
    status ENUM('published', 'unpublished') NOT NULL DEFAULT 'unpublished',
    published_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT posts_users_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
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
    CONSTRAINT medias_posts_post_id_foreign FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

/**
 * @create table categories
 */
$count = $db->exec("
  CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(100),
    PRIMARY KEY (id)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

/**
 * @create table category_post (abc order)
 */
$count = $db->exec("
  CREATE TABLE category_post (
  post_id INT UNSIGNED,
  category_id INT UNSIGNED,
  CONSTRAINT category_post_posts_post_id FOREIGN KEY(post_id) REFERENCES posts(id) ON DELETE CASCADE,
  CONSTRAINT category_post_categories_post_id FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE CASCADE,
  CONSTRAINT un_post_id_category_id UNIQUE KEY (post_id, category_id )
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

echo "number lines fixtures: $count \n";