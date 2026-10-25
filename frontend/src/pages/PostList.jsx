import { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { api } from '../api.js';

export default function PostList() {
    const [params, setParams] = useSearchParams();
    const category = params.get('category') || '';
    const page = Number(params.get('page') || 1);

    const [posts, setPosts] = useState(null);
    const [meta, setMeta] = useState(null);
    const [categories, setCategories] = useState([]);

    useEffect(() => {
        api('/categories').then((res) => setCategories(res.data));
    }, []);

    useEffect(() => {
        const query = new URLSearchParams({ page });
        if (category) query.set('category', category);
        api(`/posts?${query}`).then((res) => {
            setPosts(res.data);
            setMeta(res.meta);
        });
    }, [category, page]);

    const go = (next) => setParams({ ...(category && { category }), ...next });

    return (
        <>
            <div className="mb-6 flex flex-wrap gap-2 text-sm">
                <button
                    onClick={() => go({})}
                    className={`rounded-full px-3 py-1 ${!category ? 'bg-indigo-600 text-white' : 'bg-white'}`}
                >
                    Hammasi
                </button>
                {categories.map((c) => (
                    <button
                        key={c.id}
                        onClick={() => setParams({ category: c.slug })}
                        className={`rounded-full px-3 py-1 ${category === c.slug ? 'bg-indigo-600 text-white' : 'bg-white'}`}
                    >
                        {c.name}
                    </button>
                ))}
            </div>

            {!posts ? (
                <p>Yuklanmoqda...</p>
            ) : posts.length === 0 ? (
                <p className="text-gray-500">Postlar yo'q.</p>
            ) : (
                <div className="space-y-4">
                    {posts.map((post) => (
                        <article key={post.id} className="rounded bg-white p-5 shadow">
                            <Link to={`/posts/${post.slug}`} className="text-xl font-semibold hover:text-indigo-600">
                                {post.title}
                            </Link>
                            <p className="mt-1 text-sm text-gray-500">
                                {post.user.name} · {post.category.name} · {post.comments_count} izoh ·{' '}
                                {new Date(post.created_at).toLocaleDateString()}
                            </p>
                            <p className="mt-2 text-gray-700">{post.body.slice(0, 160)}...</p>
                        </article>
                    ))}
                </div>
            )}

            {meta && meta.last_page > 1 && (
                <div className="mt-6 flex items-center justify-between text-sm">
                    <button disabled={page <= 1} onClick={() => go({ page: page - 1 })} className="disabled:opacity-40">
                        ← Oldingi
                    </button>
                    <span>
                        {meta.current_page} / {meta.last_page}
                    </span>
                    <button
                        disabled={page >= meta.last_page}
                        onClick={() => go({ page: page + 1 })}
                        className="disabled:opacity-40"
                    >
                        Keyingi →
                    </button>
                </div>
            )}
        </>
    );
}
