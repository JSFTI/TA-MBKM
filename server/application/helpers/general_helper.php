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
      return User::find($user['id'])->first();
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
    if(file_exists($file_out)){
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
?>