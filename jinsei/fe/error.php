<?php
/**
 * @var string|null $message;
 */
?>

@extends('/jinsei/fe/web_template.php')

@section_start('body')
<div class="flex justify_center align_center" style="height: 92vh;">
    <div class="border1 p4" style="width: 400px;">
        <?= $message ?? 'Oh no, an error!' ?>
    </div>
</div>
@section_end