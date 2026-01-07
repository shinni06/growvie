function openPopup() {
    const announcementId = document.getElementById('announcement_id');
    if (announcementId) announcementId.value = '';
    
    document.getElementById('announcementTitle').value = '';
    document.getElementById('announcementContent').value = '';
    document.getElementById('day').value = '';
    document.getElementById('month').value = '';
    document.getElementById('year').value = '';
    
    document.getElementById('announcementPopup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('announcementPopup').style.display = 'none';
}

function postAnnouncement() {
    const day = document.getElementById('day').value;
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    const title = document.getElementById('announcementTitle').value;
    const content = document.getElementById('announcementContent').value;

    if (!day || !month || !year) {
        alert('Please fill in all date fields');
        return false;
    }

    if (!title || !content) {
        alert('Please fill in title and content');
        return false;
    }

    const monthNames = {
        'January': '01', 'February': '02', 'March': '03', 'April': '04',
        'May': '05', 'June': '06', 'July': '07', 'August': '08',
        'September': '09', 'October': '10', 'November': '11', 'December': '12'
    };

    const monthNum = monthNames[month];
    const formattedDay = String(day).padStart(2, '0');

    const dateString = `${year}-${monthNum}-${formattedDay}`;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';

    const fields = {
        'postAnnouncement': '1',
        'announcement_id': document.getElementById('announcement_id')?.value || '',
        'announce_title': title,
        'announce_body': content,
        'announce_schedule_date': dateString
    };

    for (const [name, value] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function openEditAnnouncement(a) {
    let announcementIdField = document.getElementById('announcement_id');
    if (!announcementIdField) {
        announcementIdField = document.createElement('input');
        announcementIdField.type = 'hidden';
        announcementIdField.id = 'announcement_id';
        document.body.appendChild(announcementIdField);
    }
    announcementIdField.value = a.announcement_id;

    document.getElementById('announcementTitle').value = a.announce_title;
    document.getElementById('announcementContent').value = a.announce_body;

    const d = new Date(a.announce_schedule_date);
    document.getElementById('day').value = d.getDate();

    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('month').value = monthNames[d.getMonth()];
    document.getElementById('year').value = d.getFullYear();

    document.getElementById('announcementPopup').style.display = 'flex';
}

function openDeleteAnnouncement(id, title) {
    let deleteModal = document.getElementById('deleteModal');
    if (!deleteModal) {
        createDeleteModal();
        deleteModal = document.getElementById('deleteModal');
    }

    let deleteIdField = document.getElementById('delete_announcement_id');
    if (!deleteIdField) {
        deleteIdField = document.createElement('input');
        deleteIdField.type = 'hidden';
        deleteIdField.id = 'delete_announcement_id';
        document.body.appendChild(deleteIdField);
    }
    deleteIdField.value = id;

    document.getElementById('deleteAnnouncementTitle').innerText = title;
    deleteModal.style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

function confirmDelete() {
    const id = document.getElementById('delete_announcement_id').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';

    const deleteInput = document.createElement('input');
    deleteInput.type = 'hidden';
    deleteInput.name = 'deleteAnnouncement';
    deleteInput.value = '1';
    form.appendChild(deleteInput);

    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'delete_announcement_id';
    idInput.value = id;
    form.appendChild(idInput);

    document.body.appendChild(form);
    form.submit();
}

function createDeleteModal() {
    const modalHTML = `
        <div id="deleteModal" class="popup-overlay" style="display: none;">
            <div class="popup-content">
                <h3 class="popup-title">Delete Announcement</h3>
                <p>Are you sure you want to delete "<span id="deleteAnnouncementTitle"></span>"?</p>
                <div class="popup-buttons">
                    <button type="button" class="btn-announcement-cancel" onclick="closeDeleteModal()">Cancel</button>
                    <button type="button" class="btn-announcement-delete" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

window.onclick = function(event) {
    const announcementPopup = document.getElementById('announcementPopup');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === announcementPopup) {
        closePopup();
    }
    if (deleteModal && event.target === deleteModal) {
        closeDeleteModal();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('announcement_id')) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'announcement_id';
        document.body.appendChild(input);
    }
});