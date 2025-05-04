<?php

require_once 'models.php';

class DocumentController {
    function PageAllDocs() {
        $docs = Document::GetAll();

        return new HtmlResponse(__DIR__.'/frontend/documents.php', [
            'documents' => $docs,
            'doc_id' => 0
        ])->Send();
    }

    function Create() {
        $request = App::$inst->request;
        $doc = Document::Create($request->post_params);
        
        $response = new RedirectResponse('/documents/'.$doc->id);
        return $response->Send();
    }

    function PageOneDoc(int $id) {
        $docs = Document::GetAll();
        if (isset($docs[$id])===false)
            throw new Exception("Document with id {$id} doesn't exist");

        $response = new HtmlResponse(__DIR__.'/frontend/homepage.php', [
            'documents' => $docs,
            'doc_id' => $id
        ]);
        return $response->Send();
    }

    function Update(int $id) {
        $doc = Document::GetOne('WHERE id = ?', [$id]);
        $req = App::$inst->request;
        $doc->title = $req->post_params['title'];
        $doc->content = $req->post_params['content'];
        $doc->Update();

        return new RedirectResponse($req->uri)->Send();
    }

    function Delete($id) {
        $doc = Document::GetOne($id);
        $doc->Delete();

        return new RedirectResponse('/documents')->Send();
    }
}