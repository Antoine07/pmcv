<?php
/**
 * @description router app
 * @param $uri
 */
function router($uri)
{
    $uri = explode('?', $uri);
    $uri = ltrim($uri[0], '/');

    $page = (!empty($_GET['page'])) ? $_GET['page'] : null;
    $id = (!empty($_GET['id'])) ? $_GET['id'] : null;

    $method = strtolower($_SERVER["REQUEST_METHOD"]);

    /**
     * @method POST
     */
    if ($method == 'post') {
        switch ($uri) {

            case 'login':
                post_login_controller();
                break;

            case 'post/store':
                store_post_controller();
                break;

            case ("post/$id" && isset($_POST['_method']) && $_POST['_method'] == 'PUT'):

                update_post_controller($id);
                break;

            case ("post/$id" && isset($_POST['_method']) && $_POST['_method'] == 'DELETE'):

                destroy_post_controller($id);
                break;

            default:
                throw new RuntimeException('routing error');
        }
    }

    /**
     * @method GET
     */
    if ($method == 'get') {
        switch ($uri) {

            case '':
                home_controller();
                break;

            case "single/$id":
                show_controller($id);
                break;

            case "category/$id":
                show_post_by_category_controller($id);
                break;

            case 'login':
                login_controller();
                break;

            case "post/$id/edit":
                edit_post_controller($id);
                break;

            case (preg_match('/dashboard/', $uri)) ? true : false :
                index_post_controller($page);
                break;

            case 'post/create':

                create_post_controller();
                break;

            case 'logout':
                auth_logout();
                redirect('/');
                break;

            default:
                throw new RuntimeException('routing error');
        }
    }
}
