<script type="text/javascript">
<?php if ($text_version) { // If is logged in        ?>
        $('.ck-full').each(function () {
            CKEDITOR.replace($(this).attr('id'), {
                filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $this->session->data['token']; ?>',
                filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $this->session->data['token']; ?>',
                filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $this->session->data['token']; ?>',
                toolbar: 'Full'
            });
        });

        $('.ck-basic').each(function () {
            CKEDITOR.replace($(this).attr('id'), {
                filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $this->session->data['token']; ?>',
                filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $this->session->data['token']; ?>',
                filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $this->session->data['token']; ?>',
                toolbar: 'Basic',
                height: '100px'
            });
        });

        CKEDITOR.on('dialogDefinition', function (event)
        {
            var editor = event.editor;
            var dialogDefinition = event.data.definition;
            var dialogName = event.data.name;

            var tabCount = dialogDefinition.contents.length;
            for (var i = 0; i < tabCount; i++) {
                var browseButton = dialogDefinition.contents[i].get('browse');

                if (browseButton !== null) {
                    browseButton.hidden = false;
                    browseButton.onClick = function () {
                        $('#modal-image').remove();
                        $.ajax({
                            url: 'index.php?route=common/filemanager&token=<?php echo $this->session->data['token']; ?>&ckedialog=' + this.filebrowser.target,
                            dataType: 'html',
                            success: function (html) {
                                $('body').append('<div id="modal-image" style="z-index: 10020;" class="modal">' + html + '</div>');
                                $('#modal-image').modal('show');
                            }
                        });
                    }
                }
            }
        });

    <?php
}
?>
</script>

<footer id="footer"><?php echo $text_footer; ?><br /><?php echo $text_version; ?></footer></div>
</body></html>
