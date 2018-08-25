<?php

namespace Deployer;

require 'recipe/laravel.php';
require __DIR__ .'/vendor/deployer/recipes/recipe/rsync.php';
// Project name
set('application', 'Emergency');

// Hosts
host('dechees.devpoint.kz')
    ->stage('staging')
    ->set('deploy_path', '/mnt/{{hostname}}')
    ->user('deployer');

set('rsync_src', __DIR__);
set('rsync_dest', '{{release_path}}');
set('rsync', [
    'exclude' => [
        '.git',
        'deploy.php',
        'servers.yml',
    ],
    'exclude-file' => false,
    'include' => [],
    'include-file' => false,
    'filter' => [],
    'filter-file' => false,
    'filter-perdir' => false,
    'flags' => 'rz', // Recursive, with compress
    'options' => ['delete'],
    'timeout' => 60,
]);

// Добавляем задачу по перезапуску php fpm
desc('Restart PHP-FPM service');
task('php-fpm:reload', function () {
    // The user must have rights for restart service
    // /etc/sudoers.d/deployer: deployer ALL=NOPASSWD: /usr/sbin/service php7.0-fpm reload
    run('sudo service php7.2-fpm reload');
});
after('deploy:symlink', 'php-fpm:reload');

desc('Make symlink for uploads');
task('deploy:public_uploads', function () {
    // Remove from source.
    run('if [ -d $(echo {{release_path}}/public/uploads) ]; then rm -rf {{release_path}}/public/uploads; fi');
    // Create shared dir if it does not exist.
    run('mkdir -p {{deploy_path}}/shared/storage/app/uploads');
    // Symlink shared dir to release dir
    run('{{bin/symlink}} {{deploy_path}}/shared/storage/app/uploads {{release_path}}/public/uploads');
});
before('deploy:symlink', 'deploy:public_uploads');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('artisan:migrate', 'artisan:db:seed');

//Запуск задач
task('deploy', [
    'deploy:prepare',
    //'deploy:lock',
    'deploy:release',
    'rsync',
    'deploy:shared',
    //'deploy:vendors',
    'deploy:writable',
    'artisan:migrate',
    'deploy:symlink',
    //'deploy:unlock',
    'cleanup',
]);

