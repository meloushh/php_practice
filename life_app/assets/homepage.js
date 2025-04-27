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