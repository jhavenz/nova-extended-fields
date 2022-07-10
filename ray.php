<?php /** @noinspection LaravelFunctionsInspection */

return [
    /*
     *  The host used to communicate with the Ray app.
     */
    'host' => 'host.docker.internal',

    /*
     *  The port number used to communicate with the Ray app.
     */
    'port' => 23517,

    /*
     *  Absolute base path for your sites or projects in Homestead, Vagrant, Docker, or another remote development server.
     */
    'remote_path' => env(
        'DOCKER_PROJECT_PATH',
        env('IGNITION_REMOTE_SITES_PATH', '/var/www/html')
    ),

    /*
     *  Absolute base path for your sites or projects on your local computer where your IDE or code editor is running on.
     */
    'local_path' => env(
        'LOCAL_PROJECT_PATH',
        env('IGNITION_LOCAL_SITES_PATH', '~/Packages/nova-extended-fields')
    ),
];
