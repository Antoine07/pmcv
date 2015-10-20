<?php
/*
|--------------------------------------------------------------------------
|   Dependencies
|--------------------------------------------------------------------------
*/

use Doctrine\Common\Inflector\Inflector;

/*
|--------------------------------------------------------------------------
|   security fill
|--------------------------------------------------------------------------
*/

$fillable = [
    'id',
    'title',
    'published_at',
    'status',
    'm_filename',
    'm_size'
];

/*
|--------------------------------------------------------------------------
|   Factoring Model
|--------------------------------------------------------------------------
*/

function __where(array $args, $db, $fillable = [])
{
    $q = ' WHERE 1=1 ';
    if (!empty($args)) {
        array_walk($args, function ($val, $field) use ($db, $fillable, &$q) {
            if (in_array($field, $fillable)) {
                $q .= " AND $field={$db->quote($val)} ";
            } else {
                $q .= " AND $field={$db->quote($val)} ";
            }
        });
    }

    return $q;
}


function __order(array $order, $fillable)
{

    if (empty($order))
        return '';

    $q = '';
    $field = key($order);

    if (in_array($field, $fillable)) {
        $order = strtolower($order[$field]) == 'desc' ? 'DESC' : 'ASC';
        $q .= " ORDER BY $field $order";
    }

    return $q;
}

/**
 * @param $offset
 * @param $limit
 * @return string
 */
function __limit($offset, $limit)
{
    $limit = (int)$limit;

    return " LIMIT $offset, $limit ";
}

/*
|--------------------------------------------------------------------------
|  App Model
|--------------------------------------------------------------------------
*/

/**
 * @param $id
 * @param $table
 * @return PDOStatement
 */
function find_model($id, $table)
{
    global $db;
    $query = sprintf(
        "SELECT * FROM %s WHERE id=%d",
        $table,
        (int)$id
    );

    return $db->query($query); // throw exception if no data
}

/**
 * @param $table
 * @param array $where
 * @param array $order
 * @param int $limit
 * @return PDOStatement
 */
function all_model($table, $where = [], $order = ['id' => 'DESC'], $start = 0, $limit = 10)
{
    global $db, $fillable;

    $query = sprintf(
        "SELECT * FROM %s ",
        $table
    );

    $query .= __where($where, $db, $fillable);
    $query .= __order($where, $fillable);
    $query .= __limit($start, $limit);

    return $db->query($query); // throw exception if no data
}

/**
 * @param $post
 * @return bool
 */
function create_post_model($post)
{
    global $db;

    $datetime = $post['published_at'];
    if (($datetime instanceof DateTime)) {
        $datetime = $datetime->format("Y-m-d h:i:s");
    }

    $query = sprintf(
        "INSERT INTO posts (title, content, status, published_at) VALUES (%s, %s, %s, %s)",
        $db->quote($post['title']),
        $db->quote($post['content']),
        $db->quote($post['status']),
        $db->quote($datetime)
    );

    $db->query($query); // PDOStatement
}

/**
 * @param $id
 * @param $post
 * @return bool
 */
function update_post_model($id, $post)
{
    global $db;

    $datetime = $post['published_at'];
    if (($datetime instanceof DateTime)) {
        $datetime = $datetime->format("Y-m-d h:i:s");
    }

    $query = sprintf(
        "UPDATE posts SET title=%s, content=%s, status=%s, published_at=%s WHERE id=%d",
        $db->quote($post['title']),
        $db->quote($post['content']),
        $db->quote($post['status']),
        $db->quote($datetime),
        (int)$id
    );

    $db->query($query); // PDOStatement
}

/**
 * @param $id
 */
function destroy_model($id, $table)
{
    global $db;
    $query = sprintf(
        "DELETE FROM %s WHERE id=%d",
        $table,
        (int)$id
    );

    $db->query($query); // PDOStatement
}

/**
 * @param $media
 */
function create_media_model($media)
{
    global $db;

    $query = sprintf(
        "INSERT INTO medias (post_id, m_filename, m_size) VALUES (%s, %s, %s)",
        $db->quote($media['post_id']),
        $db->quote($media['filename']),
        $db->quote($media['size'])
    );

    $db->query($query); // PDOStatement
}

/* @todo fillable security close where
 *
 */
/**
 * @param $table1
 * @param $table2
 * @param string $join
 * @param array $where
 * @param array $order
 * @param int $start
 * @param int $limit
 * @return PDOStatement
 */
function join_model($table1, $table2, $join = 'INNER ', $where = [], $order = [], $start = 0, $limit = 10)
{
    global $db, $fillable;

    $foreign_key = Inflector::singularize($table1) . '_id';

    $join = in_array(strtoupper($join), ['INNER', 'LEFT OUTER', 'RIGHT OUTER']) ? strtoupper($join) : 'INNER';

    $prefix = substr($table2, 0, 1);

    $query = sprintf(
        "SELECT t1.*, t2.*, t1.id as id, t2.id as %s_id
         FROM %s as t1
         %s JOIN %s as t2
         ON t1.id = t2.$foreign_key
        ",
        $prefix,
        $table1,
        $join,
        $table2

    );

    $query .= __where($where, $db);
    $query .= __order($order, $fillable);
    $query .= __limit($start, $limit);

    return $db->query($query); // PDOStatement
}

/**
 * @param $offset
 * @param $limit
 * @param string $table
 * @param array $where
 * @param array $order
 * @return PDOStatement
 */
function paginate($offset, $limit, $table = 'posts', $where = [], $order = [])
{
    global $db, $fillable;
    $query = sprintf(
        "SELECT * FROM %s ",
        $table,
        $offset,
        $limit
    );

    $query .= __where($where, $db, $fillable);
    $query .= __order($order, $fillable);
    $query .= __limit($offset, $limit);

    return $db->query($query); // throw exception if no data
}

/**
 * @param $table
 * @param array $where
 * @return null|string
 */
function count_model($table, $where = [])
{
    global $db, $fillable;
    $query = sprintf(
        "SELECT count(*) FROM %s ",
        $table
    );

    $query .= __where($where, $db, $fillable);

    if ($res = $db->query($query)) {
        return $res->fetchColumn();
    }

    return null;
}

/**
 * @param $email
 * @param $password
 * @return bool
 */
function auth_model($email, $password)
{
    global $db;

    $query = sprintf(
        "SELECT password FROM users WHERE email=%s ",
        $db->quote($email)
    );

    $stmt = $db->query($query);

    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $v) {
            return password_verify($password, $v['password']);
        }
    }

    return false;
}

/**
 * @return string
 */
function lastInsertId()
{
    global $db;

    return $db->lastInsertId();
}

/**
 * PDO start transaction
 */
function beginTransaction()
{
    global $db;

    $db->beginTransaction();
}

/**
 * @PDO commit
 */
function commit()
{
    global $db;

    $db->commit();
}

/**
 * PDO rollback
 */
function rollback()
{
    global $db;

    $db->rollback();
}