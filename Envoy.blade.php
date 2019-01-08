@servers(['web1' => '127.0.0.1', 'web2' => ['carsonlius@172.31.154.122']])

@story('deploy')
    @if($commit)
        push_web1
        pull_web2
    @else
        intro
    @endif
@endstory

@task('intro')
    echo 'Please input commit!';
@endtask

@task('pull_web2', ['on' => [ 'web2'], 'parallel' => true])
    cd laravel_learn
    git pull origin master
@endtask

@task('push_web1',  ['on' => [ 'web1'], 'parallel' => true])
    git add .
    git commit -m 'modify: {{ $commit }}'
    git push
    echo 'well done!'
@endtask

@setup
    $now = new DateTime();
    var_dump($now);
@endsetup