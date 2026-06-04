document.getElementById("loginToggle").addEventListener("click", function() {
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("signupForm").style.display = "none";
    this.classList.add("active");
    document.getElementById("signupToggle").classList.remove("active");
});

document.getElementById("signupToggle").addEventListener("click", function() {
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("loginForm").style.display = "none";
    this.classList.add("active");
    document.getElementById("loginToggle").classList.remove("active");
});

document.getElementById("showSignup").addEventListener("click", function(event) {
    event.preventDefault();
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("loginForm").style.display = "none";
});

document.getElementById("showLogin").addEventListener("click", function(event) {
    event.preventDefault();
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("signupForm").style.display = "none";
});
