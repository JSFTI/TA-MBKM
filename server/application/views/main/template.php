<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php foreach($meta as $name => $content): ?>    
    <meta name="<?= $name ?>" content="<?= $content ?>" />
  <?php endforeach; ?>

	<title><?= $title ?? $_ENV['APP_NAME'] ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"> 

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/css/glide.core.min.css" integrity="sha512-tYKqO78H3mRRCHa75fms1gBvGlANz0JxjN6fVrMBvWL+vOOy200npwJ69OBl9XEsTu3yVUvZNrdWFIIrIf8FLg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@unocss/reset/tailwind.min.css">

	<link href="<?= base_url('/public/assets/css/style.css') ?>" rel="stylesheet" />
	<link href="<?= base_url('/public/assets/css/uno.css') ?>" rel="stylesheet" />

  <script>
    window.BASE_URL = `<?= site_url('/') ?>`;

    document.addEventListener('alpine:init', () => {
      Alpine.store('user', {
        data: JSON.parse(`<?= json_encode($user) ?>`)
      })
    });
  </script>

  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/glide.min.js" integrity="sha512-2sI5N95oT62ughlApCe/8zL9bQAXKsPPtZZI2KE3dznuZ8HpE2gTMHYzyVN7OoSPJCM1k9ZkhcCo3FvOirIr2A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
  <script defer src="<?= base_url('/public/assets/js/inputmask.min.js') ?>"></script>
  <script defer src="<?= base_url('/public/assets/js/app.js') ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qs/6.11.0/qs.min.js" integrity="sha512-/l6vieC+YxaZywUhmqs++8uF9DeMvJE61ua5g+UK0TuHZ4TkTgB1Gm1n0NiA86uEOM9JJ6JUwyR0hboKO0fCng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
  <?php if($withNavbar): ?>
  <?php $this->load->view("main/partials/navbar", $data) ?>
  <?php endif; ?>
  <?php $this->load->view("main/pages/$page", $data) ?>
</body>
</html>