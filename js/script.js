document.addEventListener("DOMContentLoaded", function() {

    // Form validation for Add and Edit forms
    const addForm = document.querySelector(".add-form");
    const editForm = document.querySelector(".edit-form");

    function validateForm(form) {
        form.addEventListener("submit", function(e) {
            const name = form.querySelector("input[name='name']").value.trim();
            const course = form.querySelector("input[name='course']").value.trim();
            const year = form.querySelector("input[name='year_level']").value.trim();
            const email = form.querySelector("input[name='email']").value.trim();

            if (name === "" || course === "" || year === "" || email === "") {
                alert("Please fill out all fields.");
                e.preventDefault();
                return false;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                e.preventDefault();
                return false;
            }

            if (isNaN(year) || year <= 0) {
                alert("Year Level must be a positive number.");
                e.preventDefault();
                return false;
            }
        });
    }

    if (addForm) validateForm(addForm);
    if (editForm) validateForm(editForm);

    // Search functionality
    const searchInput = document.querySelector("#searchInput");
    const table = document.querySelector("table tbody");

    if (searchInput && table) {
        searchInput.addEventListener("keyup", function() {
            const filter = searchInput.value.toLowerCase();
            const rows = table.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                rows[i].style.display = rowText.indexOf(filter) > -1 ? "" : "none";
            }
        });
    }

    // Table sorting functionality
    const headers = document.querySelectorAll("table th");

    headers.forEach((header, index) => {
        header.addEventListener("click", function() {
            const tbody = table;
            const rowsArray = Array.from(tbody.querySelectorAll("tr"));
            const asc = !header.asc;
            header.asc = asc;

            rowsArray.sort((a, b) => {
                const aText = a.children[index].textContent.trim();
                const bText = b.children[index].textContent.trim();

                if (!isNaN(aText) && !isNaN(bText)) {
                    return asc ? aText - bText : bText - aText;
                } else {
                    return asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
                }
            });

            rowsArray.forEach(row => tbody.appendChild(row));
        });
    });

});
