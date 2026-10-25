import { createContext, useContext, useEffect, useState } from 'react';
import { api, getToken, setToken } from './api.js';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(Boolean(getToken()));

    // Sahifa yangilanganda token bo'yicha foydalanuvchini tiklaymiz
    useEffect(() => {
        if (!getToken()) return;
        api('/user')
            .then((res) => setUser(res.data))
            .catch(() => setToken(null))
            .finally(() => setLoading(false));
    }, []);

    async function authenticate(path, body) {
        const res = await api(path, { method: 'POST', body });
        setToken(res.token);
        setUser(res.user);
    }

    const value = {
        user,
        loading,
        login: (body) => authenticate('/login', body),
        register: (body) => authenticate('/register', body),
        async logout() {
            await api('/logout', { method: 'POST' }).catch(() => {});
            setToken(null);
            setUser(null);
        },
    };

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export const useAuth = () => useContext(AuthContext);
