<?php $commit = isset($commit) ? $commit : null; ?>
<?php $a = isset($a) ? $a : null; ?>
<?php $env = isset($env) ? $env : null; ?>
<?php $environment = isset($environment) ? $environment : null; ?>
<?php $__container->servers(['web1' => '127.0.0.1', 'web2' => ['carsonlius@172.31.154.122']]); ?>
<?php
    $environment = isset($env) ? $env : "testing";
    $a = 'ali';
    echo $environment;
    echo 'what happend';
    ls -lh .
?>

<?php $__container->startMacro('deploy'); ?>
    <?php if($commit): ?>
        push_web1
        pull_web2
    <?php else: ?>
        intro
    <?php endif; ?>
<?php $__container->endMacro(); ?>

<?php $__container->startTask('look', ['confirm' => true]); ?>
    echo 'ME <?php echo $environment; ?> <?php echo $a; ?>';
<?php $__container->endTask(); ?>

<?php $__container->startTask('intro'); ?>
        <?php if($a): ?>
            echo 'Please input commit! <?php echo $a; ?>'
        <?php else: ?>
            echo 'Please input commit! '
        <?php endif; ?>
<?php $__container->endTask(); ?>

<?php $__container->startTask('pull_web2', ['on' => [ 'web2'], 'parallel' => true]); ?>
    cd laravel_learn
    git pull origin master
<?php $__container->endTask(); ?>

<?php $__container->startTask('push_web1',  ['on' => [ 'web1'], 'parallel' => true]); ?>
    git add .
    git commit -m 'modify: <?php echo $commit; ?>'
    git push
    echo 'well done!'
<?php $__container->endTask(); ?>
