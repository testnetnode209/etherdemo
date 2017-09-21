var accountField = document.getElementById('account');
var copyBtn = document.getElementById('copy-btn');
var accountPattern = /^((0x)|(0X))[0-9a-fA-F]{40}$/;


function enableCopyButton() {
    var account = accountField.value;

    if (!account) {
        copyBtn.disabled = true;
        accountField.classList.remove('is-invalid');
    } else if (accountPattern.test(account)) {
        copyBtn.disabled = false;
        accountField.classList.remove('is-invalid');
    } else {
        copyBtn.disabled = true;
        accountField.classList.add('is-invalid');
    }
}
accountField.addEventListener('input', enableCopyButton);
accountField.addEventListener('change', enableCopyButton);
accountField.addEventListener('paste', enableCopyButton);


document.getElementById('setup-example-account').onclick = function() {
    accountField.value = this.text;
    accountField.dispatchEvent(new Event('change'));
    return false;
};


var clipboard = new Clipboard(copyBtn, {
    text: function() {
        return document.getElementById('app-url').value + accountField.value;
    }
});
clipboard.on('success', function() {
    alert('Link has been copied to clipboard. Place it on your website.');
});


window.onload = function() {
    accountField.focus();
};