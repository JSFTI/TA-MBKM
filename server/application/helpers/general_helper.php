<?php

use App\Models\User;

if(!function_exists('alpine_json')){
  function alpine_json(mixed $json): string{
    return htmlspecialchars(json_encode($json));
  }
}

// from: https://stackoverflow.com/a/43746181
if(!function_exists('validate_url')){
  function validate_url($url): bool {
    $path = parse_url($url, PHP_URL_PATH);
    $encoded_path = array_map('urlencode', explode('/', $path));
    $url = str_replace($path, implode('/', $encoded_path), $url);

    return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
  }
}

if(!function_exists('auth')){
  function auth(): null | User{
    $ci = &get_instance();
    if($ci->session->userdata('user')){
      $user = $ci->session->userdata('user');
      return User::with('role')->find($user['id'], ['id', 'role_id', 'email', 'email_verified_at', 'name', 'profile_picture']);
    }
    return null;
  }
}

if(!function_exists('re_auth')){
  function re_auth(): null | User{
    $ci = &get_instance();
    if($ci->session->userdata('user')){
      $user = User::with('role')
        ->find($ci->session->userdata('user')['id'], ['id', 'role_id', 'profile_picture', 'name', 'email']);
      
      $ci->session->set_userdata('user', $user->toArray());

      return $user;
    }
    return null;
  }
}

if(!function_exists('response_json')){
  function response_json(mixed $json, $code = 200, int $flags = 0, $echo = true) : string|null{
    http_response_code($code);
    header('Content-Type: application/json');
    $json = json_encode($json, $flags);
    if($echo){
      echo $json;
      return null;
    }
    return $json;
  }
}

if(!function_exists('response_file')){
  function response_file(string $file_out){
    if(file_exists($file_out) && !is_dir($file_out)){
      $image_info = getimagesize($file_out);

      //Set the content-type header as appropriate
      header('Content-Type: ' . $image_info['mime']);

      //Set the content-length header
      header('Content-Length: ' . filesize($file_out));

      //Write the image bytes to the client
      readfile($file_out);
    } else {
      header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    }
  }
}

if(!function_exists('get_input_json')){
  /**
   * Get request body as JSON, if content type is form data, convert form data to stdObject or associative array
   * 
   * @param bool $associative
   * 
   * @return stdClass|array
   */
  function get_input_json($associative = FALSE){
    if($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH'){
      return json_decode(file_get_contents("php://input"), $associative);
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json'){
      return json_decode(file_get_contents("php://input"), $associative);
    }

    if($associative){
      return $_POST;
    }

    return (object) $_POST;
  }
}

if(!function_exists('is_date_format_iso')){
  function is_date_format_iso($date){
    $date = DateTime::createFromFormat("Y-m-d", $date);
    return $date !== false && $date::getLastErrors();
  }
}
?>