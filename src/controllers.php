<?php

/*
|--------------------------------------------------------------------------
|   Factoring Controller
|--------------------------------------------------------------------------
*/

function __is_auth()
{
    if (auth_guest())
        redirect('dashboard');
}

function __is_guest()
{
    if (!auth_guest())
        redirect('login', '401 Unauthorized');
}


/*
|--------------------------------------------------------------------------
| Front controllers
|--------------------------------------------------------------------------
*/

function home_controller()
{
    $posts = join_model('posts', 'medias', 'left outer', ['t1.status' => 'published']);

    response('200 Ok');
    include 'views/front/home.php';
}


function show_controller($id)
{
    $post = find_model($id, 'posts');

    response('200 Ok');
    include 'views/front/single.php';
}

/*
|--------------------------------------------------------------------------
| Login dashboard
|--------------------------------------------------------------------------
*/

function login_controller()
{
    __is_auth();

    response('200 Ok');
    include 'views/front/login.php';
}

function post_login_controller()
{

    if (!empty($_POST) && checked_token($_POST['_token'])) {

        __session_start();

        $rules = [
            'email'    => FILTER_SANITIZE_EMAIL,
            'password' => FILTER_SANITIZE_STRING
        ];

        $sanitize = filter_input_array(INPUT_POST, $rules);

        if (empty($sanitize['email'])) {
            $_SESSION['errors']['email'] = trans('required', 'email');
        }
        if (empty($sanitize['password'])) {
            $_SESSION['errors']['password'] = trans('required', 'password');
        }

        if (empty($sanitize['email']) || empty($sanitize['password'])) {
            unset($sanitize['password']);
            $_SESSION['old'] = $sanitize;

            redirect('login');
        }

        if (auth_model($sanitize['email'], $sanitize['password'])) {
            setFlashMessage(trans('success'));
            $_SESSION['secu'] = getEnv('SECU'); // secu dashboard

            redirect('dashboard');
        } else {
            $_SESSION['old'] = $sanitize;
            unset($sanitize['password']);

            redirect('login');
        }
    }

    throw new RuntimeException('418');
}

/*
|--------------------------------------------------------------------------
| CRUD posts
|--------------------------------------------------------------------------
| CRUD resource posts example
|
*/

/**
 * @post index
 */
function index_post_controller($page = null)
{
    __is_guest();

    // paginate
    $total = count_model('posts');
    $num_per_page = getEnv('NUM_PER_PAGE');

    $num_page = ceil($total / $num_per_page);

    $previous = ($page <= 1) ? false : true;
    $next = ($page >= $num_page || $num_page == 1) ? false : true;

    $lastIdPage = 0;

    if (is_null($page)) {
        $posts = paginate(0, $num_per_page, 'posts');
    } else {
        $offset = $num_per_page * ($page - 1);
        $lastIdPage = $page;
        $posts = paginate($offset, $num_per_page, 'posts');
    }

    response('200 Ok');
    include 'views/dashboard/post/index.php';
}

// *todo test transaction PDO
/**
 * @post create
 */
function create_post_controller()
{
    __is_guest();

    response('200 Ok');
    include 'views/dashboard/post/create.php';
}

/**
 * @post store
 */
function store_post_controller()
{
    __is_guest();

    if (!empty($_POST)) {
        if (checked_token($_POST['_token'])) {

            __session_start();

            $_SESSION['old'] = [];
            $_SESSION['errors'] = [];

            $rules = [
                'title'        => FILTER_SANITIZE_STRING,
                'content'      => FILTER_SANITIZE_STRING,
                'status'       => [
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($s) {
                        if (in_array($s, ['published', 'unpublished'])) {
                            return $s;
                        } else {
                            return 'unpublished';
                        }
                    }
                ],
                'published_at' => [
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($checkbox) {
                        if ($checkbox == 'yes') {
                            return new DateTime('now');
                        }
                    }
                ],
            ];

            $sanitize = filter_input_array(INPUT_POST, $rules);

            // test if errors
            if (empty($_POST['title'])) {
                $_SESSION['errors']['title'] = trans('title', 'required');
            }

            if (!empty($_SESSION['errors'])) {

                $_SESSION['old'] = $sanitize;

                redirect('post/create');
            }

            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                try {
                    $dateFile = upload($_FILES['file']);

                    beginTransaction();
                    create_post_model($sanitize);
                    $post_id = lastInsertId();
                    create_media_model(['filename' => $dateFile['filename'], 'post_id' => $post_id, 'size' => $dateFile['size']]);
                    commit();

                    setFlashMessage("success stored");

                    redirect('dashboard');
                } catch (Exception $e) {

                    if ($e instanceof RuntimeException) {
                        $_SESSION['old'] = $sanitize;
                        $_SESSION['errors']['upload'] = $e->getMessage();
                        redirect('post/create');
                    }

                    rollback();

                    $_SESSION['old'] = $sanitize;
                    $_SESSION['errors']['file'] = $e->getMessage();

                    redirect('post/create');
                }
            } else {

                create_post_model($sanitize);

                setFlashMessage(trans('success_post_updated', 'post'));

                redirect('dashboard');
            }
        }
    }

    throw new RuntimeException('418');
}

// todo nothing todo here
/**
 * @post show
 */
function show_post_controller($id)
{
    __is_guest();
}

/**
 * @post edit
 */
function edit_post_controller($id)
{
    __is_guest();

    $post = join_model('posts', 'medias', 'left outer', ['t1.id' => (int)$id]);
    $post = $post->fetch();

    $published_at = ($post['published_at'] != '0000-00-00 00:00:00') ? new DateTime($post['published_at']) : '0000-00-00 00:00:00';
    response('200 Ok');
    include 'views/dashboard/post/update.php';
}

// todo delete image with ajax
/**
 * @post update
 */
function update_post_controller($id)
{
    __is_guest();

    if (!empty($_POST)) {
        if (checked_token($_POST['_token'])) {
            __session_start();

            $_SESSION['old'] = [];
            $_SESSION['errors'] = [];

            $rules = [
                'title'        => FILTER_SANITIZE_STRING,
                'content'      => FILTER_SANITIZE_STRING,
                'status'       => [
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($s) {
                        if (in_array($s, ['published', 'unpublished'])) {
                            return $s;
                        } else {
                            return 'unpublished';
                        }
                    }
                ],
                'published_at' => [
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($checkbox) {
                        if ($checkbox == 'yes') {
                            return new DateTime('now');
                        }
                    }
                ],
            ];

            $sanitize = filter_input_array(INPUT_POST, $rules);

            $id = (int)$id;

            // test if errors
            if (empty($_POST['title'])) {
                $_SESSION['errors']['title'] = 'title is required';
            }

            if (!empty($_SESSION['errors'])) {
                $_SESSION['old'] = $sanitize;

                redirect('post/create'); // exit
            }

            if (!empty($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                try {
                    $dateFile = upload($_FILES['file']);

                    beginTransaction();
                    update_post_model($id, $sanitize);
                    create_media_model(['filename' => $dateFile['filename'], 'post_id' => $id, 'size' => $dateFile['size']]);
                    commit();

                    setFlashMessage("success stored");
                    redirect('dashboard');
                } catch (Exception $e) {

                    if ($e instanceof RuntimeException) {
                        $_SESSION['old'] = $sanitize;
                        $_SESSION['errors']['upload'] = $e->getMessage();
                        redirect('post/create');
                    }

                    rollback();

                    $_SESSION['old'] = $sanitize;
                    $_SESSION['errors']['file'] = $e->getMessage();

                    redirect('post/create');
                }
            } else {
                try {
                    beginTransaction();
                    update_post_model($id, $sanitize);
                    $media_id = (int)$_POST['m_id'];
                    if (!empty($_POST['m_id']) && !empty($_POST['delete_filename'])) {
                        $media = find_model($media_id, 'medias');
                        $m = $media->fetch();
                        destroy_model($media_id, 'medias');
                    }
                    commit();

                    if (!empty($m)) {
                        unlink(getEnv('UPLOAD_DIRECTORY') . '/' . htmlentities($m['m_filename']));
                    }

                    setFlashMessage(trans('success_updated_post', $sanitize['title']));
                    redirect('dashboard');
                } catch (Exception $e) {
                    rollback();

                    $_SESSION['old'] = $sanitize;
                    $_SESSION['errors']['file'] = $e->getMessage();

                    redirect('post/create');
                }

                throw new RuntimeException('418');
            }
        }
    }
}

function destroy_post_controller($id)
{
    __is_guest();

    $id = (int)$id;

    if ($id == 0) {
        throw new RuntimeException('418');
    }

    destroy_model($id, 'posts');

    setFlashMessage(trans('success_deleted_post', 'post'));

    redirect('dashboard');
}
