import { articles } from './data/articles.js';
import { initTheme, toggleTheme } from './modules/theme.js';
import { initSearch } from './modules/search.js';
import { initBackToTop, initSmoothScroll } from './modules/scroll.js';


document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initBackToTop();
    initSmoothScroll();
    
    
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    
    displayArticles(articles);
    
    
    initSearch(displayArticles);
});

function displayArticles(articlesToDisplay) {
    const articlesGrid = document.getElementById('articlesGrid');
    const noResults = document.getElementById('noResults');
    
    if (!articlesGrid) return;
    
    if (articlesToDisplay.length === 0) {
        articlesGrid.style.display = 'none';
        if (noResults) noResults.style.display = 'block';
        return;
    }
    
    articlesGrid.style.display = 'grid';
    if (noResults) noResults.style.display = 'none';
    
    articlesGrid.innerHTML = articlesToDisplay.map(article => `
        <article class="article-card" data-id="${article.id}">
            <img src="${article.image}" alt="${article.title}" class="article-image" loading="lazy">
            <div class="article-content">
                <div class="article-meta">
                    <span class="article-category">${getCategoryLabel(article.category)}</span>
                    <time class="article-date" datetime="${article.date}">
                        ${formatDate(article.date)}
                    </time>
                </div>
                <h2 class="article-title">${article.title}</h2>
                <p class="article-excerpt">${article.excerpt}</p>
                <div class="article-footer">
                    <a href="article.html?id=${article.id}" class="read-more">
                        Lire la suite →
                    </a>
                    <span class="reading-time">${article.readingTime} de lecture</span>
                </div>
            </div>
        </article>
    `).join('');
    
    
    const articleCards = articlesGrid.querySelectorAll('.article-card');
    articleCards.forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', (e) => {
            
            if (e.target.classList.contains('read-more') || e.target.closest('.read-more')) {
                return;
            }
            
            const articleId = card.dataset.id;
            window.location.href = `article.html?id=${articleId}`;
        });
    });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function getCategoryLabel(category) {
    const labels = {
        'web': 'Web',
        'javascript': 'JavaScript',
        'design': 'Design',
        'tech': 'Tech'
    };
    return labels[category] || category;
}
