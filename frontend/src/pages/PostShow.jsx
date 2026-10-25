import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { api } from '../api.js';
import { useAuth } from '../auth.jsx';

export default function PostShow() {
    const { slug } = useParams();
    const { user, loading } = useAuth();
    const navigate = useNavigate();
    const [post, setPost] = useState(null);
    const [notFound, setNotFound] = useState(false);
    const [comment, setComment] = useState('');
    const [error, setError] = useState('');

    // Token tiklangach qayta yuklaymiz: can_update/can_delete foydalanuvchiga bog'liq
    useEffect(() => {
        if (loading) return;
        api(`/posts/${slug}`)
            .then((res) => setPost(res.data))
            .catch(() => setNotFound(true));
    }, [slug, loading, user]);

    async function deletePost() {
        if (!confirm("Post o'chirilsinmi?")) return;
        await api(`/posts/${slug}`, { method: 'DELETE' });
        navigate('/posts');
    }

    async function addComment(e) {
        e.preventDefault();
        try {
            const res = await api(`/posts/${slug}/comments`, { method: 'POST', body: { body: comment } });
            setPost({ ...post, comments: [res.data, ...post.comments] });
            setComment('');
            setError('');
        } catch (err) {
            setError(err.errors.body?.[0] || err.message);
        }
    }

    async function deleteComment(id) {
        await api(`/comments/${id}`, { method: 'DELETE' });
        setPost({ ...post, comments: post.comments.filter((c) => c.id !== id) });
    }

    if (notFound) return <p>Post topilmadi.</p>;
    if (!post) return <p>Yuklanmoqda...</p>;

    return (
        <>
            <article className="rounded bg-white p-6 shadow">
                <h1 className="text-3xl font-bold">{post.title}</h1>
                <p className="mt-1 text-sm text-gray-500">
                    {post.user.name} · {post.category.name} · {new Date(post.created_at).toLocaleDateString()}
                </p>
                <p className="mt-4 whitespace-pre-line">{post.body}</p>
                {(post.can_update || post.can_delete) && (
                    <div className="mt-6 flex gap-4 text-sm">
                        {post.can_update && (
                            <Link to={`/posts/${post.slug}/edit`} className="text-indigo-600">
                                Tahrirlash
                            </Link>
                        )}
                        {post.can_delete && (
                            <button onClick={deletePost} className="text-red-600">
                                O'chirish
                            </button>
                        )}
                    </div>
                )}
            </article>

            <section className="mt-8">
                <h2 className="mb-4 text-xl font-semibold">Izohlar ({post.comments.length})</h2>
                {user ? (
                    <form onSubmit={addComment} className="mb-6 space-y-2">
                        <textarea
                            value={comment}
                            onChange={(e) => setComment(e.target.value)}
                            rows={3}
                            placeholder="Izoh yozing..."
                            className="w-full rounded-md border border-gray-300 px-3 py-2"
                        />
                        {error && <p className="text-sm text-red-600">{error}</p>}
                        <button className="rounded bg-indigo-600 px-4 py-2 text-white">Yuborish</button>
                    </form>
                ) : (
                    <p className="mb-6 text-sm text-gray-500">
                        Izoh yozish uchun{' '}
                        <Link to="/login" className="text-indigo-600">
                            kiring
                        </Link>
                        .
                    </p>
                )}
                <div className="space-y-3">
                    {post.comments.map((c) => (
                        <div key={c.id} className="rounded bg-white p-4 shadow">
                            <p className="text-sm text-gray-500">
                                {c.user.name} · {new Date(c.created_at).toLocaleString()}
                            </p>
                            <p className="mt-1">{c.body}</p>
                            {c.can_delete && (
                                <button onClick={() => deleteComment(c.id)} className="mt-2 text-sm text-red-600">
                                    O'chirish
                                </button>
                            )}
                        </div>
                    ))}
                </div>
            </section>
        </>
    );
}
