/** @type {HTMLInputElement} */
let editor = document.getElementById('editor');
let delete_doc_btn = document.getElementById('delete_doc');
let confirm_window = document.getElementById('confirmation_window');
let confirm_window_no = document.querySelector('#no');

/** @param {KeyboardEvent} event */
editor.addEventListener('keydown', (event) => {
    if (event.key === 'Tab') {
        event.preventDefault();
        let selectionPos = editor.selectionStart;
        let newVal = editor.value.slice(0, selectionPos) + '    ' + editor.value.slice(selectionPos);
        editor.value = newVal;
        editor.setSelectionRange(selectionPos + 4, selectionPos + 4);
    }
});

delete_doc_btn.addEventListener('click', (event) => {
    confirm_window.classList.remove('hidden');
});

confirm_window_no.addEventListener('click', (event) => {
    confirm_window.classList.add('hidden');
});

