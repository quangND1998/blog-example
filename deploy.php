<?php

namespace Deployer;

require 'recipe/laravel.php';
require __DIR__ . '/vendor/autoload.php';

$dotenv = new \Symfony\Component\Dotenv\Dotenv;
$dotenv->load(__DIR__.'/.env');

set('application', getenv('APP_NAME'));
set('repository', getenv('DEP_GITHUB_REPO'));
set('default_stage', 'production');
set('ssh_multiplexing', true);

set('shared_files', [
    '.env'
]);
set('shared_dirs', [
    'vendor',
    'storage',
    'public/build',
]);
set('writable_dirs', [
    'vendor',
    'storage',
    'public/build',
]);

host(getenv('DEP_HOST'))
    ->user(getenv('DEP_USER'))
    ->stage('production')
    ->identityFile(getenv('DEP_RSA_PATH'))
    ->set('deploy_path', getenv('DEP_DEPLOY_PATH'))
    ->set('branch', 'master');

task('deploy', function () {
    run('cd {{release_path}}');
    run('composer install');
    run('composer dump-autoload');
    run('php artisan migrate');
    run('php artisan config:cache');
    run('php artisan route:cache');
    run('php artisan optimize');
});
