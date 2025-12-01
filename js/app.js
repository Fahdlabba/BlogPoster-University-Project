import { fetchArticles, showNotification } from "./modules/api.js";
import { initTheme, toggleTheme } from "./modules/theme.js";
import { initBackToTop, initSmoothScroll } from "./modules/scroll.js";

let currentPage = 1;
let currentCategory = "all";
let currentSearch = "";
const articlesPerPage = 10;

document.addEventListener("DOMContentLoaded", () => {
  initTheme();
  initBackToTop();
  initSmoothScroll();

  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle) {
    themeToggle.addEventListener("click", toggleTheme);
  }

  loadArticles();

  initSearchAndFilters();
});

async function loadArticles() {
  try {
    const articlesGrid = document.getElementById("articlesGrid");
    if (articlesGrid) {
      articlesGrid.innerHTML =
        '<p style="text-align: center; padding: 2rem;">Chargement des articles...</p>';
    }

    const data = await fetchArticles(
      currentCategory,
      currentSearch,
      currentPage,
      articlesPerPage
    );

    displayArticles(data.articles);

    if (data.pagination.totalPages > 1) {
      displayPagination(data.pagination);
    }
  } catch (error) {
    console.error("Error loading articles:", error);
    showNotification("Erreur lors du chargement des articles", "error");

    const articlesGrid = document.getElementById("articlesGrid");
    if (articlesGrid) {
      articlesGrid.innerHTML =
        '<p style="text-align: center; padding: 2rem; color: red;">Erreur lors du chargement des articles. Veuillez réessayer.</p>';
    }
  }
}

function initSearchAndFilters() {
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    let searchTimeout;
    searchInput.addEventListener("input", (e) => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        currentSearch = e.target.value.trim();
        currentPage = 1;
        loadArticles();
      }, 500);
    });
  }

  const filterTags = document.querySelectorAll(".tag");
  filterTags.forEach((tag) => {
    tag.addEventListener("click", () => {
      filterTags.forEach((t) => t.classList.remove("active"));

      tag.classList.add("active");

      currentCategory = tag.dataset.category;
      currentPage = 1;
      loadArticles();
    });
  });
}

function displayArticles(articlesToDisplay) {
  const articlesGrid = document.getElementById("articlesGrid");
  const noResults = document.getElementById("noResults");

  if (!articlesGrid) return;

  if (!articlesToDisplay || articlesToDisplay.length === 0) {
    articlesGrid.style.display = "none";
    if (noResults) noResults.style.display = "block";
    return;
  }

  articlesGrid.style.display = "grid";
  if (noResults) noResults.style.display = "none";

  articlesGrid.innerHTML = articlesToDisplay
    .map(
      (article) => `
        <article class="article-card" data-slug="${article.slug}">
            <img src="${
              article.image ||
              "https://via.placeholder.com/800x400?text=No+Image"
            }" 
                 alt="${article.title}" 
                 class="article-image" 
                 loading="lazy">
            <div class="article-content">
                <div class="article-meta">
                    <span class="article-category">${getCategoryLabel(
                      article.category
                    )}</span>
                    <time class="article-date" datetime="${article.date}">
                        ${formatDate(article.date)}
                    </time>
                </div>
                <h2 class="article-title">${article.title}</h2>
                <p class="article-excerpt">${article.excerpt}</p>
                <div class="article-footer">
                    <a href="article.html?slug=${
                      article.slug
                    }" class="read-more">
                        Lire la suite →
                    </a>
                    <span class="reading-time">${
                      article.reading_time || "5 min"
                    } de lecture</span>
                </div>
            </div>
        </article>
    `
    )
    .join("");

  const articleCards = articlesGrid.querySelectorAll(".article-card");
  articleCards.forEach((card) => {
    card.style.cursor = "pointer";
    card.addEventListener("click", (e) => {
      if (
        e.target.classList.contains("read-more") ||
        e.target.closest(".read-more")
      ) {
        return;
      }

      const articleSlug = card.dataset.slug;
      window.location.href = `article.html?slug=${articleSlug}`;
    });
  });
}

function displayPagination(pagination) {
  const articlesGrid = document.getElementById("articlesGrid");
  if (!articlesGrid) return;

  const paginationHtml = `
        <div class="pagination" style="
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        ">
            ${
              pagination.page > 1
                ? `
                <button onclick="changePage(${
                  pagination.page - 1
                })" class="pagination-btn" style="
                    padding: 0.5rem 1rem;
                    background: var(--color-primary);
                    color: white;
                    border: none;
                    border-radius: 0.5rem;
                    cursor: pointer;
                ">← Précédent</button>
            `
                : ""
            }
            
            <span style="
                padding: 0.5rem 1rem;
                display: flex;
                align-items: center;
            ">Page ${pagination.page} / ${pagination.totalPages}</span>
            
            ${
              pagination.page < pagination.totalPages
                ? `
                <button onclick="changePage(${
                  pagination.page + 1
                })" class="pagination-btn" style="
                    padding: 0.5rem 1rem;
                    background: var(--color-primary);
                    color: white;
                    border: none;
                    border-radius: 0.5rem;
                    cursor: pointer;
                ">Suivant →</button>
            `
                : ""
            }
        </div>
    `;

  articlesGrid.insertAdjacentHTML("beforeend", paginationHtml);
}

window.changePage = function (page) {
  currentPage = page;
  loadArticles();

  window.scrollTo({ top: 0, behavior: "smooth" });
};

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
