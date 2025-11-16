export const articles = [
    {
        id: 1,
        title: "Introduction au JavaScript Moderne",
        slug: "introduction-javascript-moderne",
        category: "javascript",
        date: "2025-11-10",
        image: "https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=800&h=400&fit=crop",
        excerpt: "Découvrez les fonctionnalités essentielles d'ES6+ qui transforment la façon dont nous écrivons du JavaScript aujourd'hui.",
        readingTime: "5 min",
        content: `
            <p>JavaScript a considérablement évolué ces dernières années avec l'introduction d'ES6 (ECMAScript 2015) et des versions suivantes. Ces nouvelles fonctionnalités ont transformé la façon dont nous écrivons du code JavaScript, le rendant plus lisible, maintenable et puissant.</p>

            <h2>Les Variables : let et const</h2>
            <p>Fini le temps où <code>var</code> était la seule option. Maintenant, nous avons <code>let</code> et <code>const</code> qui offrent une meilleure gestion de la portée des variables. <code>let</code> permet de déclarer des variables dont la valeur peut changer, tandis que <code>const</code> est utilisé pour les constantes.</p>

            <h3>Arrow Functions</h3>
            <p>Les fonctions flèches offrent une syntaxe plus concise et un comportement différent du mot-clé <code>this</code>. Elles sont particulièrement utiles pour les callbacks et les fonctions courtes.</p>

            <blockquote>
                "JavaScript est devenu l'un des langages les plus polyvalents et puissants de l'écosystème du développement moderne."
            </blockquote>

            <h2>Destructuration et Spread Operator</h2>
            <p>La destructuration permet d'extraire facilement des données des tableaux ou des objets. Le spread operator (<code>...</code>) facilite la manipulation des tableaux et des objets en permettant de copier ou fusionner des structures de données.</p>

            <p>Ces fonctionnalités modernes rendent le code plus expressif et moins verbeux. Par exemple, au lieu d'écrire plusieurs lignes pour extraire des propriétés d'un objet, on peut le faire en une seule ligne avec la destructuration.</p>

            <h3>Promesses et Async/Await</h3>
            <p>La gestion de l'asynchrone a également été grandement améliorée. Les Promesses offrent une meilleure gestion des opérations asynchrones que les callbacks traditionnels. Et avec async/await, le code asynchrone ressemble presque à du code synchrone, ce qui améliore considérablement la lisibilité.</p>

            <p>En conclusion, JavaScript moderne offre des outils puissants pour créer des applications web robustes et performantes. Il est essentiel pour tout développeur web de maîtriser ces concepts pour rester compétitif dans l'industrie.</p>
        `
    },
    {
        id: 2,
        title: "Les Principes du Design Minimaliste",
        slug: "principes-design-minimaliste",
        category: "design",
        date: "2025-11-08",
        image: "https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?w=800&h=400&fit=crop",
        excerpt: "Le design minimaliste n'est pas seulement une esthétique, c'est une philosophie qui met l'accent sur l'essentiel.",
        readingTime: "7 min",
        content: `
            <p>Le design minimaliste est bien plus qu'une simple tendance esthétique. C'est une philosophie de conception qui cherche à éliminer tout ce qui est superflu pour ne garder que l'essentiel. Cette approche permet de créer des interfaces claires, élégantes et fonctionnelles.</p>

            <h2>Moins, c'est plus</h2>
            <p>La célèbre devise de Ludwig Mies van der Rohe résume parfaitement l'esprit du minimalisme. En design web, cela signifie supprimer tous les éléments qui n'apportent pas de valeur à l'utilisateur. Chaque élément présent sur la page doit avoir un but précis.</p>

            <h3>Espace blanc et respiration</h3>
            <p>L'espace blanc (ou espace négatif) n'est pas du vide inutile. C'est un outil de design puissant qui permet de créer de la hiérarchie, d'améliorer la lisibilité et de donner de l'air à votre contenu. Un design minimaliste utilise généreusement l'espace blanc pour guider l'œil de l'utilisateur.</p>

            <blockquote>
                "La simplicité est la sophistication ultime." - Léonard de Vinci
            </blockquote>

            <h2>Typographie et hiérarchie</h2>
            <p>Dans un design minimaliste, la typographie joue un rôle crucial. Avec moins d'éléments visuels, le choix des polices, leur taille et leur espacement deviennent d'autant plus importants. Une bonne hiérarchie typographique guide naturellement l'utilisateur à travers le contenu.</p>

            <h3>Palette de couleurs limitée</h3>
            <p>Le minimalisme favorise une palette de couleurs restreinte, souvent composée de neutres avec un ou deux accents de couleur. Cette approche crée une cohérence visuelle et évite la surcharge cognitive.</p>

            <p>En appliquant ces principes, vous créez des designs qui ne se démodent pas, qui sont faciles à maintenir et qui offrent une excellente expérience utilisateur. Le minimalisme n'est pas synonyme de simplicité pauvre, mais plutôt de sophistication réfléchie.</p>
        `
    },
    {
        id: 3,
        title: "Construire une API RESTful avec Node.js",
        slug: "api-restful-nodejs",
        category: "web",
        date: "2025-11-05",
        image: "https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=400&fit=crop",
        excerpt: "Apprenez à créer une API REST robuste et scalable avec Node.js et Express.",
        readingTime: "10 min",
        content: `
            <p>Les API RESTful sont devenues le standard pour la communication entre applications web. Node.js, avec son architecture événementielle et non-bloquante, est un excellent choix pour construire des API performantes et scalables.</p>

            <h2>Qu'est-ce qu'une API REST ?</h2>
            <p>REST (Representational State Transfer) est un style architectural pour concevoir des services web. Une API REST utilise les méthodes HTTP standard (GET, POST, PUT, DELETE) pour effectuer des opérations CRUD (Create, Read, Update, Delete) sur des ressources.</p>

            <h3>Configuration du projet</h3>
            <p>Pour commencer, nous utiliserons Express, le framework web le plus populaire pour Node.js. Express simplifie la création de serveurs web et fournit une structure robuste pour gérer les routes et les middlewares.</p>

            <h2>Les principes RESTful</h2>
            <p>Une bonne API REST suit plusieurs principes fondamentaux :</p>
            
            <ul>
                <li>Architecture client-serveur séparée</li>
                <li>Sans état (stateless) - chaque requête contient toutes les informations nécessaires</li>
                <li>Cacheable - les réponses doivent indiquer si elles peuvent être mises en cache</li>
                <li>Interface uniforme - utilisation cohérente des méthodes HTTP</li>
            </ul>

            <blockquote>
                "Une API bien conçue est invisible pour le développeur qui l'utilise."
            </blockquote>

            <h3>Gestion des erreurs</h3>
            <p>Une gestion appropriée des erreurs est cruciale pour une API de qualité. Utilisez les codes de statut HTTP appropriés (200 pour succès, 404 pour non trouvé, 500 pour erreur serveur) et fournissez des messages d'erreur clairs et informatifs.</p>

            <h2>Authentification et sécurité</h2>
            <p>La sécurité est primordiale. Implémentez toujours une authentification appropriée (JWT est un choix populaire), utilisez HTTPS, validez toutes les entrées, et limitez le taux de requêtes pour prévenir les abus.</p>

            <p>Avec ces bonnes pratiques, vous pouvez créer des API robustes, maintenables et sécurisées qui serviront de fondation solide à vos applications web modernes.</p>
        `
    },
    {
        id: 4,
        title: "CSS Grid vs Flexbox : Quand utiliser quoi ?",
        slug: "css-grid-vs-flexbox",
        category: "web",
        date: "2025-11-03",
        image: "https://images.unsplash.com/photo-1507721999472-8ed4421c4af2?w=800&h=400&fit=crop",
        excerpt: "Comprendre les différences entre CSS Grid et Flexbox pour choisir le bon outil au bon moment.",
        readingTime: "6 min",
        content: `
            <p>CSS Grid et Flexbox sont deux systèmes de mise en page puissants, mais ils excellent dans des situations différentes. Comprendre quand utiliser l'un ou l'autre est essentiel pour créer des layouts efficaces.</p>

            <h2>Flexbox : La mise en page unidimensionnelle</h2>
            <p>Flexbox est parfait pour organiser des éléments dans une seule dimension - soit en ligne, soit en colonne. Il brille particulièrement dans les situations suivantes :</p>

            <ul>
                <li>Centrer des éléments verticalement et horizontalement</li>
                <li>Créer des barres de navigation</li>
                <li>Répartir l'espace équitablement entre des éléments</li>
                <li>Organiser des composants dans une direction</li>
            </ul>

            <h3>CSS Grid : La puissance bidimensionnelle</h3>
            <p>CSS Grid excelle dans la création de layouts complexes à deux dimensions. Utilisez Grid quand vous avez besoin de :</p>

            <ul>
                <li>Créer des grilles de cartes ou de produits</li>
                <li>Construire des layouts de page complets</li>
                <li>Contrôler précisément les lignes ET les colonnes</li>
                <li>Superposer des éléments facilement</li>
            </ul>

            <blockquote>
                "Flexbox et Grid ne sont pas des concurrents, mais des compléments. Utilisez-les ensemble pour créer des layouts puissants."
            </blockquote>

            <h2>La combinaison gagnante</h2>
            <p>En pratique, vous utiliserez souvent les deux ensemble. Par exemple, Grid pour la structure principale de la page, et Flexbox pour aligner les éléments à l'intérieur de chaque cellule de la grille.</p>

            <h3>Compatibilité et support</h3>
            <p>Les deux technologies sont maintenant bien supportées par tous les navigateurs modernes. Vous pouvez les utiliser en production sans hésitation, avec éventuellement des fallbacks pour les très anciens navigateurs si nécessaire.</p>

            <p>La clé est de comprendre les forces de chaque outil et de les utiliser en conséquence. Grid pour les layouts bidimensionnels, Flexbox pour l'alignement unidimensionnel - maîtrisez les deux et vous serez équipé pour gérer n'importe quelle situation de mise en page.</p>
        `
    },
    {
        id: 5,
        title: "L'Intelligence Artificielle et le Développement Web",
        slug: "ia-developpement-web",
        category: "tech",
        date: "2025-11-01",
        image: "https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&h=400&fit=crop",
        excerpt: "Comment l'IA transforme le développement web et ce que cela signifie pour les développeurs.",
        readingTime: "8 min",
        content: `
            <p>L'intelligence artificielle révolutionne de nombreux domaines, et le développement web n'échappe pas à cette transformation. Des assistants de code aux outils de génération de design, l'IA change notre façon de travailler.</p>

            <h2>Les assistants de code IA</h2>
            <p>Des outils comme GitHub Copilot et ChatGPT ont changé la donne pour les développeurs. Ils peuvent suggérer du code, détecter des bugs, et même expliquer des concepts complexes. Ces assistants ne remplacent pas les développeurs, mais les rendent plus productifs.</p>

            <h3>Génération de contenu et de design</h3>
            <p>L'IA peut maintenant générer du texte, des images, et même des suggestions de design. Pour les développeurs web, cela signifie pouvoir prototyper plus rapidement et avoir accès à du contenu de placeholder de meilleure qualité.</p>

            <blockquote>
                "L'IA n'est pas là pour remplacer les développeurs, mais pour augmenter leurs capacités et les libérer des tâches répétitives."
            </blockquote>

            <h2>Personnalisation et UX</h2>
            <p>L'IA permet de créer des expériences utilisateur personnalisées à grande échelle. En analysant le comportement des utilisateurs, les systèmes IA peuvent adapter l'interface, le contenu et les recommandations en temps réel.</p>

            <h3>Accessibilité améliorée</h3>
            <p>L'IA aide également à rendre le web plus accessible. Des outils de génération automatique de textes alternatifs pour les images aux systèmes de sous-titrage en temps réel, l'IA facilite la création de sites web inclusifs.</p>

            <h2>L'avenir du développement web</h2>
            <p>L'intégration de l'IA dans le développement web ne fait que commencer. Nous verrons probablement :</p>

            <ul>
                <li>Des outils de debugging plus intelligents</li>
                <li>Une génération automatique de tests</li>
                <li>Des optimisations de performance pilotées par l'IA</li>
                <li>Des interfaces qui s'adaptent automatiquement aux préférences des utilisateurs</li>
            </ul>

            <p>Pour les développeurs, cela signifie qu'il faut rester curieux et apprendre à collaborer efficacement avec ces nouveaux outils. L'IA ne remplacera pas la créativité humaine et la compréhension profonde des problèmes, mais elle peut nous aider à construire de meilleures solutions plus rapidement.</p>
        `
    },
    {
        id: 6,
        title: "Performance Web : Les Fondamentaux",
        slug: "performance-web-fondamentaux",
        category: "web",
        date: "2025-10-28",
        image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop",
        excerpt: "Optimisez la vitesse de votre site web avec ces techniques essentielles de performance.",
        readingTime: "9 min",
        content: `
            <p>La performance web est cruciale pour l'expérience utilisateur et le référencement. Un site lent fait fuir les visiteurs et pénalise votre classement dans les moteurs de recherche. Voici les fondamentaux pour optimiser la vitesse de votre site.</p>

            <h2>Core Web Vitals</h2>
            <p>Google a défini trois métriques essentielles pour mesurer l'expérience utilisateur : LCP (Largest Contentful Paint), FID (First Input Delay), et CLS (Cumulative Layout Shift). Ces métriques impactent directement votre SEO.</p>

            <h3>Optimisation des images</h3>
            <p>Les images représentent souvent la majorité du poids d'une page web. Utilisez des formats modernes comme WebP, implémentez le lazy loading, et servez des images aux dimensions appropriées pour chaque appareil.</p>

            <h2>Minification et compression</h2>
            <p>Réduisez la taille de vos fichiers CSS, JavaScript et HTML en les minifiant. Activez la compression Gzip ou Brotli sur votre serveur pour réduire encore plus la quantité de données transférées.</p>

            <blockquote>
                "Chaque milliseconde compte. Une amélioration de 0.1 seconde du temps de chargement peut augmenter les conversions de 8%."
            </blockquote>

            <h3>Mise en cache intelligente</h3>
            <p>Utilisez les en-têtes de cache HTTP pour stocker les ressources statiques dans le navigateur de l'utilisateur. Cela réduit drastiquement le temps de chargement des visites répétées.</p>

            <h2>JavaScript et CSS critiques</h2>
            <p>Identifiez et inlinez le CSS critique nécessaire pour le rendu initial de la page. Différez le chargement du JavaScript non-critique avec les attributs <code>async</code> ou <code>defer</code>.</p>

            <h3>CDN et distribution géographique</h3>
            <p>Utilisez un Content Delivery Network (CDN) pour servir vos fichiers statiques depuis des serveurs proches de vos utilisateurs. Cela réduit la latence et améliore les temps de chargement globaux.</p>

            <p>La performance web n'est pas un projet ponctuel mais un processus continu. Mesurez régulièrement vos performances avec des outils comme Lighthouse, WebPageTest, ou Chrome DevTools, et optimisez en conséquence.</p>
        `
    }
];

export function getArticleById(id) {
    return articles.find(article => article.id === parseInt(id));
}

export function getArticleBySlug(slug) {
    return articles.find(article => article.slug === slug);
}

export function filterArticles(searchTerm, category) {
    return articles.filter(article => {
        const matchesSearch = !searchTerm || 
            article.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            article.excerpt.toLowerCase().includes(searchTerm.toLowerCase());
        
        const matchesCategory = category === 'all' || article.category === category;
        
        return matchesSearch && matchesCategory;
    });
}
