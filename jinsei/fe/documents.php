<?php

/** 
 * @var Document[] $documents
 * @var int $doc_id
 */
?>

@extends('/jinsei/fe/web_template.php')

@section_start('body')
<div class="flex" style="height: 92vh">
    <!-- documents -->
    <div id="col1" style="width: 25%; overflow-y: auto" class="p1 mr1">
        <div class="flex align_center">
            <a href="/documents" class="block p1 <?= $doc_id === 0 ? 'btn2' : 'btn1' ?>" style="flex-grow: 0">+ New</a>
            <form action="/documents" method="GET" class="ml2" style="flex-grow: 4">
                <input type="text" name="doc_name" placeholder="Search by name" class="border1 p1 w100">
            </form>
        </div>

        <?php foreach ($documents as $document): ?>
            <a href="/documents/<?= $document->id ?>" <?= $doc_id === $document->id ? 'id="active_doc"' : '' ?>
                class="block justify_between mt2 p1 <?= $doc_id === $document->id ? 'btn2' : 'btn1' ?>" tabindex="-1">
                <?= $document->title ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- editor -->
    <div id="col2" style="width: 75%; border-left: 2px solid var(--color10)">

        <form action="/documents<?= $doc_id ? '/' . $doc_id : '' ?>" method="POST" class="p1 pb0 h100 flex"
            style="flex-direction: column;">

            <div class="flex">
                <input type="text" name="title" class="border1 p1" placeholder="Title" style="width:400px"
                    value="<?= $doc_id ? $documents[$doc_id]->title : '' ?>" tabindex="1">
                <input type="submit" value="Save" class="btn1 ml2 p1" tabindex="3">
                <?php if ($doc_id): ?>
                    <button type="button" id="delete_doc" class="btn2 p1" style="margin-left: auto;" tabindex="4">Delete</button>
                <?php endif ?>
            </div>

            <textarea id="editor" placeholder="Content" name="content" class="block border1 p1 mt2 w100" style="flex: 1 1 100%"
                tabindex="2"><?= $doc_id ? $documents[$doc_id]->content : '' ?></textarea>
        </form>

    </div>

</div>

<?php if ($doc_id > 0): ?>
    <div id="confirmation_window" class="hidden pos_absolute flex justify_center align_center w100 h100"
        style="top: 0; left: 0">

        <div class="bg4 border3 p4" style="width: 300px;">
            <p>Are you sure you want to delete this document (<?= $documents[$doc_id]->title ?>)?</p>
            <form action="/documents/<?= $doc_id ?>/del" method="POST" class="mt2 flex justify_end">
                <input type="submit" value="Yes" class="btn1 py1 px2">
                <button type="button" id="no" class="btn1 ml2 py1 px2">No</button>
            </form>
        </div>
    </div>
<?php endif ?>

<script src="/jinsei/fe/js/documents.js"></script>
@section_end