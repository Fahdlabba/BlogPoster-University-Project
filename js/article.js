import { fetchArticleBySlug, showNotification } from "./modules/api.js";
import { initTheme, toggleTheme } from "./modules/theme.js";
import {
  initBackToTop,
  initReadingProgress,
  initSmoothScroll,
} from "./modules/scroll.js";
import { initShare } from "./modules/share.js";

let currentArticle = null;

document.addEventListener("DOMContentLoaded", async () => {
  initTheme();
  initBackToTop();
  initReadingProgress();
  initSmoothScroll();
  initShare();

  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle) {
    themeToggle.addEventListener("click", toggleTheme);
  }

  const urlParams = new URLSearchParams(window.location.search);
  const articleSlug = urlParams.get("slug");

  if (!articleSlug) {
    window.location.href = "index.html";
    return;
  }

  await loadArticle(articleSlug);
});

async function loadArticle(slug) {
  try {
    const articleContent = document.getElementById("articleContent");
    if (articleContent) {
      articleContent.innerHTML =
        '<p style="text-align: center; padding: 2rem;">Chargement de l\'article...</p>';
    }

    const article = await fetchArticleBySlug(slug);

    currentArticle = article;

    displayArticle(article);

    const { initCommentsWithPHP } = await import("./modules/comments.js");
    initCommentsWithPHP(article.id);
  } catch (error) {
    console.error("Error loading article:", error);
    showNotification("Article non trouvé", "error");

    const articleContent = document.getElementById("articleContent");
    if (articleContent) {
      articleContent.innerHTML =
        '<p style="text-align: center; padding: 2rem; color: red;">Article non trouvé. Redirection vers l\'accueil...</p>';
    }

    setTimeout(() => {
      window.location.href = "index.html";
    }, 2000);
  }
}

function displayArticle(article) {
  const articleContent = document.getElementById("articleContent");
  const pageTitle = document.getElementById("pageTitle");

  if (!articleContent) return;

  if (pageTitle) {
    pageTitle.textContent = `${article.title} - Blog Minimaliste`;
  }

  articleContent.innerHTML = `
        <header class="article-header">
            <span class="article-category">${getCategoryLabel(
              article.category
            )}</span>
            <h1 class="article-title">${article.title}</h1>
            <div class="article-meta">
                <time class="article-date" datetime="${article.date}">
                    ${formatDate(article.date)}
                </time>
                <span class="reading-time">${
                  article.reading_time || "5 min"
                } de lecture</span>
            </div>
        </header>
        
        <img src="${
          article.image || "https://via.placeholder.com/800x400?text=No+Image"
        }" 
             alt="${article.title}" 
             class="article-image" 
             loading="eager">
        
        <div class="article-body">
            ${article.content}
        </div>
    `;
}

function displayArticleNavigation(currentArticle) {
  const nav = document.getElementById("articleNav");
  if (!nav) return;

  const currentIndex = articles.findIndex((a) => a.id === currentArticle.id);
  const prevArticle = currentIndex > 0 ? articles[currentIndex - 1] : null;
  const nextArticle =
    currentIndex < articles.length - 1 ? articles[currentIndex + 1] : null;

  let navHtml = "";

  if (prevArticle) {
    navHtml += `
            <a href="article.html?id=${prevArticle.id}" class="nav-article nav-prev">
                <div class="nav-label">← Article précédent</div>
                <div class="nav-title">${prevArticle.title}</div>
            </a>
        `;
  }

  if (nextArticle) {
    navHtml += `
            <a href="article.html?id=${nextArticle.id}" class="nav-article nav-next">
                <div class="nav-label">Article suivant →</div>
                <div class="nav-title">${nextArticle.title}</div>
            </a>
        `;
  }

  nav.innerHTML = navHtml;
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("fr-FR", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

function getCategoryLabel(category) {
  const labels = {
    web: "Web",
    javascript: "JavaScript",
    design: "Design",
    tech: "Tech",
  };
  return labels[category] || category;
}
