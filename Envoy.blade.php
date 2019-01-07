@servers(['web1' => ['carsonlius@127.0.0.1'], 'web2' => ['carsonlius@172.31.154.122']])

@task('show', ['on' => [ 'web2'], 'parallel' => true])
cd laravel_learn
ls -la
@endtask