const TOKEN_KEY = 'blog_token';

export const getToken = () => localStorage.getItem(TOKEN_KEY);
export const setToken = (token) =>
    token ? localStorage.setItem(TOKEN_KEY, token) : localStorage.removeItem(TOKEN_KEY);

// Laravel xatosi (422 validatsiya, 403 va h.k.) shu ko'rinishda otiladi
export class ApiError extends Error {
    constructor(status, data) {
        super(data?.message || `Xato: ${status}`);
        this.status = status;
        this.errors = data?.errors || {};
    }
}

export async function api(path, { method = 'GET', body } = {}) {
    const headers = { Accept: 'application/json' };
    const token = getToken();
    if (token) headers.Authorization = `Bearer ${token}`;
    if (body) headers['Content-Type'] = 'application/json';

    const res = await fetch(`/api${path}`, {
        method,
        headers,
        body: body ? JSON.stringify(body) : undefined,
    });

    if (res.status === 204) return null;
    const data = await res.json().catch(() => null);
    if (!res.ok) throw new ApiError(res.status, data);
    return data;
}
