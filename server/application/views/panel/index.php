<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite App</title>

    <?= vite($_ENV['APP_MODE'] === 'production' ? 'panel/src/main.ts' : 'src/main.ts') ?>
    <script>
      /**
       * THIS VARIABLE IS ONLY USED TO STORE USER DATA.
       * USER CAN EDIT THIS VARIABLE THROUGH THE DEV CONSOLE.
       * THIS IS NOT A SECURITY CONCERN BECAUSE THE API IS AUTHENTICATED WITH
       * STATEFUL CI SESSION COOKIE.
       * USER AUTHENTICATION IS STILL STORED IN THE SERVER.
       * THIS OBJECT IS NOT USED IN AUTHORIZATION.
       */
      window.user = JSON.parse(`<?= json_encode($this->session->userdata('user')) ?>`)
    </script>
</head>

<body>
  <div id="app"></div>
  <script>
    (function() {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
      const setting = localStorage.getItem('color-schema') || 'auto'
      if (setting === 'dark' || (prefersDark && setting !== 'light'))
        document.documentElement.classList.toggle('dark', true)
    })();
  </script>
</body>

</html>