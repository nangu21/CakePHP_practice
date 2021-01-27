<?php
    class CategoriesController extends AppController{
        //public $scaffold; //一覧が表示される
        public $uses=array("Category","Post","CategoriesPost"); //controllerの中でこのmodelにアクセスできるようにする

        public $helpers = array('Html', 'Form'); //選択的読み込みでメモリ消費を抑える
        //Viewのなかで$this->Html, $this->Formが使えるようになる

        public function index(){ //カテゴリ記事一覧
    /*
            $params = array(
                'order' => 'modified desc',
                'limit' => 2
            );
    */
     //       $this->set('posts', $this->Post->find('all', $params)); //find:postから記事を引っ張ってくる(第一引数で設定した変数に第二引数が渡される)
            $this->set('categories', $this->Category->find('all'));
            $this->set('title_for_layout', 'カテゴリ一覧');

            $this->set('post_c', $this->CategoriesPost->find('all'));
            $this->set('post_title', $this->Post->find('all'));
        }

       // public function view($id = null){ //idがなかったときにエラーが出ないよう処理
       //     $this->Category->id = $id;
        //    $this->set('category', $this->Category->read());
     //   }

        public function add(){
            if($this->request->is('post')){//データがpostされたときの処理
                //$this->request->dataはフォームの値
                if($this->Category->save($this->request->data)){//保存処理
                    $this->Session->setFlash('Success!');//保存成功
                    $this->redirect(array('controller'=>'categories','action'=>'index'));//成功した場合、記事一覧に画面遷移する
                }else{
                    $this->Session->setFlash('failed!');//保存失敗
                }
            }
        }

        public function edit($id = null){
            $this->Category->id = $id; //読み込むものを指定
            if ($this->request->is('get')){ //リンクまたはurl直入力で来た場合
                $this->request->data = $this->Category->read(); //formの各inputの値に指定されたidのデータをreadしたものをセット
            }else{ //フォーム入力があった場合
                if ($this->Category->save($this->request->data)){
                    $this->Session->setFlash('success!');
                    $this->redirect(array('controller'=>'categories','action' => 'index'));
                }else{
                    $this->Session->setFlash('failed!');
                }
            }
        }

        public function delete($id){
            if($this->request->is('get')){
                throw new MethodNotAllowedException();
            }
            /* if($this->Post->delete($id)){
                $this->Session->setFlash('Deleted!');
                $this->redirect(array('action'=>'index'));
            }*/
            if($this->request->is('ajax')){
                $conditions = array("category_id"=>$id);
                $posts = $this->CategoriesPost->find("first",array("conditions"=>$conditions));

                if(!empty($posts)){
                    //1件もなければ通過
                    exit(); //本来ならエラーを出すべきだが省略
                }//あとで1件もなければ削除を表示しないように修正

                if($this->Category->delete($id)){
                    $this->autoRender = false;
                    $this->autoLayout = false;
                    $response = array('id' => $id);
                    $this->header('Content-Type: application/json');
                    echo json_encode($response);
                    exit();
                }
            }
            $this->redirect(array('controller'=>'categories','action'=>'index'));
        }

    }
?>
