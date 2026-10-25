import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import Field from '../components/Field.jsx';
import { useAuth } from '../auth.jsx';

export default function Login() {
    const { login } = useAuth();
    const navigate = useNavigate();
    const [form, setForm] = useState({ email: '', password: '' });
    const [errors, setErrors] = useState({});

    async function submit(e) {
        e.preventDefault();
        try {
            await login(form);
            navigate('/posts');
        } catch (err) {
            setErrors(err.errors);
        }
    }

    return (
        <form onSubmit={submit} className="mx-auto max-w-md space-y-4 rounded bg-white p-6 shadow">
            <h1 className="text-2xl font-bold">Kirish</h1>
            <Field
                label="Email"
                type="email"
                value={form.email}
                error={errors.email}
                onChange={(e) => setForm({ ...form, email: e.target.value })}
            />
            <Field
                label="Parol"
                type="password"
                value={form.password}
                error={errors.password}
                onChange={(e) => setForm({ ...form, password: e.target.value })}
            />
            <button className="rounded bg-indigo-600 px-4 py-2 text-white">Kirish</button>
            <p className="text-sm text-gray-600">
                Akkaunt yo'qmi?{' '}
                <Link to="/register" className="text-indigo-600">
                    Ro'yxatdan o'ting
                </Link>
            </p>
        </form>
    );
}
