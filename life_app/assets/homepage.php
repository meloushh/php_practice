<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="/life_app/assets/reset.css">
<link rel="stylesheet" type="text/css" href="/life_app/assets/app.css">
<title>Life app</title>
</head>

<body class="bg-main">
    <div class="flex">
        <div id="col1" style="width: 30%;" class="p2">
            <?php if(count($data['documents']) == 0): ?>
                <p>No documents at the moment</p>
            <?php endif ?>
            <?php foreach ($data['documents'] as $document): ?>
                <a href="" class="block border-main p2 mt2"><?= $document['title'] ?></a>
            <?php endforeach; ?>
        </div>

        <div id="col2" style="width: 70%; border-left: 1px solid var(--color3)" class="p2">

            <form action="documents" method="POST">
                <div class="flex">
                    <input type="text" name="title" class="border-main p1" placeholder="Title">
                    <input type="submit" value="Save" class="border-main p2 ml2 bg-btn">
                </div>
                <textarea placeholder="Content" name="content" class="border-main p1 mt2"
                    style="width: 100%; height: 600px"></textarea>
            </form>

        </div>
    </div>
    
</body>

</html>