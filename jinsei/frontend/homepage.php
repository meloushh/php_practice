<?php
/** 
 * @var Document[] $documents
 * @var int $doc_id
 */
?>

@extends('/jinsei/frontend/layout.php')

@section_start('body')
<div class="flex">
    <div id="col1" style="width: 25%;" class="p2">
        <a href="/" class="block btn1">+ New</button>

        <?php foreach ($documents as $document): ?>
            <a href="/documents/<?= $document->id ?>" 
                class="block btn1 mt2 <?= $doc_id == $document->id ? 'bg2' : '' ?>"
            >
                <?= $document->title ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div id="col2" style="width: 75%; border-left: 1px solid var(--color3)" class="p2">

        <form action="/documents<?= $doc_id ? '/'.$doc_id : '' ?>" method="POST">
            <div class="flex">
                <input type="text" name="title" class="border1 p1" placeholder="Title" style="width:400px"
                    value="<?= $doc_id ? $documents[$doc_id]->title : '' ?>">
                <input type="submit" value="Save" class="btn1 ml2">
                <?php if ($doc_id): ?>
                    <button type="button" id="delete_doc" class="btn1" style="margin-left: auto;">Delete</button>
                <?php endif ?>
            </div>

            <textarea id="editor" placeholder="Content" name="content" class="border1 p1 mt2" style="width: 100%; height: 600px"
            ><?= $doc_id ? $documents[$doc_id]->content : '' ?></textarea>
        </form>

    </div>
</div>

<div id="confirmation_window" class="hidden pos_absolute flex justify_center align_center" 
    style="width: 100%; height: 100%; top: 0; left: 0">

    <div class="bg2 border1 p4" style="width: 300px;">
        <p>Are you sure you want to delete this document (<?= $documents[$doc_id]->title ?>)?</p>
        <form action="/documents/<?= $doc_id ?>/del" method="POST" class="mt2 flex justify_end">
            <input type="submit" value="Yes" class="btn1">
            <button type="button" id="no" class="btn1 ml2">No</button>
        </form>
    </div>
</div>

<script src="/jinsei/frontend/homepage.js"></script>
@section_end