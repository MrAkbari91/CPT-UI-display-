function screenshotsAccordion() {
    // Screenshots accordion
    const panels = document.querySelectorAll('.feature-row');
    let openedPanel = document.querySelector('.open-feature');

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function toggleOpen() {
        let currentPanel = document.querySelector('.open-feature');

        if (!this.classList.contains('open-feature')) {
            if (openedPanel) openedPanel.classList.remove('open-feature');
            this.classList.add('open-feature');
            openedPanel = this;

            let featureImg = openedPanel.getAttribute('data-feature-img');
            let featureSkin = openedPanel.getAttribute('data-feature-skin');
            let featureIcon = openedPanel.getAttribute('data-feature-icon');

            let screenshots = document.querySelectorAll('.screenshot');
            let widget = document.querySelector('#home-features');
            let accent = document.querySelector('.bottom-accent');
            let accents = document.querySelectorAll('.bottom-accent');

            screenshots.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateX(20%)';
            });

            if (typeof featureSkin !== 'undefined' && featureSkin !== null) {
                accent.style.transform = 'scale(0)';
                await sleep(360);

                screenshots.forEach(el => {
                    el.style.backgroundImage = 'url(' + featureImg + ')';
                });

                accents.forEach(es => {
                    es.style.backgroundImage = 'url(' + featureIcon + ')';
                });

                await sleep(360);
                widget.className = featureSkin + ' buddy-' + featureSkin;

                screenshots.forEach(el => {
                    el.style.opacity = 1;
                    el.style.transform = 'none';
                });

                accent.style.opacity = 1;
                accent.style.transform = 'none';
            } else {
                await sleep(360);

                screenshots.forEach(el => {
                    el.style.backgroundImage = 'url(' + featureImg + ')';
                });

                accents.forEach(es => {
                    es.style.backgroundImage = 'url(' + featureIcon + ')';
                });

                await sleep(360);

                screenshots.forEach(el => {
                    el.style.opacity = 1;
                    el.style.transform = 'none';
                });
            }
        }
    }

    panels.forEach(panel => panel.addEventListener('click', toggleOpen));
}

document.addEventListener('DOMContentLoaded', function (e) {
    screenshotsAccordion();
});
