var showError = false;

var addressField = document.getElementById('address');
var amountField = document.getElementById('amount');
var donateBtn = document.getElementById('donate-btn');
var amountPattern = /^\d+(\.\d{1,18})?$/;

var successMsg = document.getElementById('success-msg');
var errorMsg = document.getElementById('error-msg');
var infoMsg = document.getElementById('info-msg');

function validateAmount() {
    var amount = amountField.value;
    if (amountPattern.test(amount) && amount > 0) {
        donateBtn.disabled = false;
        amountField.classList.remove('is-invalid');
    } else {
        donateBtn.disabled = true;
        amountField.classList.add('is-invalid');
    }
}
amountField.addEventListener('input', validateAmount);
amountField.addEventListener('change', validateAmount);
amountField.addEventListener('paste', validateAmount);


donateBtn.onclick = function() {
    successMsg.style.display = 'none';
    errorMsg.style.display = 'none';
    infoMsg.style.display = 'none';

    try {
        web3.eth.sendTransaction({
            from: web3.eth.coinbase,
            to: addressField.value,
            value: web3.toWei(amountField.value, 'ether')
        }, function (error, result) {
            if (!error) {
                successMsg.innerHTML = 'Koosi token Sent add Above Contract Address in tokens Tab to view them' 
				+
                    '<a href="https://etherscan.io/tx/' + result + '" target="_blank">View Transaction</a>';
                successMsg.style.display = 'block';
            } else {
                errorMsg.innerHTML = 'Transaction error.' + (showError ? ' ' + error : '');
                errorMsg.style.display = 'block';
            }
        });
    } catch (error) {
        errorMsg.innerHTML = 'It seems, your MetaMask wallet is locked. Please, unlock it.' + (showError ? ' ' + error : '');
        errorMsg.style.display = 'block';
    }
    return false;
};


window.onload = function() {
    if (typeof web3 === 'undefined') {
        new QRCode(document.getElementById("qrcode"), addressField.value);
        infoMsg.style.display = 'block';
    } else {
        amountField.disabled = false;
        amountField.focus();
        donateBtn.disabled = false;
    }
};