export function initShare() {
    const shareButtons = document.querySelectorAll('.share-btn');
    
    shareButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const platform = btn.dataset.platform;
            const articleTitle = document.querySelector('.article-title')?.textContent || 'Article';
            const articleUrl = window.location.href;
            
            shareArticle(platform, articleTitle, articleUrl);
        });
    });
}

function shareArticle(platform, title, url) {
    const encodedUrl = encodeURIComponent(url);
    const encodedTitle = encodeURIComponent(title);
    
    switch (platform) {
        case 'twitter':
            simulateShare('Twitter', `https://twitter.com/intent/tweet?text=${encodedTitle}&url=${encodedUrl}`);
            break;
            
        case 'facebook':
            simulateShare('Facebook', `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`);
            break;
            
        case 'linkedin':
            simulateShare('LinkedIn', `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`);
            break;
            
        case 'copy':
            copyToClipboard(url);
            break;
    }
}

function simulateShare(platform, url) {
    // En production, cela ouvrirait réellement la fenêtre de partage
    // window.open(url, '_blank', 'width=600,height=400');
    
    // Pour la simulation, on affiche une notification
    showShareNotification(`Simulation : Partage sur ${platform}`, 'info');
    
    console.log(`Partage sur ${platform}:`, url);
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text)
            .then(() => {
                showShareNotification('Lien copié dans le presse-papier !', 'success');
            })
            .catch(() => {
                fallbackCopyToClipboard(text);
            });
    } else {
        fallbackCopyToClipboard(text);
    }
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showShareNotification('Lien copié dans le presse-papier !', 'success');
    } catch (err) {
        showShareNotification('Erreur lors de la copie', 'error');
    }
    
    document.body.removeChild(textArea);
}

function showShareNotification(message, type = 'info') {
    const notification = document.createElement('div');
    
    const colors = {
        success: 'var(--color-success)',
        error: '#ef4444',
        info: 'var(--color-primary)'
    };
    
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background-color: ${colors[type]};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
    `;
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
