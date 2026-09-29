// Menu mobile. Componentes sao ligados por data-* para o markup dos
// templates-parts e o JS ficarem em sincronia.
document.addEventListener('DOMContentLoaded', () => {
	const toggle = document.querySelector('[data-menu-toggle]');
	const menu = toggle && document.getElementById(toggle.getAttribute('aria-controls'));

	if (!toggle || !menu) return;

	toggle.addEventListener('click', () => {
		const open = menu.classList.toggle('hidden') === false;
		toggle.setAttribute('aria-expanded', String(open));
	});
});
