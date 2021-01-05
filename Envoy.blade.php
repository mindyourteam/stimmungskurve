@servers(['web' => 'stimmungskurve.de'])

@setup
    $repo = 'git@github.com:mindyourteam/stimmungskurve.git';
    $root_dir = '/var/www/stimmung';
    $releases_dir = "{$root_dir}/releases";
    $now = strftime('%Y%m%d-%H%M%S');
    $release_dir = "{$releases_dir}/{$now}";

    if (!empty($target)) {
        $env = '.env-' . $target;
    }
    else {
        $target = 'current';
        $env = '.env';
    }
@endsetup

@task('test', ['on' => 'web'])
    cd {{ $root_dir }}/current
    vendor/bin/phpunit packages/mindyourteam/barometer/tests
@endtask

@task('deploy', ['on' => 'web'])
    set -x
    if [[ "{{ $target }}" = "current" ]]
    then
      cd {{ $releases_dir }};
      old=$(ls -t1  | tail -n +3)
      if [[ -n $old ]]; then echo $old | xargs rm -r; fi
    fi
    cd {{ $root_dir }};
    git clone --recursive {{ $repo }} {{ $release_dir }};
    cd {{ $release_dir }};
    git submodule update --init --remote --recursive

    rm -rf storage
    rm -rf bootstrap/cache
    ln -s {{ $root_dir }}/shared/storage storage
    ln -s {{ $root_dir }}/shared/cache bootstrap/cache
    ln -s {{ $root_dir }}/shared/{{ $env }} .env
    rm -rf public/system
    ln -s {{ $root_dir }}/shared/storage/app/public public/storage
    #ln -s {{ $root_dir }}/shared/uploads public/uploads

    composer install --ignore-platform-reqs

    php artisan cache:clear
    php artisan migrate

    npm install --no-optional
    npm run production

    #chmod 777 storage/logs/laravel.log
    #sudo setfacl -R -m u:www-data:rwX -m u:olav:rwX {{ $root_dir }}/shared/storage
    #sudo setfacl -dR -m u:www-data:rwX -m u:olav:rwX {{ $root_dir }}/shared/storage

    cd {{ $root_dir }};
    ln -s {{ $release_dir }} {{ $target }}-{{ $now }}
    mv -T {{ $target }}-{{ $now }} {{ $target }}

    cd {{ $root_dir }}/{{ $target }}; php artisan queue:restart; sudo /usr/local/sbin/restart-php
    @endtask
