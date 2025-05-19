// Back to Top Button Show/Hide
window.addEventListener('scroll', function () {
  const backToTop = document.getElementById('backToTop');
  if (window.scrollY > 300) {
    backToTop.classList.add('show');
  } else {
    backToTop.classList.remove('show');
  }
});

// Scroll to top on button click
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Toggle profile dropdown
document.querySelector('.user-icon').addEventListener('click', function () {
  document.querySelector('.profile-dropdown').classList.toggle('active');
});
