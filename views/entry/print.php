<?php $template->fill('content') ?>
<?php $template->insert('entry/template_print_entry', compact('entry')) ?>
<?php $template->stop() ?>
<?php $template->expand('printables/label') ?>