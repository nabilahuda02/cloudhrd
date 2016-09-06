@servers(['web' => 'cloudhrd', 'local' => '127.0.0.1'])

@task('branchproduction', ['on' => 'local'])
    cd /private/var/www/cloudhrd.dev/
    git checkout production
    git merge master
    git push
@endtask

@task('deploylive', ['on' => 'web'])
    cd /var/www/html/ihr
    ./deploy
    cd /var/www/html/ihr/web/app
    php artisan db:sync
@endtask

@task('branchmaster', ['on' => 'local'])
    git checkout master
    git merge production
    git push
    cd /private/var/www/cloudhrd.dev/web/app
@endtask

@macro('deploy')
    branchproduction
    deploylive
    branchmaster
@endmacro
