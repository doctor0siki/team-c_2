<?php

use Model\Dao\Mypage;
use Slim\Http\Request;
use Slim\Http\Response;

// マイページ (メッセージも受け取るところの想定)
$app->get('/mypage', function (Request $request, Response $response, $args) {
    $this->session->delete('forwarding_path');

    // ログインしてなかったらトップへ
    $user = $this->session->get('user_info');
    if ($user == "") {
        return $response->withRedirect('/');
    }

    $data = [];

    $myp = new Mypage($this->db);

    if(empty($_SERVER['QUERY_STRING'])){ // $_GET['page_id'] はURLに渡された現在のページ数
        $now = 1; // 設定されてない場合は1ページ目にする
    }else{
        $now = $_SERVER['QUERY_STRING'];
    }


    $data["pages"] = $myp->getTalentMasterList($now);
    $data["member"] = $myp->getAllTalentMasterList();
    $data["max"] = ceil( count($data["member"]) / 20 );
    //dd($data["max"]);
    $data["now"] = $now;

    return $this->view->render($response, 'mypage/index.twig', $data);

});
