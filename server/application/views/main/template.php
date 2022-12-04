<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= $meta?->description ?? '' ?>" />

	<title><?= $title ?? $_ENV['APP_NAME'] ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> 

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@unocss/reset/tailwind.min.css">
  <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
	<link href="<?= base_url('/public/assets/css/style.css') ?>" rel="stylesheet" />
	<link href="<?= base_url('/public/assets/css/uno.css') ?>" rel="stylesheet" />

  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.store('user', {
        data: JSON.parse(`<?= json_encode($user) ?>`)
      })
    });
  </script>
</head>
<body>
  <?php $withNavbar ? $this->component->load('navbar') : '' ?>
  <?php $this->load->view("main/pages/$page", $data) ?>
</body>
</html>