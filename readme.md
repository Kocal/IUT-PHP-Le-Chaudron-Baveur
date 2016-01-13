# Le Chaudron Baveur

Site web de ventes aux enchères dans le cadre d'un projet PHP à l'IUT Info'.

## Choses à savoir

- Le site web a été créé avec le framework Laravel.
- Le code personnel se trouve dans les dossiers suivants :
    - app/
    - app/Http/
    - app/Http/Controllers/
    - public/js/
    - resources/assets/sass/
    - resources/views/
    - resources/views/admin/
    - resources/views/auth/
    - resources/views/emails
    - resources/views/layouts

## Technologies utilisées

- Linux 3.10.18 x86_64 (chroot d'Ubuntu 14.04 Trusty)
- Apache 2.4.16
- PHP 5.6.14
- MySQL 5.5.44
- NodeJS 0.12.5
- Npm 2.11.2
- Composer 1.0-dev

## Installation

```bash
$ git clone https://github.com/Kocal/Le-Chaudron-Baveur.git
$ cp .env.example .env # Configuration du site à modifier selon vos besoins
$ npm install
$ composer install
$ chmod -R 777 storage
$ chmod -R 777 public/upload
$ php artisan key:generate
```
