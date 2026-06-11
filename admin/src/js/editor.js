import '../css/editor.css';

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Underline from '@tiptap/extension-underline';
import Placeholder from '@tiptap/extension-placeholder';
import { basicSetup, EditorView } from 'codemirror';
import { html } from '@codemirror/lang-html';

// Extend Image to persist width, height, and style attributes
const ImageExt = Image.extend({
    addAttributes() {
        return {
            ...this.parent?.(),
            width: {
                default: null,
                parseHTML: el => el.getAttribute('width'),
                renderHTML: attrs => attrs.width ? { width: attrs.width } : {},
            },
            height: {
                default: null,
                parseHTML: el => el.getAttribute('height'),
                renderHTML: attrs => attrs.height ? { height: attrs.height } : {},
            },
        };
    },
});

const FULL_TOOLBAR = `
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="paragraph" title="Paragraph">P</button>
        <button type="button" class="btn btn-default" data-cmd="heading" data-args='{"level":2}' title="Heading 2">H2</button>
        <button type="button" class="btn btn-default" data-cmd="heading" data-args='{"level":3}' title="Heading 3">H3</button>
        <button type="button" class="btn btn-default" data-cmd="heading" data-args='{"level":4}' title="Heading 4">H4</button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="bold" title="Bold"><i class="fa fa-bold"></i></button>
        <button type="button" class="btn btn-default" data-cmd="italic" title="Italic"><i class="fa fa-italic"></i></button>
        <button type="button" class="btn btn-default" data-cmd="underline" title="Underline"><i class="fa fa-underline"></i></button>
        <button type="button" class="btn btn-default" data-cmd="strike" title="Strikethrough"><i class="fa fa-strikethrough"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="link" title="Link"><i class="fa fa-link"></i></button>
        <button type="button" class="btn btn-default" data-cmd="image" title="Insert Image"><i class="fa fa-picture-o"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="bulletList" title="Bullet List"><i class="fa fa-list-ul"></i></button>
        <button type="button" class="btn btn-default" data-cmd="orderedList" title="Ordered List"><i class="fa fa-list-ol"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="blockquote" title="Blockquote"><i class="fa fa-quote-left"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="undo" title="Undo"><i class="fa fa-undo"></i></button>
        <button type="button" class="btn btn-default" data-cmd="redo" title="Redo"><i class="fa fa-repeat"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="source" title="HTML Source"><i class="fa fa-code"></i></button>
    </div>
`;

const BASIC_TOOLBAR = `
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="bold" title="Bold"><i class="fa fa-bold"></i></button>
        <button type="button" class="btn btn-default" data-cmd="italic" title="Italic"><i class="fa fa-italic"></i></button>
        <button type="button" class="btn btn-default" data-cmd="underline" title="Underline"><i class="fa fa-underline"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="link" title="Link"><i class="fa fa-link"></i></button>
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default" data-cmd="bulletList" title="Bullet List"><i class="fa fa-list-ul"></i></button>
        <button type="button" class="btn btn-default" data-cmd="orderedList" title="Ordered List"><i class="fa fa-list-ol"></i></button>
    </div>
`;

const ACTIVE_CMDS = ['bold', 'italic', 'underline', 'strike', 'bulletList', 'orderedList', 'blockquote', 'paragraph', 'heading'];

function initEditor(textarea, toolbarHtml) {
    const placeholder = textarea.getAttribute('placeholder') || '';

    const wrapper = document.createElement('div');
    wrapper.className = 'tiptap-wrapper';

    const toolbar = document.createElement('div');
    toolbar.className = 'tiptap-toolbar btn-toolbar';
    toolbar.setAttribute('role', 'toolbar');
    toolbar.innerHTML = toolbarHtml;

    const editorContainer = document.createElement('div');
    editorContainer.className = 'tiptap-content';

    const sourceArea = document.createElement('div');
    sourceArea.className = 'tiptap-source-view';
    sourceArea.style.display = 'none';
    let cmEditor = null; // lazy-init CodeMirror on first use

    const imgPanel = document.createElement('div');
    imgPanel.className = 'tiptap-img-panel';
    imgPanel.innerHTML =
        '<label>Width: <input type="text" class="form-control form-control-sm img-dim img-width" placeholder="px or %"></label>' +
        '<label>Height: <input type="text" class="form-control form-control-sm img-dim img-height" placeholder="auto"></label>' +
        '<label>Alt text: <input type="text" class="form-control form-control-sm img-alt" placeholder="Description"></label>' +
        '<button type="button" class="btn btn-sm btn-primary img-apply">Apply</button>' +
        '<button type="button" class="btn btn-sm btn-danger img-remove">Remove image</button>';

    wrapper.appendChild(toolbar);
    wrapper.appendChild(editorContainer);
    wrapper.appendChild(imgPanel);
    wrapper.appendChild(sourceArea);
    textarea.insertAdjacentElement('afterend', wrapper);
    textarea.style.display = 'none';

    const editor = new Editor({
        element: editorContainer,
        extensions: [
            StarterKit,
            Underline,
            Link.configure({ openOnClick: false, autolink: true }),
            ImageExt.configure({ inline: false }),
            Placeholder.configure({ placeholder }),
        ],
        content: textarea.value || '',
        onUpdate({ editor }) {
            textarea.value = editor.getHTML();
        },
    });

    // Show image panel when an image is clicked; hide on click elsewhere in editor
    editorContainer.addEventListener('click', function (e) {
        const img = e.target.closest('img');
        if (img) {
            editorContainer.querySelectorAll('img.tiptap-img-selected').forEach(function (el) {
                el.classList.remove('tiptap-img-selected');
            });
            img.classList.add('tiptap-img-selected');
            const attrs = editor.getAttributes('image');
            imgPanel.querySelector('.img-width').value  = attrs.width  || '';
            imgPanel.querySelector('.img-height').value = attrs.height || '';
            imgPanel.querySelector('.img-alt').value    = attrs.alt    || '';
            imgPanel.classList.add('visible');
        } else {
            editorContainer.querySelectorAll('img.tiptap-img-selected').forEach(function (el) {
                el.classList.remove('tiptap-img-selected');
            });
            imgPanel.classList.remove('visible');
        }
    });

    // Hide panel when clicking outside the wrapper
    document.addEventListener('mousedown', function (e) {
        if (!wrapper.contains(e.target)) {
            editorContainer.querySelectorAll('img.tiptap-img-selected').forEach(function (el) {
                el.classList.remove('tiptap-img-selected');
            });
            imgPanel.classList.remove('visible');
        }
    });

    imgPanel.querySelector('.img-apply').addEventListener('click', function () {
        const width  = imgPanel.querySelector('.img-width').value.trim().replace(/px$/i, '') || null;
        const height = imgPanel.querySelector('.img-height').value.trim().replace(/px$/i, '') || null;
        const alt    = imgPanel.querySelector('.img-alt').value || null;
        editor.chain().focus().updateAttributes('image', { width, height, alt }).run();
    });

    imgPanel.querySelector('.img-remove').addEventListener('click', function () {
        editor.chain().focus().deleteSelection().run();
        imgPanel.classList.remove('visible');
    });

    toolbar.addEventListener('mousedown', function (e) {
        const btn = e.target.closest('[data-cmd]');
        if (!btn) return;
        e.preventDefault();

        const cmd = btn.dataset.cmd;
        const args = btn.dataset.args ? JSON.parse(btn.dataset.args) : {};

        switch (cmd) {
        case 'bold':         editor.chain().focus().toggleBold().run(); break;
        case 'italic':       editor.chain().focus().toggleItalic().run(); break;
        case 'underline':    editor.chain().focus().toggleUnderline().run(); break;
        case 'strike':       editor.chain().focus().toggleStrike().run(); break;
        case 'paragraph':    editor.chain().focus().setParagraph().run(); break;
        case 'heading':      editor.chain().focus().toggleHeading(args).run(); break;
        case 'bulletList':   editor.chain().focus().toggleBulletList().run(); break;
        case 'orderedList':  editor.chain().focus().toggleOrderedList().run(); break;
        case 'blockquote':   editor.chain().focus().toggleBlockquote().run(); break;
        case 'undo':         editor.chain().focus().undo().run(); break;
        case 'redo':         editor.chain().focus().redo().run(); break;
        case 'link': {
            const existing = editor.getAttributes('link').href || '';
            const url = window.prompt('Enter URL:', existing);
            if (url === null) break;
            url === ''
                ? editor.chain().focus().unsetLink().run()
                : editor.chain().focus().setLink({ href: url }).run();
            break;
        }
        case 'image': {
            // Reuse or create the hidden input that the filemanager writes into
            let targetInput = document.getElementById('tiptap-image-target');
            if (!targetInput) {
                targetInput = document.createElement('input');
                targetInput.type = 'hidden';
                targetInput.id = 'tiptap-image-target';
                document.body.appendChild(targetInput);
            }
            targetInput.value = '';

            const token = new URLSearchParams(window.location.search).get('token') || '';
            const fmUrl = 'index.php?route=common/filemanager&token=' + token + '&target=tiptap-image-target';

            window.jQuery('#modal-image').remove();

            // Must use jQuery $.ajax + $().append() — jQuery executes <script> tags
            // inside appended HTML, which wires up the filemanager's click handlers.
            // fetch() + innerHTML does NOT execute scripts, breaking folder navigation.
            window.jQuery.ajax({
                url: fmUrl,
                dataType: 'html',
                success: function (html) {
                    window.jQuery('body').append('<div id="modal-image" class="modal" tabindex="-1">' + html + '</div>');
                    const modalEl = document.getElementById('modal-image');
                    const modal = new window.bootstrap.Modal(modalEl);
                    window.jQuery(modalEl).one('hidden.bs.modal', function () {
                        const path = targetInput.value;
                        if (path) {
                            // Root-relative path — no domain, works in any environment
                            const basePath = new URL(window.CATALOG_URL || window.location.href).pathname.replace(/\/$/, '');
                            editor.chain().focus().setImage({ src: basePath + '/image/' + path }).run();
                        }
                        modal.dispose();
                        modalEl.remove();
                    });
                    modal.show();
                }
            });
            break;
        }
        case 'source': {
            const isSource = sourceArea.style.display !== 'none';
            if (isSource) {
                const content = cmEditor ? cmEditor.state.doc.toString() : '';
                editor.commands.setContent(content, false);
                textarea.value = editor.getHTML();
                sourceArea.style.display = 'none';
                editorContainer.style.display = '';
                imgPanel.classList.remove('visible');
                btn.classList.remove('active');
            } else {
                editorContainer.style.display = 'none';
                sourceArea.style.display = '';
                btn.classList.add('active');
                const htmlContent = editor.getHTML();
                if (!cmEditor) {
                    cmEditor = new EditorView({
                        doc: htmlContent,
                        extensions: [basicSetup, html()],
                        parent: sourceArea,
                    });
                } else {
                    cmEditor.dispatch({
                        changes: { from: 0, to: cmEditor.state.doc.length, insert: htmlContent },
                    });
                }
            }
            break;
        }
        }
    });

    function refreshActive() {
        toolbar.querySelectorAll('[data-cmd]').forEach(function (btn) {
            const cmd = btn.dataset.cmd;
            if (!ACTIVE_CMDS.includes(cmd)) return;
            const args = btn.dataset.args ? JSON.parse(btn.dataset.args) : {};
            btn.classList.toggle('active', editor.isActive(cmd, args));
        });
    }

    editor.on('selectionUpdate', refreshActive);
    editor.on('transaction', refreshActive);

    return editor;
}

window.tiptapInstances = {};

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('textarea.ck-full').forEach(function (el) {
        window.tiptapInstances[el.id] = initEditor(el, FULL_TOOLBAR);
    });
    document.querySelectorAll('textarea.ck-basic').forEach(function (el) {
        window.tiptapInstances[el.id] = initEditor(el, BASIC_TOOLBAR);
    });
});
