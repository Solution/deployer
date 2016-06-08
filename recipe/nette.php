<?php
/* (c) Patrik Tomasik <tomasikpatrik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/common.php';

/**
 * Nette configuration
 */

// Nette shared dirs
set('shared_dirs', ['logs/']);

// Nette shared files
set('shared_files', ['app/config/config.neon']);

// Symfony writable dirs
set('writable_dirs', ['temp/', 'logs/']);


/**
 * Create cache dir
 */
task('deploy:create_cache_dir', function () {
    // Set cache dir
    env('cache_dir', '{{release_path}}/' . trim(get('var_dir'), '/') . '/cache');

    // Remove cache dir if it exist
    run('if [ -d "{{cache_dir}}" ]; then rm -rf {{cache_dir}}; fi');

    // Create cache dir
    run('mkdir -p {{cache_dir}}');

    // Set rights
    run("chmod -R g+w {{cache_dir}}");
})->desc('Create cache dir');


/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');
