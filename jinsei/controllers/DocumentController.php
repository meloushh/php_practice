<?php

require_once APP_DIR.'/models.php';
use Framework\App;
use Framework\HtmlResponse;
use Framework\RedirectResponse;

class DocumentController {
    protected int $auth_user_id = 0;



    function __construct() {
        $this->auth_user_id = App::$si->GetAuthUserId();
        
        if ($this->auth_user_id === 0) {
            throw new Exception('Unauthenticated');
        }
    }

    function PageAllDocs() {
        $request = App::$si->request;
        $q = 'WHERE user_id = ?';
        $data = [$this->auth_user_id];

        if (isset($request->get['doc_name'])) {
            $q .= " AND title LIKE '%' || ? || '%'";
            $data[] = $request->get['doc_name'];
        }

        $docs = Document::GetAll($q, $data);

        $response = new HtmlResponse(APP_DIR.'/fe/documents.php', [
            'documents' => $docs,
            'doc_id' => 0
        ]);
        $response->Send();
    }

    function Create() {
        $request = App::$si->request;

        $data = $request->post;
        $data['user_id'] = $this->auth_user_id;
        $doc = Document::Create($data);
        
        $response = new RedirectResponse('/documents/'.$doc->id);
        $response->Send();
    }

    function PageOneDoc(int $id) {
        $docs = Document::GetAll('WHERE user_id = ?', [$this->auth_user_id]);
        
        if (isset($docs[$id])===false)
            throw new Exception("Document with id {$id} doesn't exist");

        $response = new HtmlResponse(APP_DIR.'/fe/documents.php', [
            'documents' => $docs,
            'doc_id' => $id
        ]);
        $response->Send();
    }

    function Update(int $id) {
        $doc = Document::GetOne('WHERE id = ?', [$id]);
        if ($doc === null) {
            throw new Exception();
        }

        $req = App::$si->request;
        $doc->title = $req->post['title'];
        $doc->content = $req->post['content'];
        $doc->Update();

        $response = new RedirectResponse($req->uri);
        $response->Send();
    }

    function Delete($id) {
        $doc = Document::GetOne('WHERE id = ?', [$id]);
        if ($doc === null) {
            throw new Exception();
        }
        $doc->Delete();

        $response = new RedirectResponse('/documents');
        $response->Send();
    }
}