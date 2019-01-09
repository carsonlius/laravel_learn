@servers(['web3' => ['carsonlius@172.31.154.121'], 'web2' => ['carsonlius@172.31.154.122'], 'web1' => '127.0.0.1'])
@setup
    $environment = isset($env) ? $env : "testing";
@endsetup

@story('deploy')
    @if($commit)
        push_web1
        pull_web2
    @else
        intro
    @endif
@endstory

@task('intro')
    echo 'Please input commit!'
@endtask

@task('pull_web2', ['on' => [ 'web2', 'web3'], 'parallel' => true])
    cd laravel_learn
    git pull origin master
@endtask

@task('push_web1',  ['on' => [ 'web1'], 'parallel' => true])
    git add .
    git commit -m 'modify: {{ $commit }}'
    git push
    echo 'well done!'
@endtask
