<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite App</title>

    <?= vite('panel/src/main.ts') ?>
    <script>
      /**
       * THIS VARIABLE IS ONLY USED TO DETECT POSSIBLE LOGIN
       * USER CAN EDIT THIS VARIABLE THROUGH THE DEV CONSOLE
       * THIS IS NOT A SECURITY CONCERN BECAUSE THE API IS AUTHENTICATED WITH
       * STATEFUL CI SESSION COOKIE
       */
      window._session_user = JSON.parse(`<?= json_encode($this->session->userdata('user')) ?>`)
    </script>
</head>

<body>
  <div id="app"></div>
</body>

</html>