<?php
/*
*
 *  --------- Helpers function App
*/

/*
|--------------------------------------------------------------------------
| env
|--------------------------------------------------------------------------
*/

if (!function_exists('env')) {

    /**
     * @param $name
     * @param $default
     * @return mixed
     */
    function env($name, $default = '')
    {
        if (!empty($default)) return $default;

        if (getEnv($name)) {
            return getEnv($name);
        }

        throw new RuntimeException("$name constant no defined");
    }
}


/*
|--------------------------------------------------------------------------
| debug
|--------------------------------------------------------------------------
*/
if (!function_exists('dd')) {
    /**
     * @param $arg
     */
    function dd($arg)
    {
        var_dump($arg);
        die;
    }
}

/*
|--------------------------------------------------------------------------
| Session and flash message
|--------------------------------------------------------------------------
*/

if (!function_exists('__session_start')) {
    function __session_start()
    {
        if (!isset($_SESSION))
            session_start();

    }
}

if (!function_exists('setFlashMessage')) {
    /**
     * @param $message
     * @param string $type
     */
    function setFlashMessage($message, $type = 'success')
    {
        __session_start();

        $_SESSION['flash'] = [
            'message' => $message,
            'type'    => $type
        ];
    }
}

if (!function_exists('getFlashMessage')) {
    /**
     * @return string
     */
    function getFlashMessage()
    {
        __session_start();

        if (!empty($_SESSION['flash'])) {
            $html = '    <div class="info">
    <strong class="' . $_SESSION['flash']['type'] . '">' . $_SESSION['flash']['message'] . ' </strong>
    </div>';
            unset($_SESSION['flash']);

            return $html;
        }
    }
}

if (!function_exists('hasFlashMessage')) {
    /**
     * @return bool
     */
    function hasFlashMessage()
    {
        __session_start();

        if (!empty($_SESSION['flash'])) {
            return true;
        }

        return false;
    }
}

/*
|--------------------------------------------------------------------------
| Security
|--------------------------------------------------------------------------
*/
if (!function_exists('render')) {
    /**
     * @param $string
     * @param bool $op
     * @return string
     */
    function render($string, $op = true)
    {
        if ($op) {
            echo htmlentities($string, ENT_COMPAT, 'UTF-8');
        } else {
            return htmlentities($string, ENT_COMPAT, 'UTF-8');
        }
    }
}

// injection script uri
if (!function_exists('filter_script')) {
    /**
     * @param $request
     */
    function filter_script($request)
    {
        if (preg_match('/script/', $request)) {
            throw new RuntimeException('418');
        }
    }
}

if (!function_exists('_token')) {
    function _token()
    {
        $token = md5(date('Y-m-d h:i:00') . getenv('SALT'));

        return '<input type="hidden" name="_token" value="' . $token . '">';
    }
}

if (!function_exists('checked_token')) {
    /**
     * @param $token
     * @return bool
     */
    function checked_token($token)
    {
        if (!empty($token)) {
            foreach (range(0, getEnv('VALID_TIME')) as $v) {
                if (($token == md5(date('Y-m-d h:i:00', time() - $v * 60) . getEnv('SALT')))) {
                    return true;
                }
            }

            return false;
        }

        throw new RuntimeException('no _token checked');
    }
}

if (!function_exists('generate_salt')) {
    /**
     * @param int $length
     * @param bool $strong
     * @return string
     */
    function generate_salt($length = 16, $strong = true)
    {
        if (PHP_MAJOR_VERSION >= 7) {
            $bytes = random_bytes($length);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);

            if ($bytes === false || $strong === false) {
                throw new RuntimeException('Unable to generate random string.');
            }
        } else {
            throw new RuntimeException('OpenSSL extension is required for PHP 5 users.');
        }

        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}

/*
|--------------------------------------------------------------------------
| HTTP App request and response
|--------------------------------------------------------------------------
*/
if (!function_exists('response')) {
    /**
     * @param string $status
     * @param string $content
     */
    function response($status = "200 OK", $content = 'text/html')
    {
        header("HTTP/1.1 $status");
        header("Content-Type: $content, charset=UTF-8");
    }
}

if (!function_exists('redirect')) {
    /**
     * @param $route
     * @param string $status
     * @param string $content
     */
    function redirect($route, $status = '302 Moved Temporarily', $content = 'text/html')
    {
        header("HTTP/1.1 $status");
        header("Content-Type: $content, charset=UTF-8");
        header('Location:' . url($route));
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| url path
|--------------------------------------------------------------------------
*/
if (!function_exists('url')) {
    /**
     * @param string $path
     * @param null $params
     * @return string
     */
    function url($path = '', $params = null)
    {
        if ($path == 'self') {
            return htmlentities($_SERVER["REQUEST_URI"]); // protection injection script
        }

        $p = '';
        $sep = '/';

        $path = ltrim($path, '/');

        if (!empty($params)) {
            if (is_array($params)) {
                $params = implode('/', $params);
            }
            $p = $sep . $params;
        }

        return URL_SITE . $sep . $path . $p;
    }
}

/*
|--------------------------------------------------------------------------
|  Form
|--------------------------------------------------------------------------
*/
if (!function_exists('old')) {
    /**
     * @param $name
     * @return mixed
     */
    function old($name)
    {
        __session_start();

        if (!empty($_SESSION['old'][$name])) {
            $old = $_SESSION['old'][$name];
            $_SESSION['old'][$name] = [];

            return $old;
        }
    }
}

if (!function_exists('errors')) {
    /**
     * @param $name
     * @return mixed
     */
    function errors($name)
    {
        __session_start();

        if (!empty($_SESSION['errors'][$name])) {

            $errors = $_SESSION['errors'][$name];
            $_SESSION['errors'][$name] = [];

            return $errors;
        }
    }
}

/*
|--------------------------------------------------------------------------
|  Auth
|--------------------------------------------------------------------------
*/
if (!function_exists('auth_guest')) {
    /**
     * @return bool
     */
    function auth_guest()
    {
        __session_start();

        if (!empty($_SESSION['secu']) && ($_SESSION['secu'] == getEnv('SECU'))) {
            return true;
        }

        return false;
    }
}

if (!function_exists('auth_logout')) {
    /**
     * @description delete session
     */
    function auth_logout()
    {
        __session_start();

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}

/*
|--------------------------------------------------------------------------
|   Upload image dependencies trans function
|--------------------------------------------------------------------------
*/
if (!function_exists('upload')) {
    function upload($file, $types = ['image/png', 'image/jpg', 'image/gif', 'image/jpeg'])
    {
        $errors = [
            1 => 'dépasse la taille définie dans le fichier php.ini',
            2 => 'dépasse la taille définie dans le formulaire MAX_FILE_SIZE',
            3 => 'Le fichier n\'a pas être téléchargé',
            4 => 'aucune image téléchargée',
            6 => 'pas de dossier tmp',
            7 => 'échec de l\'écriture du fichier sur le disque',
        ];

        if ($file['error'] > 0) {
            throw new  RuntimeException($errors[$file['error']]);
        }

        $directory = getEnv('UPLOAD_DIRECTORY');

        $size = getimagesize($file['tmp_name']);

        if (!in_array($size['mime'], $types)) {
            throw new RuntimeException('type de l\'image inconu');
        }

        if (count(scandir($directory)) - 2 > getEnv('MAX_IMAGES')) {
            throw new RuntimeException('plus de place dans le répertoire');
        }

        $ext = substr(strrchr($file['name'], '.'), 1);
        $fileName = md5(uniqid(rand(), true)) . '.' . $ext;

        if (move_uploaded_file($file['tmp_name'], $directory . '/' . $fileName)) {
            return ['filename' => $fileName, 'size' => $size['size']];
        }

        throw new RuntimeException('Impossible de télécharger l\'image');
    }
}

/*
|--------------------------------------------------------------------------
|   Translate
|--------------------------------------------------------------------------
*/
if (!function_exists('trans')) {

    function trans($word, $field = '')
    {
        global $lang;

        if (!empty($lang) && array_key_exists($word, $lang))
            return str_replace(':attribute', $field, $lang[$word]);
    }

    //throw new RuntimeException("no key translate found");
}

/*
|--------------------------------------------------------------------------
|   Cache system
|--------------------------------------------------------------------------
*/
if (!function_exists('cache_put')) {
    /**
     * @param $key
     * @param array $posts
     */
    function cache_put($key, $posts = [])
    {
        $dCache = getEnv('CACHE_DIRECTORY');
        $file = implode('/', explode('.', $key)) . '.php';
        $fileCache = APP_PATH . '/' . $dCache . '/' . implode('_', explode('.', $key)) . '.php';

        ob_start();
        include APP_PATH . '/src/views/' . $file;
        $content = ob_get_contents();
        file_put_contents($fileCache, $content);
        ob_end_clean();

        chmod($fileCache, 0775);
    }
}

if (!function_exists('cache_get')) {
    /**
     * @param $key
     * @return bool|string
     */
    function cache_get($key)
    {
        $dCache = getEnv('CACHE_DIRECTORY');
        $file = APP_PATH . '/' . $dCache . '/' . implode('_', explode('.', $key)) . '.php';

        if (file_exists($file) && filemtime($file) > getEnv('CACHE_EXPIRE')) {
            return file_get_contents($file);
        }

        return false;
    }
}