function openPopup() {
    const announcementId = document.getElementById('announcement_id');
    if (announcementId) announcementId.value = '';
    
    document.getElementById('announcementTitle').value = '';
    document.getElementById('announcementContent').value = '';
    document.getElementById('day').value = '';
    document.getElementById('month').value = '';
    document.getElementById('year').value = '';
    
    document.getElementById('announcementPopup').style.display = 'block'; // Changed to block for standard modal
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

    document.getElementById('announcementPopup').style.display = 'block'; // Changed to block
}

function openDeleteAnnouncement(id, title) {
    const modal = document.getElementById('deleteModal');
    const inputId = document.getElementById('delete_quest_id'); // Reusing existing modal hidden input
    const titleSpan = document.getElementById('deleteQuestTitle'); // Reusing existing modal title span
    const submitBtn = modal.querySelector('button[name="confirmDelete"]') || modal.querySelector('.cfmdelete');

    if (!modal) return;

    // 1. Inject Data
    if(inputId) {
        inputId.value = id;
        inputId.name = "delete_announcement_id"; // Swap name for PHP announcement handler
    }
    if(titleSpan) titleSpan.innerText = title;
    
    // 2. Switch Button Action
    if(submitBtn) {
        submitBtn.name = "deleteAnnouncement"; // Swap name for PHP announcement handler
    }
    
    modal.style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
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