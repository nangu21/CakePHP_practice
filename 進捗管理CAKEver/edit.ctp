<h2>Edit Post</h2>

<?php
echo $this->Form->create('Post');
echo $this->Form->input('title');
echo $this->Form->input('pages');
echo $this->Form->input('current_page');
echo $this->Form->input('fin_date');
echo $this->Form->end('save!');
?>