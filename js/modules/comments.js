const COMMENTS_KEY = 'blog-comments';

export function initComments(articleId) {
    const commentForm = document.getElementById('commentForm');
    const commentsList = document.getElementById('commentsList');
    const commentsCount = document.getElementById('commentsCount');
    
    if (!commentForm || !commentsList) return;
    
    loadComments(articleId, commentsList, commentsCount);
    
    commentForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const name = document.getElementById('commentName').value.trim();
        const text = document.getElementById('commentText').value.trim();
        
        if (!name || !text) return;
        
        const comment = {
            id: Date.now(),
            articleId: articleId,
            name: name,
            text: text,
            date: new Date().toISOString()
        };
        
        saveComment(comment);
        addCommentToDOM(comment, commentsList);
        updateCommentsCount(articleId, commentsCount);
        
        commentForm.reset();
        
        showNotification('Commentaire publié avec succès !');
    });
}

function saveComment(comment) {
    const comments = getAllComments();
    comments.push(comment);
    localStorage.setItem(COMMENTS_KEY, JSON.stringify(comments));
}

function getAllComments() {
    const stored = localStorage.getItem(COMMENTS_KEY);
    return stored ? JSON.parse(stored) : [];
}

function getArticleComments(articleId) {
    return getAllComments().filter(c => c.articleId === parseInt(articleId));
}

function loadComments(articleId, container, countElement) {
    const comments = getArticleComments(articleId);
    
    container.innerHTML = '';
    
    if (comments.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: var(--color-text-light); padding: 2rem;">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>';
    } else {
        comments.sort((a, b) => new Date(b.date) - new Date(a.date));
        comments.forEach(comment => addCommentToDOM(comment, container));
    }
    
    updateCommentsCount(articleId, countElement);
}

function addCommentToDOM(comment, container) {
    const commentEl = document.createElement('div');
    commentEl.className = 'comment';
    commentEl.innerHTML = `
        <div class="comment-header">
            <span class="comment-author">${escapeHtml(comment.name)}</span>
            <time class="comment-date" datetime="${comment.date}">
                ${formatDate(comment.date)}
            </time>
        </div>
        <p class="comment-text">${escapeHtml(comment.text)}</p>
    `;
    
    if (container.firstChild && container.firstChild.tagName !== 'P') {
        container.insertBefore(commentEl, container.firstChild);
    } else {
        container.innerHTML = '';
        container.appendChild(commentEl);
    }
}

function updateCommentsCount(articleId, countElement) {
    if (!countElement) return;
    const count = getArticleComments(articleId).length;
    countElement.textContent = count;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'À l\'instant';
    if (diffMins < 60) return `Il y a ${diffMins} minute${diffMins > 1 ? 's' : ''}`;
    if (diffHours < 24) return `Il y a ${diffHours} heure${diffHours > 1 ? 's' : ''}`;
    if (diffDays < 7) return `Il y a ${diffDays} jour${diffDays > 1 ? 's' : ''}`;
    
    return date.toLocaleDateString('fr-FR', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background-color: var(--color-success);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(style);
