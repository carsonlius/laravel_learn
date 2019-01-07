@servers(['web1' => '127.0.0.1', 'web2' => ['carsonlius@172.31.154.122']])

@task('pull_web2', ['on' => [ 'web2'], 'parallel' => true])
cd laravel_learn
git pull origin master
@endtask

@task('push_web1',  ['on' => [ 'web1'], 'parallel' => true])
cd laravel.learn
git add .
git commit -m 'modify: envoy update test'
git push
echo 'well done!'
@endtask

@story('deploy')
push_web1
pull_web2
@endstory