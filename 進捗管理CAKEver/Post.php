<?php
    class Post extends AppModel{
        //public $hasMany = "Comment";
        public $validate = array( //DBと合わせる
            'title' => array(
                'rule' => 'notBlank' //空のチェックを行う
            ),
            'pages' => array(
                'rule' => 'notBlank'
            ),
            'current_page' => array(
                'rule' => 'notBlank'
            ),
            'fin_date' => array(
                'rule' => 'notBlank'
            )
        );

    }

?>