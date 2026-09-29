// Header mobile panel + submenus of every .nav-menu. Wired through
// data-* and the WP menu classes so the markup in templates-parts and the JS stay in sync.
const desktop = window.matchMedia('(min-width: 64rem)'); // Tailwind lg

const initNavMenu = (menu) => {
	const setItemOpen = (item, open) => {
		item.classList.toggle('is-open', open);
		item.querySelector(':scope > .submenu-toggle')?.setAttribute('aria-expanded', String(open));

		// Closing a branch also closes everything nested inside it.
		if (!open) item.querySelectorAll('.is-open').forEach((child) => setItemOpen(child, false));

		syncPanelHeights();
	};

	// One open branch per level.
	const closeSiblings = (item) => {
		for (const sibling of item.parentElement.children) {
			if (sibling !== item && sibling.classList.contains('is-open')) setItemOpen(sibling, false);
		}
	};

	const closeAll = () => menu.querySelectorAll(':scope > .is-open').forEach((item) => setItemOpen(item, false));

	// Desktop: a panel grows to the height of its open flyout so both read as one block.
	// min-height below the content height has no effect, so no comparison is needed.
	const syncPanelHeights = () => {
		menu.querySelectorAll('.sub-menu').forEach((panel) => {
			panel.style.minHeight = '';

			const flyout = desktop.matches && panel.querySelector(':scope > li:is(:hover, .is-open) > .sub-menu');
			if (!flyout) return;

			// Measure the flyout's own content, without the min-height it inherits from the panel.
			flyout.style.minHeight = '0px';
			panel.style.minHeight = `${flyout.offsetHeight}px`;
			flyout.style.minHeight = '';
		});
	};

	menu.addEventListener('click', (event) => {
		const button = event.target.closest('.submenu-toggle');
		const link = event.target.closest('.menu-item-has-children > a');
		// Placeholder parents ("#" custom links) toggle their submenu instead of navigating.
		const isPlaceholder = link && ['', '#'].includes(link.getAttribute('href') ?? '');

		if (!button && !isPlaceholder) return;

		event.preventDefault();
		const item = (button || link).parentElement;
		const open = !item.classList.contains('is-open');
		closeSiblings(item);
		setItemOpen(item, open);
	});

	// Desktop: hovering another branch closes the one left open by click.
	menu.addEventListener('mouseover', (event) => {
		const item = desktop.matches && event.target.closest('li');
		if (item) closeSiblings(item);
		syncPanelHeights();
	});

	menu.addEventListener('mouseleave', syncPanelHeights);

	// Desktop: close dropdowns when clicking or tabbing out of them.
	document.addEventListener('click', (event) => {
		if (desktop.matches && !menu.contains(event.target)) closeAll();
	});

	menu.addEventListener('focusout', (event) => {
		if (!desktop.matches) return;
		menu.querySelectorAll('.is-open').forEach((item) => {
			if (!item.contains(event.relatedTarget)) setItemOpen(item, false);
		});
	});

	// Escape closes the innermost open submenu around the focus. preventDefault tells the
	// mobile panel handler the key was already used.
	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape' || event.defaultPrevented) return;

		const item = document.activeElement?.closest('.is-open');

		if (item && menu.contains(item)) {
			event.preventDefault();
			setItemOpen(item, false);
			item.querySelector(':scope > .submenu-toggle')?.focus();
		} else if (desktop.matches) {
			closeAll();
		}
	});

	// Reset when crossing the breakpoint (accordion state makes no sense as dropdowns).
	desktop.addEventListener('change', () => {
		closeAll();
		syncPanelHeights();
	});
};

const initMobilePanel = () => {
	const toggle = document.querySelector('[data-menu-toggle]');
	const panel = toggle && document.getElementById(toggle.getAttribute('aria-controls'));

	if (!panel) return;

	const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

	const setOpen = (open) => {
		panel.classList.toggle('hidden', !open);
		toggle.setAttribute('aria-expanded', String(open));
	};

	toggle.addEventListener('click', () => setOpen(!isOpen()));

	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape' || desktop.matches || !isOpen()) return;
		// Leave it to the submenu handler when focus is inside an open submenu.
		if (event.defaultPrevented || document.activeElement?.closest('.nav-menu .is-open')) return;

		setOpen(false);
		toggle.focus();
	});

	desktop.addEventListener('change', () => setOpen(false));
};

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.nav-menu').forEach(initNavMenu);
	initMobilePanel();
});
