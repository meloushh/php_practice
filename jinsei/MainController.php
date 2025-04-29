<?php

require_once BASEDIR.'/framework/functions.php';
require_once BASEDIR.'/framework/responses.php';
require_once BASEDIR.'/jinsei/models.php';

class MainController {
    function Homepage() {
        $docs = Document::GetAll();

        $response = new HtmlResponse(__DIR__.'/frontend/homepage.php', [
            'documents' => $docs,
            'doc_id' => 0
        ]);
        $response->Send();
    }

    function CreateDocument() {
        $request = App::$inst->request;
        $doc = new Document($request->postParams);
        $id = $doc->Create();
        
        $response = new RedirectResponse('documents/'.$id);
        $response->Send();
    }

    function GetDocument(int $id) {
        $docs = Document::GetAll();
        if (isset($docs[$id]) == false)
            throw new Exception("Document with id {$id} doesn't exist");

        $response = new HtmlResponse(__DIR__.'/frontend/homepage.php', [
            'documents' => $docs,
            'doc_id' => $id
        ]);
        $response->Send();
    }

    function UpdateDocument(int $id) {
        $doc = Document::GetOne($id);
        $req = App::$inst->request;
        $doc->title = $req->postParams['title'];
        $doc->content = $req->postParams['content'];
        $doc->Update();

        $response = new RedirectResponse($req->uri);
        $response->Send();
    }

    function DeleteDocument($id) {
        $doc = Document::GetOne($id);
        $doc->Delete();

        new RedirectResponse('documents')->Send();
    }
}

?>