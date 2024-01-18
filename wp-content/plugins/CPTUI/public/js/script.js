const slider = document.querySelector('.cptui-slider');

function activate(e) {
    const items = document.querySelectorAll('.cptui-item');
    e.target.matches('.next') && slider.append(items[0])
    e.target.matches('.prev') && slider.prepend(items[items.length - 1]);
}

document.addEventListener('click', activate, false);