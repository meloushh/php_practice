<?php

require_once 'models.php';

class DocumentController {
    function PageAllDocs() {
        $docs = Document::GetAll();

        new HtmlResponse(__DIR__.'/documents.php', [
            'documents' => $docs,
            'doc_id' => 0
        ])->Send();
    }

    function Create() {
        $request = App::$inst->request;
        $doc = new Document($request->post_params);
        $id = $doc->Create();
        
        $response = new RedirectResponse('documents/'.$id);
        $response->Send();
    }

    function PageOneDoc(int $id) {
        $docs = Document::GetAll();
        if (isset($docs[$id]) == false)
            throw new Exception("Document with id {$id} doesn't exist");

        $response = new HtmlResponse(__DIR__.'/frontend/homepage.php', [
            'documents' => $docs,
            'doc_id' => $id
        ]);
        $response->Send();
    }

    function Update(int $id) {
        $doc = Document::GetOne('WHERE id = ?', [$id]);
        $req = App::$inst->request;
        $doc->title = $req->post_params['title'];
        $doc->content = $req->post_params['content'];
        $doc->Update();

        $response = new RedirectResponse($req->uri);
        $response->Send();
    }

    function Delete($id) {
        $doc = Document::GetOne($id);
        $doc->Delete();

        new RedirectResponse('documents')->Send();
    }
}