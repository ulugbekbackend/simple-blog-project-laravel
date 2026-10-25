import { Link, Navigate, Outlet, Route, Routes } from 'react-router-dom';
import { useAuth } from './auth.jsx';
import Login from './pages/Login.jsx';
import PostForm from './pages/PostForm.jsx';
import PostList from './pages/PostList.jsx';
import PostShow from './pages/PostShow.jsx';
import Register from './pages/Register.jsx';

function Layout() {
    const { user, logout } = useAuth();

    return (
        <>
            <header className="bg-white shadow">
                <nav className="mx-auto flex max-w-4xl items-center justify-between px-4 py-4">
                    <Link to="/posts" className="text-xl font-bold">
                        Simple Blog
                    </Link>
                    <div className="flex items-center gap-4 text-sm">
                        {user ? (
                            <>
                                <Link to="/posts/new" className="text-indigo-600">
                                    Yangi post
                                </Link>
                                <span className="text-gray-500">{user.name}</span>
                                <button onClick={logout} className="text-gray-700 hover:underline">
                                    Chiqish
                                </button>
                            </>
                        ) : (
                            <>
                                <Link to="/login">Kirish</Link>
                                <Link to="/register">Ro'yxatdan o'tish</Link>
                            </>
                        )}
                    </div>
                </nav>
            </header>
            <main className="mx-auto max-w-4xl px-4 py-8">
                <Outlet />
            </main>
        </>
    );
}

// Login qilmaganlarni /login ga yuboradi
function RequireAuth({ children }) {
    const { user, loading } = useAuth();
    if (loading) return <p>Yuklanmoqda...</p>;
    return user ? children : <Navigate to="/login" replace />;
}

export default function App() {
    return (
        <Routes>
            <Route element={<Layout />}>
                <Route path="/" element={<Navigate to="/posts" replace />} />
                <Route path="/posts" element={<PostList />} />
                <Route
                    path="/posts/new"
                    element={
                        <RequireAuth>
                            <PostForm />
                        </RequireAuth>
                    }
                />
                <Route
                    path="/posts/:slug/edit"
                    element={
                        <RequireAuth>
                            <PostForm />
                        </RequireAuth>
                    }
                />
                <Route path="/posts/:slug" element={<PostShow />} />
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
            </Route>
        </Routes>
    );
}
