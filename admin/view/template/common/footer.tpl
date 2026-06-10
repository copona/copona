<script type="text/javascript">
<?php if ($text_version) { ?>
    var cke5Instances = {};

    $('.ck-full').each(function () {
        var el = this;
        ClassicEditor.create(el, {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'imageInsert', 'mediaEmbed', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'htmlEmbed', 'sourceEditing', '|',
                    'undo', 'redo'
                ]
            }
        }).then(function (editor) {
            cke5Instances[el.id] = editor;
            editor.model.document.on('change:data', function () {
                el.value = editor.getData();
            });
        }).catch(function (err) {
            console.error('CKEditor5 error:', err);
        });
    });

    $('.ck-basic').each(function () {
        var el = this;
        ClassicEditor.create(el, {
            toolbar: {
                items: ['bold', 'italic', 'link', '|', 'bulletedList', 'numberedList']
            }
        }).then(function (editor) {
            cke5Instances[el.id] = editor;
            editor.model.document.on('change:data', function () {
                el.value = editor.getData();
            });
        }).catch(function (err) {
            console.error('CKEditor5 error:', err);
        });
    });
<?php } ?>
</script>

<footer id="footer"><?php echo $text_footer; ?><br /><?php echo $text_version; ?></footer></div>
</body></html>
