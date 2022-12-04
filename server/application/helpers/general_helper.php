<?php
if(!function_exists('alpine_json')){
  function alpine_json(mixed $json){
    return htmlspecialchars(json_encode($json));
  }
}

// from: https://stackoverflow.com/a/43746181
if(!function_exists('validate_url')){
  function validate_url($url) {
    $path = parse_url($url, PHP_URL_PATH);
    $encoded_path = array_map('urlencode', explode('/', $path));
    $url = str_replace($path, implode('/', $encoded_path), $url);

    return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
  }
}
?>