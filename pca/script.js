// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})

document.addEventListener('DOMContentLoaded', function() {
	const menuItems = document.querySelectorAll('.side-menu li');
	const contentSections = document.querySelectorAll('.content-section');

	menuItems.forEach(item => {
		item.addEventListener('click', function() {
			// Remove 'active' class from all menu items
			menuItems.forEach(item => item.classList.remove('active'));
			// Add 'active' class to the clicked menu item
			this.classList.add('active');

			// Get the content id to be displayed
			const contentId = this.getAttribute('data-content');

			// Hide all content sections
			contentSections.forEach(section => section.style.display = 'none');
			// Show the selected content section
			document.getElementById(contentId).style.display = 'block';
		});
	});

	// By default, show the dashboard content
	document.getElementById('dashboard').style.display = 'block';
});

