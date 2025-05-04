<?php

require_once 'models.php';

class DocumentController {
    protected int $auth_user_id = 0;



    function __construct() {
        $this->auth_user_id = App::$inst->GetAuthUserId();
    }

    function PageAllDocs() {
        $docs = Document::GetAll('WHERE user_id = ?', [$this->auth_user_id]);

        return new HtmlResponse(__DIR__.'/frontend/documents.php', [
            'documents' => $docs,
            'doc_id' => 0
        ])->Send();
    }

    function Create() {
        $request = App::$inst->request;

        $data = $request->post_params;
        $data['user_id'] = $this->auth_user_id;
        $doc = Document::Create($data);
        
        return new RedirectResponse('/documents/'.$doc->id)->Send();
    }

    function PageOneDoc(int $id) {
        $docs = Document::GetAll('WHERE user_id = ?', [$this->auth_user_id]);
        
        if (isset($docs[$id])===false)
            throw new Exception("Document with id {$id} doesn't exist");

        return new HtmlResponse(__DIR__.'/frontend/homepage.php', [
            'documents' => $docs,
            'doc_id' => $id
        ])->Send();
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