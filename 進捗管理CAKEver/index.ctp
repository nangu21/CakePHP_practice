<h2>Add</h2>
<?php echo $this->Html->link('参考書を追加する', array('controller'=>'posts', 'action'=>'add'));?>

<h2>Progress</h2>
<ul>
<?php foreach ($posts as $post) : ?>
<li id="post_<?php echo h($post['Post']['id']); ?>">
<?php
    //debug($post); //cake版print_r()
    //残りの日数を計算する
    $left = floor((strtotime($post['Post']['fin_date']) - time()) / (60*60*24));
    //進捗割合を計算する
    //$progress = $post['Post']['current_page'] / $post['Post']['pages'] *100;

    //postの一覧をつくる
    //echo h($post['Post']['title']); //cake版htmlspecialchars()
    echo $this->Html->link('あと'.$left.'日: '.$post['Post']['title'],'/posts/view/'. $post['Post']['id']); //.round($progress, 2).'%'
    //パスの書き方：相対、絶対(外部リンク)、配列(array("controller"=>"posts", "action"=>""view, $post['Post']['id']))
    //round()で少数第２位まで表示
?>

<?php 
    //編集
    echo $this->Html->link('編集',array('action'=>'edit', $post['Post']['id']));
?>

<?php
    //削除
    //echo $this->Form->postLink('削除',array('action'=>'delete',$post['Post']['id']),array('confirm'=>'sure?'));
    echo $this->Html->link('削除', '#', array('class'=>'delete','data-post-id'=>$post['Post']['id']));
?>

</li>
<?php endforeach; ?>
</ul>

<script>
$(function(){
    $('a.delete').click(function(e){
        if(confirm('sure?')){
            $.post('/MyProject/posts/delete/'+$(this).data('post-id'),{},function(res){
                $('#post_'+res.id).fadeOut();
            }, "json");
        }
        return false;
    });
});
</script>

<script src="/package/dist/Chart.js"></script>
<canvas id="canvas"></canvas>
<script>
    var ctx = document.getElementById("canvas").getContext("2d");
    var myBar = new Chart(ctx, {
        type: 'horizontalBar',                           //◆棒グラフ
        data: {                                //◆データ
            labels: [
            <?php $n=0; ?>
            <?php foreach($posts as $post): ?>
                <?php echo "'". h($post['Post']['title']). "'" . "," ?>
                <?php $n++;?>
            <?php endforeach ?>
            ],     //ラベル名
            datasets: [{                       //データ設定
                data: [
                <?php foreach($posts as $post): ?>
                    <?php echo h($post['Post']['current_page'] / $post['Post']['pages'] *100). ","?>
                <?php endforeach ?>
                ],          //データ内容
                //backgroundColor: ['#F97F51', '#25CCF7', '#D6A2E8', '#58B19F', '#FC427B', '#EAB543']   //背景色
                backgroundColor: [
                <?php for($i=0;$i<=$n;$i++){ ?>
                    <?php if($i<$n){ ?>
                        '<?php echo sprintf("#%06x",rand(0x000000, 0xFFFFFF)); ?>',
                <?php }else if($i==$n){ ?>
                    '<?php echo sprintf("#%06x",rand(0x000000, 0xFFFFFF)); ?>'
                <?php }} ?>
                ]
            }]
        },
        options: {                             //◆オプション
            responsive: true,                  //グラフ自動設定
            legend: {                          //凡例設定
                display: false                 //表示設定
        },
            title: {                           //タイトル設定
                display: true,                 //表示設定
                fontSize: 18,                  //フォントサイズ
                text: '進捗状況'                //ラベル
            },
            scales: {                          //軸設定
                yAxes: [{                      //y軸設定
                    display: true,             //表示設定
                    barPercentage: 0.4,           //棒グラフ幅
                    categoryPercentage: 0.4,      //棒グラフ幅
                    scaleLabel: {              //軸ラベル設定
                       display: true,          //表示設定
                     //  labelString: '縦軸',  //ラベル
                       fontSize: 18               //フォントサイズ
                    },
                    ticks: {                      
                        fontSize: 15            //フォントサイズ
                    },
                }],
                xAxes: [{                         //x軸設定
                    display: true,                //表示設定
                    
                    scaleLabel: {                 //軸ラベル設定
                       display: true,             //表示設定
                       //labelString: '横軸',  //ラベル
                       fontSize: 18               //フォントサイズ
                    },
                    ticks: {		//最大値最小値設定
                    	min: 0,                   //最小値
                        max: 100,                  //最大値
                        fontSize: 15,           //フォントサイズ
                        stepSize: 25               //軸間隔
                    },
                }],
            },
            layout: {                             //レイアウト
                padding: {                          //余白設定
                    left: 0,
                    right: 50,
                    top: 50,
                    bottom: 50
                }
            }
        }
    });
</script>