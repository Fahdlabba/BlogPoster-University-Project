class Router {
    constructor() {
        this.routes = new Map();
        this.currentRoute = null;
    }
    
    register(path, handler) {
        this.routes.set(path, handler);
    }
    
    navigate(path, data = {}) {
        const handler = this.routes.get(path);
        
        if (handler) {
            this.currentRoute = path;
            handler(data);
            
            if (window.history && window.history.pushState) {
                const url = path === '/' ? '/index.html' : `/article.html?id=${data.id}`;
                window.history.pushState({ path, data }, '', url);
            }
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    init() {
        window.addEventListener('popstate', (e) => {
            if (e.state) {
                const handler = this.routes.get(e.state.path);
                if (handler) {
                    handler(e.state.data);
                }
            }
        });
    }
}

export const router = new Router();
