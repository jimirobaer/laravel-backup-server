![laravel_backup_server_banner_2](https://github.com/user-attachments/assets/ac21b06a-dd0d-43be-a36b-cc28aece9508)
![](https://github.com/user-attachments/assets/72af1cb5-fa3c-47aa-98ad-fd2057b4e34a)
![laravel_backup_server_banner_3](https://github.com/user-attachments/assets/f9c23ac3-29e2-45c9-a034-482396a259bd)


# Laravel Backup Server

[![Latest Stable Version](https://poser.pugx.org/spatie/laravel-backup/v/stable?format=flat-square)](https://packagist.org/packages/spatie/laravel-backup)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-backup/run-tests.yml?branch=main&label=Tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-backup.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-backup)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-backup.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-backup)

This Laravel package [creates a backup of your application](https://spatie.be/docs/laravel-backup/v8/taking-backups/overview). The backup is a zip file that contains all files in the directories you specify along with a dump of your database. The backup can be stored on [any of the filesystems you have configured in Laravel](https://laravel.com/docs/filesystem).

Feeling paranoid about backups? No problem! You can backup your application to multiple filesystems at once.

Once installed taking a backup of your files and databases is very easy. Just issue this artisan command:

``` bash
php artisan backup:run
```

But we didn't stop there. The package also provides [a backup monitor to check the health of your backups](https://spatie.be/docs/laravel-backup/v8/monitoring-the-health-of-all-backups/overview). You can be [notified via several channels](https://spatie.be/docs/laravel-backup/v8/sending-notifications/overview) when a problem with one of your backups is found.
To avoid using excessive disk space, the package can also [clean up old backups](https://spatie.be/docs/laravel-backup/v8/cleaning-up-old-backups/overview).
