const lightboxState = {
    triggers: [],
    index: 0,
    root: null,
    image: null,
    counter: null,
};

const createLightbox = () => {
    if (lightboxState.root) {
        return;
    }

    const root = document.createElement('div');
    root.className = 'lightbox-backdrop';
    root.setAttribute('role', 'dialog');
    root.setAttribute('aria-modal', 'true');
    root.setAttribute('aria-label', 'Certificate viewer');

    const panel = document.createElement('div');
    panel.className = 'lightbox-panel';

    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'lightbox-control lightbox-close';
    close.textContent = 'Close';

    const image = document.createElement('img');
    image.className = 'lightbox-image';
    image.alt = '';

    const navRow = document.createElement('div');
    navRow.className = 'lightbox-nav-row';

    const previous = document.createElement('button');
    previous.type = 'button';
    previous.className = 'lightbox-control lightbox-nav';
    previous.textContent = '<';
    previous.setAttribute('aria-label', 'Previous image');

    const counter = document.createElement('p');
    counter.className = 'lightbox-counter';

    const next = document.createElement('button');
    next.type = 'button';
    next.className = 'lightbox-control lightbox-nav';
    next.textContent = '>';
    next.setAttribute('aria-label', 'Next image');

    navRow.append(previous, counter, next);
    panel.append(close, image, navRow);
    root.append(panel);
    document.body.append(root);

    root.addEventListener('click', (event) => {
        if (event.target === root) {
            closeLightbox();
        }
    });

    close.addEventListener('click', closeLightbox);
    previous.addEventListener('click', () => moveLightbox(-1));
    next.addEventListener('click', () => moveLightbox(1));

    lightboxState.root = root;
    lightboxState.image = image;
    lightboxState.counter = counter;
};

const openLightbox = (trigger) => {
    createLightbox();

    const gallery = trigger.dataset.lightboxGallery;
    lightboxState.triggers = Array.from(document.querySelectorAll('[data-lightbox-trigger]'))
        .filter((item) => item.dataset.lightboxGallery === gallery);
    lightboxState.index = Math.max(0, lightboxState.triggers.indexOf(trigger));

    updateLightbox();
    lightboxState.root.classList.add('is-open');
    document.body.style.overflow = 'hidden';
};

const updateLightbox = () => {
    const trigger = lightboxState.triggers[lightboxState.index];

    if (!trigger || !lightboxState.image || !lightboxState.counter) {
        return;
    }

    lightboxState.image.src = trigger.dataset.lightboxSrc;
    lightboxState.image.alt = trigger.dataset.lightboxAlt || '';
    lightboxState.counter.textContent = `${lightboxState.index + 1} / ${lightboxState.triggers.length}`;
};

const moveLightbox = (direction) => {
    lightboxState.index = (lightboxState.index + direction + lightboxState.triggers.length) % lightboxState.triggers.length;
    updateLightbox();
};

const closeLightbox = () => {
    if (!lightboxState.root) {
        return;
    }

    lightboxState.root.classList.remove('is-open');
    document.body.style.overflow = '';
};

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-lightbox-trigger]');

    if (!trigger) {
        return;
    }

    openLightbox(trigger);
});

document.addEventListener('keydown', (event) => {
    if (!lightboxState.root?.classList.contains('is-open')) {
        return;
    }

    if (event.key === 'Escape') {
        closeLightbox();
    }

    if (event.key === 'ArrowLeft') {
        moveLightbox(-1);
    }

    if (event.key === 'ArrowRight') {
        moveLightbox(1);
    }
});
