const toggleFormLinks = document.querySelectorAll('.toggle-form');
const signInForm = document.getElementById('signin-form');
const signUpForm = document.getElementById('signup-form');
const pcaUserSelect = document.getElementById('pca-user');
const aboutMeText = document.getElementById('about-me');

toggleFormLinks.forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    signInForm.classList.toggle('hidden');
    signUpForm.classList.toggle('hidden');
  });
});

pcaUserSelect.addEventListener('change', (e) => {
  if (e.target.value === 'yes') {
    aboutMeText.textContent = 'About me: PCA';
  } else {
    aboutMeText.textContent = 'About me: User';
  }
});
