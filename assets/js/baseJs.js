console.log('Ma base javascript');

let option = {
    animation : true,
    autohide : true,
    delay : 12000
};

function seeToast(htmlElement) {
    let toastHtmlElement = document.getElementById(htmlElement);
    let toastElemnt = new bootstrap.Toast(toastHtmlElement, option);
    toastElemnt.show();
}

window.onload = function(event) {
    event = 'connect';
    seeToast(event);
}

seeToast('error');

