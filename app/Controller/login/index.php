<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Model\Dao\Usr;

// ログイン画面コントローラ
$app->get('/login/', function (Request $request, Response $response) {

    // ログインしてたらマイページへ
    $user = $this->session->get('user_info');
    if ($user != "") {
        return $response->withRedirect('/mypage');
    }

    //GETされた内容を取得します。
    $data = $request->getQueryParams();

    // Render index view
    return $this->view->render($response, 'login/login.twig', $data);

});

// ログインロジックコントローラ
$app->post('/login/', function (Request $request, Response $response) {

    // ログインしてたらマイページへ
    $user = $this->session->get('user_info');
    if ($user != "") {
        return $response->withRedirect('/mypage');
    }

    //POSTされた内容を取得します
    $data = $request->getParsedBody();

    //ユーザーDAOをインスタンス化
    $user = new Usr($this->db);

    $param["mailAddress"] = $data["email"];
    $param["password"] = $data["password"];

    //入力された情報から会員情報を取得
    $result = $user->select($param, "", "", 1,false);

    //結果が取得できたらログイン処理を行い、TOPへリダイレクト
    if($result){

        //セッションにユーザー情報を登録
        $this->session->set('user_info', $result);

        $forwarding_path = $this->session->get("forwarding_path");
        //TOPへリダイレクト
        return $response->withRedirect($forwarding_path != "" ? $forwarding_path : "/");

    } else {
        //入力項目がマッチしない場合エラーを出す
        $data["error"] = "ユーザー名かパスワードが間違っています";
    }

    // Render index view
    return $this->view->render($response, 'login/login.twig', $data);

});
