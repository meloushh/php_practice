/** @type {HTMLInputElement} */
let editor = document.getElementById('editor');

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




let delete_doc_btn = document.getElementById('delete_doc');
if (delete_doc_btn) {
    let confirm_window = document.getElementById('confirmation_window');
    let confirm_window_no = document.querySelector('#no');
    delete_doc_btn.addEventListener('click', (event) => {
        confirm_window.classList.remove('hidden');
    });
    confirm_window_no.addEventListener('click', (event) => {
        confirm_window.classList.add('hidden');
    });
}



let active_doc = document.getElementById('active_doc');
console.log(active_doc);
if (active_doc) {
    active_doc.scrollIntoView({
        behavior: 'instant',
        block: 'center'
    });
}
