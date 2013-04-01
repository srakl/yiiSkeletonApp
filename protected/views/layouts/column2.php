<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="span9">
        <?php echo $content; ?>
    </div>
    <div class="span3">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'tabs',
            'stacked' => true,
            'items' => $this->menu,
        )); ?>
    </div>
</div>
<?php $this->endContent(); ?>