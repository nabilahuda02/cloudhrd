@servers(['web' => '192.168.1.1'])

@task('branchproduction')
    git checkout production
    git merge master
    git push
@endtask

@servers(['web' => 'cloudhrd'])

@task('deploylive', ['on' => 'web'])
    cd /var/www/html/ihr
    git checkout production
    ./deploy
@endtask

@task('branchmaster')
    git checkout master
    git merge production
    git push
    curl http://sands.cloudhrd.com/backend/migratedb
@endtask

@story('deploy')
    branchproduction
    deploylive
    branchmaster
@endstory
