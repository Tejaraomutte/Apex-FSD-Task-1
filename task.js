const skills = ["HTML5", "CSS3", "JavaScript", "PHP", "MySQL"];

function showMessage() {
    const target = document.getElementById("dynamicMessage");
    target.textContent = "JavaScript is active: click, keyup, and change events are now wired.";
}

function renderSkills() {
    const list = document.getElementById("skillsList");
    list.innerHTML = "";

    for (let i = 0; i < skills.length; i += 1) {
        const item = document.createElement("li");
        item.textContent = `${i + 1}. ${skills[i]}`;
        list.appendChild(item);
    }
}

function validateEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}

function validateForm() {
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const role = document.getElementById("role").value;
    const gender = document.querySelector('input[name="gender"]:checked');
    const message = document.getElementById("validationMessage");

    if (!name || !email || !password || !role || !gender) {
        message.textContent = "All required fields must be completed.";
        return false;
    }

    if (!validateEmail(email)) {
        message.textContent = "Please enter a valid email address.";
        return false;
    }

    if (password.length < 8) {
        message.textContent = "Password must be at least 8 characters long.";
        return false;
    }

    message.textContent = "";
    return true;
}

function initEvents() {
    const demoButton = document.getElementById("jsButton");
    const nameInput = document.getElementById("name");
    const roleSelect = document.getElementById("role");

    demoButton.addEventListener("click", showMessage);

    nameInput.addEventListener("keyup", (event) => {
        const info = document.getElementById("dynamicMessage");
        info.textContent = `Typing detected: ${event.target.value || "(empty)"}`;
    });

    roleSelect.addEventListener("change", (event) => {
        const info = document.getElementById("dynamicMessage");
        info.textContent = `Selected role: ${event.target.value || "none"}`;
    });
}

function initPortfolioApp() {
    renderSkills();
    initEvents();
}

document.addEventListener("DOMContentLoaded", initPortfolioApp);