

import { filterArticles } from '../data/articles.js';

let currentSearchTerm = '';
let currentCategory = 'all';
let searchCallback = null;

export function initSearch(callback) {
    searchCallback = callback;
    
    const searchInput = document.getElementById('searchInput');
    const filterTags = document.querySelectorAll('.tag');
    
    if (!searchInput) return;
    
    
    let debounceTimer;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentSearchTerm = e.target.value.trim();
            performSearch();
        }, 300);
    });
    
    
    filterTags.forEach(tag => {
        tag.addEventListener('click', () => {
            
            filterTags.forEach(t => t.classList.remove('active'));
            
            tag.classList.add('active');
            
            currentCategory = tag.dataset.category;
            performSearch();
        });
    });
}

function performSearch() {
    if (!searchCallback) return;
    
    const results = filterArticles(currentSearchTerm, currentCategory);
    searchCallback(results);
    
    
    const articlesGrid = document.getElementById('articlesGrid');
    if (articlesGrid) {
        articlesGrid.style.opacity = '0';
        setTimeout(() => {
            articlesGrid.style.opacity = '1';
        }, 100);
    }
}

export function getSearchState() {
    return {
        searchTerm: currentSearchTerm,
        category: currentCategory
    };
}
