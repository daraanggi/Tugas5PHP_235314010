document.addEventListener('DOMContentLoaded', () => {
    console.log("Halaman To-Do List siap!");
});

function togglePassword() {
    const passwordField = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        // Password terlihat, jadi tampilkan ikon "mata terbuka"
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        // Password disembunyikan, jadi tampilkan ikon "mata tertutup"
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    }
}

