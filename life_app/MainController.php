<?php

require_once BASEDIR.'/framework/functions.php';
require_once BASEDIR.'/framework/Response.php';
require_once BASEDIR.'/life_app/models.php';

class MainController {
    public function Homepage() {
        $documents = [];
        $result = App::$inst->db->Query("SELECT * FROM documents");
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $documents[] = $row;
        }

        $response = new HtmlResponse(__DIR__ . '/assets/homepage.php', ['documents' => $documents]);
        $response->Send();
    }

    public function CreateDocument() {
        $db = App::$inst->db;
        $db->Prepared("INSERT INTO documents VALUES (NULL, ?, ?)", App::$inst->request->postParams);
        $id = $db->sqlite->lastInsertRowID();
        $response = new RedirectResponse('documents/'.$id);
        $response->Send();
    }

    public function Document(int $id) {
        $doc = Document::GetOne($id);
        if ($doc == null)
            throw new Exception("Document with id {$id} doesn't exist");
    }
}

?>