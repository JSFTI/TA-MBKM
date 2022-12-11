<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'HomepageController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'AuthController/login';
$route['logout'] = 'AuthController/logout';

// Catch all panel route
$route['panel(.*)'] = 'PanelController';

// Panel API
$route['api/profile']['get'] = 'Panel/ProfileController';
$route['api/profile/password']['post'] = 'Panel/ProfileController/changePassword';

$route['api/roles']['get'] = 'Panel/RoleController';

$route['api/users']['get'] = 'Panel/UserController';
$route['api/users']['post'] = 'Panel/UserController/create';
$route['api/users/(:num)']['get'] = 'Panel/UserController/show/$1';
$route['api/users/(:num)']['put'] = 'Panel/UserController/update/$1';
$route['api/users/(:num)']['patch'] = 'Panel/UserController/edit/$1';
$route['api/users/(:num)']['delete'] = 'Panel/UserController/destroy/$1';

$route['api/users/(:num)/password']['post'] = 'Panel/User/PasswordController/update/$1';

$route['api/users/(:num)/profile-picture']['get'] = 'Panel/User/ProfilePictureController/index/$1';
$route['api/users/(:num)/profile-picture']['post'] = 'Panel/User/ProfilePictureController/update/$1';
$route['api/users/(:num)/profile-picture']['delete'] = 'Panel/User/ProfilePictureController/destroy/$1';

$route['api/carousels']['get'] = 'Panel/CarouselController';
$route['api/carousels']['post'] = 'Panel/CarouselController/create';
$route['api/carousels/(:num)']['delete'] = 'Panel/CarouselController/destroy/$1';

$route['api/carousels/approved']['patch'] = 'Panel/Carousel/ApprovedController/updateOrder/$1';
$route['api/carousels/(:num)/approved']['put'] = 'Panel/Carousel/ApprovedController/update/$1';
$route['api/carousels/(:num)/approved']['delete'] = 'Panel/Carousel/ApprovedController/destroy/$1';

$route['api/categories']['get'] = 'Panel/CategoryController';

$route['api/tags']['get'] = 'Panel/TagController';

$route['api/products']['get'] = 'Panel/ProductController';
$route['api/products/(:num)']['get'] = 'Panel/ProductController/show/$1';
$route['api/products']['post'] = 'Panel/ProductController/create';
$route['api/products/(:num)']['put'] = 'Panel/ProductController/update/$1';
$route['api/products/(:num)']['delete'] = 'Panel/ProductController/destroy/$1';

$route['api/products/(:num)/published']['put'] = 'Panel/Product/PublishController/index/update/$1';
$route['api/products/(:num)/published']['delete'] = 'Panel/Product/PublishController/index/destroy/$1';

$route['storage/(.*)'] = 'StorageController/index/$1';