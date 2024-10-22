function loadContent(section) {
    const contentSection = document.getElementById('content-section');

    // Simulating content loading for different sections
    switch (section) {
        case 'dashboard':
            contentSection.innerHTML = '<h2>Dashboard</h2><p>Welcome to your dashboard!</p>';
            break;
        case 'orders':
            contentSection.innerHTML = '<h2>Current Orders</h2><p>Your current orders will be displayed here.</p>';
            break;
        case 'profile':
            contentSection.innerHTML = '<h2>Profile</h2><p>Your profile information will be displayed here.</p>';
            break;
        case 'notifications':
            contentSection.innerHTML = '<h2>Notifications</h2><p>Your notifications will be displayed here.</p>';
            break;
        default:
            contentSection.innerHTML = '<h2>Welcome</h2><p>Select an option from the sidebar.</p>';
            break;
    }
}
