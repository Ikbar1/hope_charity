const button = document.querySelector('.menu-toggle');
const nav = document.querySelector('.primary-nav');
if (button && nav) {
    document.documentElement.classList.add('js');
    button.hidden = false;
    const close = () => { nav.classList.remove('open'); button.setAttribute('aria-expanded', 'false'); };
    button.addEventListener('click', () => {
        const open = button.getAttribute('aria-expanded') !== 'true';
        button.setAttribute('aria-expanded', String(open));
        nav.classList.toggle('open', open);
    });
    nav.addEventListener('keydown', event => { if (event.key === 'Escape') { close(); button.focus(); } });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', close));
}
document.querySelectorAll('img').forEach(img => {
    const recover = () => {
        if (img.parentElement.classList.contains('brand') && !img.hidden) {
            const label = document.createElement('span');
            label.textContent = img.alt;
            img.parentElement.append(label);
        }
        img.hidden = true;
        img.parentElement.classList.add('media-unavailable');
    };
    img.addEventListener('error', recover);
    if (img.complete && img.naturalWidth === 0) recover();
});
