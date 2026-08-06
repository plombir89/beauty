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

const paypalFeedbackClasses = {
    success: ['border-emerald-200', 'bg-emerald-50', 'text-emerald-800'],
    error: ['border-blush-200', 'bg-blush-50', 'text-blush-500'],
    cancelled: ['border-sand-200', 'bg-sand-50', 'text-ink-soft'],
};

const buildPayPalScriptUrl = (root) => {
    const params = new URLSearchParams({
        'client-id': root.dataset.clientId,
        components: 'buttons',
        currency: root.dataset.currency || 'USD',
        intent: 'capture',
    });

    if (root.dataset.locale) {
        params.set('locale', root.dataset.locale);
    }

    return `https://www.paypal.com/sdk/js?${params.toString()}`;
};

const loadPayPalScript = (root) => {
    if (window.paypal?.Buttons) {
        return Promise.resolve();
    }

    if (window.paypalDepositScript) {
        return window.paypalDepositScript;
    }

    window.paypalDepositScript = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = buildPayPalScriptUrl(root);
        script.async = true;
        script.addEventListener('load', resolve);
        script.addEventListener('error', reject);
        document.head.append(script);
    });

    return window.paypalDepositScript;
};

const hidePayPalLoading = (root) => {
    root.querySelector('[data-paypal-loading]')?.classList.add('hidden');
};

const showPayPalUnavailable = (root) => {
    hidePayPalLoading(root);
    root.querySelector('[data-paypal-buttons]')?.classList.add('hidden');
    root.querySelector('[data-paypal-unavailable]')?.classList.remove('hidden');
};

const showPayPalFeedback = (root, status, payerName = '') => {
    const feedback = root.parentElement?.querySelector('[data-paypal-feedback]');

    if (!feedback) {
        return;
    }

    Object.values(paypalFeedbackClasses).flat().forEach((className) => feedback.classList.remove(className));
    feedback.classList.remove('hidden');
    feedback.classList.add('flex', ...paypalFeedbackClasses[status]);

    const template = root.dataset[`${status}Template`] || '';
    const name = payerName ? `, ${payerName}` : '';
    feedback.textContent = template.replace(':name', name);
};

const initializePayPalDeposit = (root) => {
    if (root.dataset.paypalInitialized === 'true') {
        return;
    }

    root.dataset.paypalInitialized = 'true';

    if (!root.dataset.clientId || Number(root.dataset.amount) <= 0) {
        showPayPalUnavailable(root);

        return;
    }

    loadPayPalScript(root)
        .then(() => {
            const buttonsContainer = root.querySelector('[data-paypal-buttons]');

            if (!window.paypal?.Buttons || !buttonsContainer) {
                showPayPalUnavailable(root);

                return;
            }

            hidePayPalLoading(root);
            buttonsContainer.classList.remove('hidden');

            window.paypal.Buttons({
                style: { layout: 'vertical', shape: 'pill', color: 'gold' },
                createOrder: (_, actions) => actions.order.create({
                    intent: 'CAPTURE',
                    purchase_units: [
                        {
                            description: root.dataset.description,
                            amount: {
                                value: root.dataset.amount,
                                currency_code: root.dataset.currency || 'USD',
                            },
                        },
                    ],
                }),
                onApprove: async (_, actions) => {
                    const details = await actions.order?.capture();
                    const payerName = details?.payment_source?.paypal?.name?.given_name
                        || details?.payer?.name?.given_name
                        || '';

                    showPayPalFeedback(root, 'success', payerName);
                },
                onCancel: () => showPayPalFeedback(root, 'cancelled'),
                onError: (error) => {
                    console.error('PayPal deposit failed', error);
                    showPayPalFeedback(root, 'error');
                },
            }).render(buttonsContainer);
        })
        .catch(() => showPayPalUnavailable(root));
};

document.querySelectorAll('[data-paypal-deposit]').forEach(initializePayPalDeposit);
