import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Field from '../components/Field.jsx';
import { api } from '../api.js';

// Bitta forma ikkala holat uchun: /posts/new (yaratish) va /posts/:slug/edit (tahrirlash)
export default function PostForm() {
    const { slug } = useParams();
    const navigate = useNavigate();
    const [categories, setCategories] = useState([]);
    const [form, setForm] = useState({ title: '', category_id: '', body: '' });
    const [errors, setErrors] = useState({});

    useEffect(() => {
        api('/categories').then((res) => setCategories(res.data));
        if (slug) {
            api(`/posts/${slug}`).then(({ data }) =>
                setForm({ title: data.title, category_id: data.category.id, body: data.body }),
            );
        }
    }, [slug]);

    const bind = (key) => ({
        value: form[key],
        error: errors[key],
        onChange: (e) => setForm({ ...form, [key]: e.target.value }),
    });

    async function submit(e) {
        e.preventDefault();
        try {
            const res = await api(slug ? `/posts/${slug}` : '/posts', {
                method: slug ? 'PUT' : 'POST',
                body: form,
            });
            navigate(`/posts/${res.data.slug}`);
        } catch (err) {
            setErrors(err.errors);
        }
    }

    return (
        <form onSubmit={submit} className="space-y-4 rounded bg-white p-6 shadow">
            <h1 className="text-2xl font-bold">{slug ? 'Postni tahrirlash' : 'Yangi post'}</h1>
            <Field label="Sarlavha" {...bind('title')} />
            <Field label="Kategoriya" as="select" {...bind('category_id')}>
                <option value="">Tanlang...</option>
                {categories.map((c) => (
                    <option key={c.id} value={c.id}>
                        {c.name}
                    </option>
                ))}
            </Field>
            <Field label="Matn" as="textarea" rows={8} {...bind('body')} />
            <button className="rounded bg-indigo-600 px-4 py-2 text-white">Saqlash</button>
        </form>
    );
}
