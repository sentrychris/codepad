
let form = document.querySelector('#codepad');
form.addEventListener('submit', (e) => {
    e.preventDefault();
    let data = {
        code: btoa(document.querySelector('#code').value),
        ver: btoa(document.querySelector('#ver').value)
    };
    let res = document.querySelector('#result pre');
    res.value = '';
    axios.post('/http/manager.php', {
        data: data,
    })
    .then(response => {
        res.innerHTML = response.data
    })
    .catch(error => {
        console.log(error);
    });
});


var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "application/x-httpd-php",
    theme: "monokai",
    indentUnit: 2,
    indentWithTabs: true
});

editor.setSize('auto', 600);