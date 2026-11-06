<?php $template->fill('content') ?>

<?php foreach($entries as $entry): ?>
<?php $template->insert('entry/template_print_entry', compact('entry')) ?>
<div class="pagebreak"></div>
<?php endforeach ?>

<?php $template->stop() ?>
<?php $template->expand('printables/label') ?>