# Final Project
Build by Jason Surya Faylim (FWD1)

## How To Develop
Because this project uses Vite and is going to use a Vue SPA in PHP, a special setup must be made to be able to run this project in development mode.

A local domain must be added in the ```hosts``` file. In Linux, this file can be found in ```/etc/hosts```.

Required tools:
- Node (v16)
- NPM
- PNPM
- Composer

A virtual host must be set in apache2 configuration file. By default, this configuration file can be found in ```/etc/apache2/sites-available/000-default.conf```.

For example:

```
<VirtualHost *:80>
  ServerAdmin webmaster@arkastore.local
  ServerName arkastore.local

  DocumentRoot /var/www/MBKM/server

  ErrorLog ${APACHE_LOG_DIR}/error.log
  CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

## Interesting Packages / Repositories Used in This Project
- [vitesse-lite](https://github.com/antfu/vitesse-lite)
- [unocss](https://github.com/unocss/unocss)
- [vite](https://github.com/vitejs/vite)
- [vuetify](https://github.com/vuetifyjs/vuetify)
- [vite-php-setup](https://github.com/andrefelipe/vite-php-setup)