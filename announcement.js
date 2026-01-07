// Announcement Popup Functions

function openPopup() {
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

    if (!day || !year || !title || !content) {
        alert('Please fill in all fields');
        return;
    }

    const monthNames = {
        January: '01', February: '02', March: '03', April: '04',
        May: '05', June: '06', July: '07', August: '08',
        September: '09', October: '10', November: '11', December: '12'
    }

    // Here you would typically save the announcement
    console.log('Announcement posted:', { day, month, year, title, content });
    
    // Clear form
    document.getElementById('day').value = '';
    document.getElementById('year').value = '';
    document.getElementById('announcementTitle').value = '';
    document.getElementById('announcementContent').value = '';
    
    closePopup();
    alert('Announcement posted successfully!');
}

// Close popup when clicking outside the content
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('announcementPopup').addEventListener('click', function(e) {
        if (e.target === this) {
            closePopup();
        }
    });
});