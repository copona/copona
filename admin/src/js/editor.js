import '../css/editor.css';

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Underline from '@tiptap/extension-underline';
import Placeholder from '@tiptap/extension-placeholder';

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

    const sourceArea = document.createElement('textarea');
    sourceArea.className = 'form-control tiptap-source-view';
    sourceArea.rows = 12;
    sourceArea.style.display = 'none';

    wrapper.appendChild(toolbar);
    wrapper.appendChild(editorContainer);
    wrapper.appendChild(sourceArea);
    textarea.insertAdjacentElement('afterend', wrapper);
    textarea.style.display = 'none';

    const editor = new Editor({
        element: editorContainer,
        extensions: [
            StarterKit,
            Underline,
            Link.configure({ openOnClick: false, autolink: true }),
            Image.configure({ inline: false }),
            Placeholder.configure({ placeholder }),
        ],
        content: textarea.value || '',
        onUpdate({ editor }) {
            textarea.value = editor.getHTML();
        },
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
            const url = window.prompt('Enter image URL:');
            if (url) editor.chain().focus().setImage({ src: url }).run();
            break;
        }
        case 'source': {
            const isSource = sourceArea.style.display !== 'none';
            if (isSource) {
                editor.commands.setContent(sourceArea.value, false);
                textarea.value = editor.getHTML();
                sourceArea.style.display = 'none';
                editorContainer.style.display = '';
                btn.classList.remove('active');
            } else {
                sourceArea.value = editor.getHTML();
                editorContainer.style.display = 'none';
                sourceArea.style.display = '';
                btn.classList.add('active');
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
