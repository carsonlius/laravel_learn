<?php $now = isset($now) ? $now : null; ?>
<?php $commit = isset($commit) ? $commit : null; ?>
<?php $__container->servers(['web1' => '127.0.0.1', 'web2' => ['carsonlius@172.31.154.122']]); ?>

<?php $__container->startMacro('deploy'); ?>
    <?php if($commit): ?>
        push_web1
        pull_web2
    <?php else: ?>
        intro
    <?php endif; ?>
<?php $__container->endMacro(); ?>

<?php $__container->startTask('intro'); ?>
    echo 'Please input commit!';
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

<?php
    $now = new DateTime();
    echo $now;
?>