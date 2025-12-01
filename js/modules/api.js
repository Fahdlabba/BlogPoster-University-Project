const API_BASE_URL = "php/";

export async function fetchArticles(
  category = "all",
  search = "",
  page = 1,
  limit = 10
) {
  try {
    const params = new URLSearchParams();
    if (category && category !== "all") {
      params.append("category", category);
    }
    if (search) {
      params.append("search", search);
    }
    params.append("page", page);
    params.append("limit", limit);

    const response = await fetch(
      `${API_BASE_URL}articles.php?${params.toString()}`
    );

    if (!response.ok) {
      throw new Error("Failed to fetch articles");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error fetching articles:", error);
    throw error;
  }
}

export async function fetchArticleBySlug(slug) {
  try {
    const response = await fetch(
      `${API_BASE_URL}articles.php?slug=${encodeURIComponent(slug)}`
    );

    if (!response.ok) {
      throw new Error("Article not found");
    }

    const article = await response.json();
    return article;
  } catch (error) {
    console.error("Error fetching article:", error);
    throw error;
  }
}

export async function createArticle(articleData) {
  try {
    const response = await fetch(`${API_BASE_URL}articles.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(articleData),
    });

    if (!response.ok) {
      throw new Error("Failed to create article");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error creating article:", error);
    throw error;
  }
}

export async function updateArticle(id, articleData) {
  try {
    const response = await fetch(`${API_BASE_URL}articles.php?id=${id}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(articleData),
    });

    if (!response.ok) {
      throw new Error("Failed to update article");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error updating article:", error);
    throw error;
  }
}

export async function deleteArticle(id) {
  try {
    const response = await fetch(`${API_BASE_URL}articles.php?id=${id}`, {
      method: "DELETE",
    });

    if (!response.ok) {
      throw new Error("Failed to delete article");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error deleting article:", error);
    throw error;
  }
}

export async function fetchComments(articleId, approvedOnly = true) {
  try {
    const params = new URLSearchParams();
    params.append("article_id", articleId);
    params.append("approved", approvedOnly ? "1" : "-1");

    const response = await fetch(
      `${API_BASE_URL}comments.php?${params.toString()}`
    );

    if (!response.ok) {
      throw new Error("Failed to fetch comments");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error fetching comments:", error);
    throw error;
  }
}

export async function submitComment(commentData) {
  try {
    if (!commentData.article_id || !commentData.name || !commentData.text) {
      throw new Error("Missing required fields");
    }

    const response = await fetch(`${API_BASE_URL}comments.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(commentData),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.error || "Failed to submit comment");
    }

    return data;
  } catch (error) {
    console.error("Error submitting comment:", error);
    throw error;
  }
}

export async function approveComment(commentId) {
  try {
    const response = await fetch(
      `${API_BASE_URL}comments.php?id=${commentId}`,
      {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ is_approved: 1 }),
      }
    );

    if (!response.ok) {
      throw new Error("Failed to approve comment");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error approving comment:", error);
    throw error;
  }
}

/**
 * Delete a comment (admin function)
 * @param {number} commentId - Comment ID
 * @returns {Promise} - Promise with result
 */
export async function deleteComment(commentId) {
  try {
    const response = await fetch(
      `${API_BASE_URL}comments.php?id=${commentId}`,
      {
        method: "DELETE",
      }
    );

    if (!response.ok) {
      throw new Error("Failed to delete comment");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error deleting comment:", error);
    throw error;
  }
}

export function showNotification(message, type = "success") {
  const notification = document.createElement("div");

  let backgroundColor;
  switch (type) {
    case "error":
      backgroundColor = "#f44336";
      break;
    case "info":
      backgroundColor = "#2196F3";
      break;
    case "success":
    default:
      backgroundColor = "#4CAF50";
      break;
  }

  notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background-color: ${backgroundColor};
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
    notification.style.animation = "slideOut 0.3s ease";
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}
