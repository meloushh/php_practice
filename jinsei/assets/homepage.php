<?php
/** 
 * @var Document[] $documents
 * @var int $doc_id
 */
?>

@extends('/jinsei/assets/layout.php')

@section_start('body')
<div class="flex">
    <div id="col1" style="width: 25%;" class="p2">
        <a href="/" class="block border1 bg-btn1 p2 py1">+ New</button>

        <?php foreach ($documents as $document): ?>
            <a href="/documents/<?= $document->id ?>" 
                class="block border1 p2 mt2 <?= $doc_id == $document->id ? 'btn-active' : '' ?>"
            >
                <?= $document->title ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div id="col2" style="width: 75%; border-left: 1px solid var(--color3)" class="p2">

        <form action="/documents<?= $doc_id ? '/'.$doc_id : '' ?>" method="POST">
            <div class="flex">
                <input type="text" name="title" class="border1 p1" placeholder="Title"
                    value="<?= $doc_id ? $documents[$doc_id]->title : '' ?>">
                <input type="submit" value="Save" class="border1 bg-btn1 p2 ml2">
            </div>
            <textarea id="editor" placeholder="Content" name="content" class="border1 p1 mt2" style="width: 100%; height: 600px"
            ><?= $doc_id ? $documents[$doc_id]->content : '' ?></textarea>
        </form>

    </div>
</div>

<script src="/jinsei/assets/homepage.js"></script>
@section_end