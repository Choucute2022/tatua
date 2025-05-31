document.addEventListener('DOMContentLoaded', () => {
    // Dropdown for Gender
    const dropdownSelected = document.querySelector('.dropdown-selected');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const dropdownItems = document.querySelectorAll('.dropdown-item');

    dropdownSelected.addEventListener('click', () => {
        dropdownMenu.classList.toggle('active');
    });

    dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
            dropdownItems.forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
            dropdownSelected.textContent = item.textContent;
            dropdownSelected.innerHTML = item.textContent + ' ▼';
            dropdownMenu.classList.remove('active');
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdownSelected.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('active');
        }
    });

    // Birthday modal logic
    const yearSelect = document.getElementById('year-select');
    const monthSelect = document.getElementById('month-select');
    const daySelect = document.getElementById('day-select');
    const currentYear = new Date().getFullYear();

    // Populate years
    for (let year = currentYear; year >= 1900; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Populate months
    for (let month = 1; month <= 12; month++) {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelect.appendChild(option);
    }

    // Function to update days based on selected month and year
    function updateDays() {
        const year = parseInt(yearSelect.value);
        const month = parseInt(monthSelect.value);

        if (!year || !month) return;

        const daysInMonth = new Date(year, month, 0).getDate();
        daySelect.innerHTML = '';

        for (let day = 1; day <= daysInMonth; day++) {
            const option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            daySelect.appendChild(option);
        }
    }

    // Populate days initially
    updateDays();

    // Update days when year or month changes
    yearSelect.addEventListener('change', updateDays);
    monthSelect.addEventListener('change', updateDays);
});

function openModal() {
    const modal = document.getElementById('address-modal');
    modal.classList.add('active');
}

function closeModal() {
    const modal = document.getElementById('address-modal');
    modal.classList.remove('active');
}

function openBirthdayModal() {
    const modal = document.getElementById('birthday-modal');
    modal.classList.add('active');
}

function closeBirthdayModal() {
    const modal = document.getElementById('birthday-modal');
    modal.classList.remove('active');
}
